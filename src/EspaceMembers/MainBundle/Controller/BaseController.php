<?php
/**
 * This file is part of the EspaceMembers project.
 *
 * (c) Stanislav Stepanenko <dsazztazz@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EspaceMembers\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Pagerfanta\Exception\NotValidCurrentPageException;

use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;

/**
 * BaseController
 *
 * @author Stepanenko Stanislav <dsazztazz@gmail.com>
 */
class BaseController extends Controller
{
    protected function setCurrentPageOr404(Pagerfanta $pagerfanta, $page)
    {
        try {
            $pagerfanta->setCurrentPage($page);
        } catch (NotValidCurrentPageException $e) {
            throw new NotFoundHttpException();
        }
    }

    protected function getPagerfantaByArrayResults(array $arrayResults, $maxPerPage = 10)
    {
        $adapter = new ArrayAdapter($arrayResults);
        $pagerfanta = new Pagerfanta($adapter);

        return $pagerfanta->setMaxPerPage($maxPerPage);
    }
}
