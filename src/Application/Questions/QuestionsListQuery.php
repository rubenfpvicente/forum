<?php

/**
 * This file is part of forum
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);


namespace App\Application\Questions;

use App\Application\PaginatedQuery;
use App\Application\Query\AbstractPaginatedQuery;

/**
 * QuestionsListQuery
 *
 * @package App\Application\Questions
 */
abstract class QuestionsListQuery extends AbstractPaginatedQuery implements PaginatedQuery
{

    public const OWNER_ALL = 'all';
    public const OWNER_SELF = 'owner';

    public const OWNER_FILTER = 'owner';

    public const PARAM_USER_ID = 'userId';

    public const OWNER_OTHERS = 'others';
}
