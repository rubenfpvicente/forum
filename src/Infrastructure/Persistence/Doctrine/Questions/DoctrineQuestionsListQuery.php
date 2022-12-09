<?php

/**
 * This file is part of forum
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);


namespace App\Infrastructure\Persistence\Doctrine\Questions;

use App\Application\Questions\Models\QuestionEntry;
use App\Application\Questions\QuestionsListQuery;
use App\Domain\UserManagement\User\UserId;
use App\Infrastructure\JsonApi\SchemaDiscovery\Attributes\AsResourceCollection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Query\QueryBuilder;
use Traversable;

/**
 * DoctrineQuestionsListQuery
 *
 * @package App\Infrastructure\Persistence\Doctrine\Questions
 */
#[AsResourceCollection(
    type: 'questions',
    metaFromMethod: "metaData",
    linksFromMethod: "paginationLinks"
)]
final class DoctrineQuestionsListQuery extends QuestionsListQuery
{

    public function __construct(private readonly Connection $connection)
    {
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    protected function executeCountQuery(): int
    {
        $query = $this->connection->createQueryBuilder()
            ->select("count(*) as total")
            ->from("questions", "q")
            ->leftJoin("q", "users", "u", "q.owner_id = u.id");

        $this->filterOwner($query);

        return (int) $query
            ->executeQuery()
            ->fetchOne();
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    protected function executeQuery(): Traversable
    {
        $query = $this->connection->createQueryBuilder()
            ->select("q.id as questionId, q.title, q.body, u.id as userId, u.name, u.email")
            ->from("questions", "q")
            ->leftJoin("q", "users", "u", "q.owner_id = u.id");
        $this->filterOwner($query);
        $data = $query
            ->setMaxResults($this->pagination()->rowsPerPage())
            ->setFirstResult($this->pagination()->offset())
            ->executeQuery()
            ->fetchAllAssociative();

        $questions = new ArrayCollection();

        foreach ($data as $entry) {
            $questions->add(new QuestionEntry($entry));
        }

        return $questions;
    }

    /**
     * @inheritDoc
     */
    protected function baseUrlPath(): string
    {
        return "/questions";
    }

    private function filterOwner(QueryBuilder $query)
    {
        $param = $this->param(self::OWNER_FILTER, self::OWNER_ALL);
        switch ($param) {
            case self::OWNER_ALL;
                return;

            case self::OWNER_SELF;
                $userIdParam = $this->param(self::PARAM_USER_ID);
                break;

            case self::OWNER_OTHERS:
                $userIdParam = $this->param(self::PARAM_USER_ID);
                $query->where('u.id != :userId')
                    ->setParameter('userId', $userIdParam);
                return;


            default:
                $userIdParam = (string) new UserId($param);
        }


        $query->where('u.id = :userId')
            ->setParameter('userId', $userIdParam);
    }
}