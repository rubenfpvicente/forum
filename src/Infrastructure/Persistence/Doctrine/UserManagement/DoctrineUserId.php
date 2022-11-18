<?php

/**
 * This file is part of forum
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\UserManagement;

use App\Domain\UserManagement\User\UserId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;

/**
 * DoctrineUserId
 *
 * @package App\Infrastructure\Persistence\Doctrine\UserManagement
 */
final class DoctrineUserId extends GuidType
{
    public const NAME = 'UserId';

    /**
     * @inheritDoc
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?UserId
    {
        if ($value === null || $value === '') {
            return null;
        }

        return new UserId($value);
    }

    /**
     * @inheritDoc
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        if ($value === null || $value === '') {
            return null;
        }

        return (string) $value;
    }


    /**
     * @inheritDoc
     */
    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return static::NAME;
    }

    /**
     * @inheritDoc
     */
    public function getMappedDatabaseTypes(AbstractPlatform $platform): array
    {
        return [self::NAME];
    }
}
