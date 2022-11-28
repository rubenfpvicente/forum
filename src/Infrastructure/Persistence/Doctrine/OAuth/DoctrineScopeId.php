<?php

/**
 * This file is part of forum
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);


namespace App\Infrastructure\Persistence\Doctrine\OAuth;

use App\Domain\OAuth\Scope\ScopeId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

/**
 * DoctrineScopeId
 *
 * @package App\Infrastructure\Persistence\Doctrine\OAuth
 */
final class DoctrineScopeId extends StringType
{

    public const NAME = 'ScopeId';

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
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if ($value) {
            return (string) $value;
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?ScopeId
    {
        if  ($value) {
            return new ScopeId($value);
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function getMappedDatabaseTypes(AbstractPlatform $platform): array
    {
        return [self::NAME];
    }
}
