<?php

/**
 * This file is part of forum
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\UserManagement;

use App\Domain\UserManagement\User\Password;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

/**
 * DoctrinePassword
 *
 * @package App\Infrastructure\Persistence\Doctrine\UserManagement
 */
final class DoctrinePassword extends StringType
{

    public const NAME = 'Password';

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return self::NAME;
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
    public function convertToPHPValue($value, AbstractPlatform $platform): ?Password
    {
        if ($value === null || $value === '') {
            return null;
        }

        return new Password($value);
    }

    /**
     * @inheritDoc
     */
    public function getMappedDatabaseTypes(AbstractPlatform $platform): array
    {
        return [self::NAME];
    }


    /**
     * @inheritDoc
     */
    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}