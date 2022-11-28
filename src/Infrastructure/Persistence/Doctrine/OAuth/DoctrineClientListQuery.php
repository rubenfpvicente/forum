<?php

/**
 * This file is part of forum
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\OAuth;

use App\Application\OAuth\ClientListQuery;
use App\Application\OAuth\Model\Client;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Traversable;

/**
 * DoctrineClientListQuery
 *
 * @package App\Infrastructure\Persistence\Doctrine\OAuth
 */
final class DoctrineClientListQuery extends ClientListQuery
{

    /**
     * Creates a DoctrineClientListQuery
     *
     * @param Connection $connection
     */
    public function __construct(private readonly Connection $connection)
    {
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    protected function executeCountQuery(): int
    {
        return (int) $this->connection->createQueryBuilder()
            ->select('COUNT(*) AS total')
            ->from('clients', 'c')
            ->executeQuery()
            ->fetchOne();
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    protected function executeQuery(): Traversable
    {
        $data = $this->connection->createQueryBuilder()
            ->select('c.*')
            ->from('clients', 'c')
            ->setMaxResults($this->pagination()->rowsPerPage())
            ->setFirstResult($this->pagination()->offset())
            ->executeQuery()
            ->fetchAllAssociative();

        if (!is_iterable($data)) {
            return new ArrayCollection();
        }

        $results = new ArrayCollection();
        foreach ($data as $datum) {
            $results->add(new Client($datum));
        }

        return $results;
    }
}
