<?php

/**
 * This file is part of forum
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Application\OAuth;

use App\Application\PaginatedQuery;
use App\Application\Query\AbstractPaginatedQuery;

/**
 * ClientListQuery
 *
 * @package App\Application\OAuth
 */
abstract class ClientListQuery extends AbstractPaginatedQuery implements PaginatedQuery
{

}