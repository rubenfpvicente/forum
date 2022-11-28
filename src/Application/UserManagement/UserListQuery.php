<?php

/**
 * This file is part of forum
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Application\UserManagement;

use App\Application\PaginatedQuery;
use App\Application\Query\AbstractPaginatedQuery;

/**
 * UserListQuery
 *
 * @package App\Application\UserManagement
 */
abstract class UserListQuery extends AbstractPaginatedQuery implements PaginatedQuery
{

}
