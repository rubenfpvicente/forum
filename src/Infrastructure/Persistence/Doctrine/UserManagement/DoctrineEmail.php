<?php

/**
 * This file is part of forum
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\UserManagement;

use App\Domain\UserManagement\User\Email;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

/**
 * DoctrineEmail
 *
 * @package App\Infrastructure\Persistence\Doctrine\UserManagement
 */
final class DoctrineEmail extends StringType
{
    public const NAME = 'Email';

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
    public function convertToPHPValue($value, AbstractPlatform $platform): ?Email
    {
        if ($value === null || $value === '') {
            return null;
        }

        return new Email($value);
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
