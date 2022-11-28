<?php

/**
 * This file is part of forum
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\UserManagement;

use App\Application\UserManagement\UserListQuery;
use App\Domain\UserManagement\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Traversable;

/**
 * DoctrineUserListQuery
 *
 * @package App\Infrastructure\Persistence\Doctrine\UserManagement
 */
final class DoctrineUserListQuery extends UserListQuery
{

    public function __construct(private readonly EntityManagerInterface $entityManager)
    {

    }


    /**
     * @inheritDoc
     * @throws Exception
     */
    protected function executeCountQuery(): int
    {
        return (int) $this->entityManager->getConnection()->createQueryBuilder()
            ->select('COUNT(*) AS total')
            ->from('users')
            ->executeQuery()
            ->fetchOne();
    }

    /**
     * @inheritDoc
     */
    protected function executeQuery(): Traversable
    {
        $result = $this->entityManager->createQueryBuilder()
            ->select('u.name', 'u.userId', 'u.email')
            ->from(User::class, 'u')
            ->setMaxResults($this->pagination()->rowsPerPage())
            ->setFirstResult($this->pagination()->offset())
            ->getQuery()
            ->getResult();

        if (is_array($result)) {
            return new ArrayCollection($result);
        }

        return new ArrayCollection();
    }
}