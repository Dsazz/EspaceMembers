<?php
namespace EspaceMembers\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Pagerfanta\Exception\NotValidCurrentPageException;

use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;

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
