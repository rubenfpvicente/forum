<?php

/**
 * This file is part of forum
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Application\Query;

use App\Application\Query;
use Traversable;

/**
 * AbstractQuery
 *
 * @package App\Application\Query
 */
abstract class AbstractQuery implements Query
{

    protected ?Traversable $data = null;
    private array $params = [];

    /**
     * Execute and retrieve query data
     *
     * @return Traversable
     */
    abstract protected function executeQuery(): Traversable;

    /**
     * @inheritDoc
     */
    public function getIterator(): Traversable
    {
        if (!$this->data) {
            $this->data = $this->executeQuery();
        }
        return $this->data;
    }

    /**
     * @inheritDoc
     */
    public function params(): array
    {
        return $this->params;
    }

    /**
     * @inheritDoc
     */
    public function hasParam(string $name): bool
    {
        return array_key_exists($name, $this->params);
    }

    /**
     * @inheritDoc
     */
    public function param(string $name, mixed $default = null): mixed
    {
        if ($this->hasParam($name)) {
            return $this->params[$name];
        }

        return $default;
    }

    /**
     * @inheritDoc
     */
    public function withParam(string $name, mixed $value): self
    {
        $clone = clone $this;
        $clone->data = null;
        $clone->params[$name] = $value;
        return $clone;
    }
}
