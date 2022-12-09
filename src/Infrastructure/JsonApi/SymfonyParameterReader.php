<?php

/**
 * This file is part of forum
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Infrastructure\JsonApi;

use App\Application\Query\ParameterReader;
use Symfony\Component\HttpFoundation\Request;

/**
 * SymfonyParameterReader
 *
 * @package App\Infrastructure\JsonApi
 */
final class SymfonyParameterReader implements ParameterReader
{

    public function __construct(private readonly Request $request)
    {}

    public function pagination(): ?array
    {
        $page = $this->request->query->all();
        if (!array_key_exists('page', $page) || !is_array($page['page'])) {
            return null;
        }

        $validKeys = ['offset', 'limit'];
        $params = [];
        foreach ($page['page'] as $name => $param) {
            if (in_array($name, $validKeys)) {
                $params[$name] = $param;
            }
        }

        return $params;
    }
}