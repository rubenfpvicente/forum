<?php

/**
 * This file is part of forum
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\UserInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * HomePageController
 *
 * @package App\UserInterface
 */
final class HomePageController extends AbstractController
{

    #[Route(path: "/")]
    public function documentation(): Response
    {
        return $this->redirect('/docs/home.html');
    }
}