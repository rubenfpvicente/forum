<?php

/**
 * This file is part of forum
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Application;

/**
 * CommandHandler
 *
 * @package App\Application
 */
interface CommandHandler
{
    /**
     * Handles a give command
     *
     * @param Command $command
     * @return mixed
     */
    public function handle(Command $command): mixed;
}
