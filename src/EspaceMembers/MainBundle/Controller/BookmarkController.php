<?php

namespace EspaceMembers\MainBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use EspaceMembers\MainBundle\Entity\Teaching;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Pagerfanta\Exception\NotValidCurrentPageException;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;

class BookmarkController extends Controller
{
    public function indexAction($page)
    {
        $em = $this->getDoctrine()->getManager();

        $bookmarkData = $em->getRepository('EspaceMembersMainBundle:User')
            ->getBookmarksData($this->getUser());

        $pagerfanta = $em->getRepository('EspaceMembersMainBundle:Event')
            ->findBookmarks($bookmarkData);

        $this->setCurrentPageOr404($pagerfanta, $page);

        return $this->render('EspaceMembersMainBundle:Teaching:index.html.twig', array(
            'paginator'   => $pagerfanta,
            'events'      => $pagerfanta->getCurrentPageResults(),
            'bookmarksId' => $em->getRepository('EspaceMembersMainBundle:User')
                ->getBookmarksId($this->getUser()),
        ));
    }

    /**
     * @ParamConverter(name="teaching")
     */
    public function addAction(Teaching $teaching)
    {
        try {
            $user = $this->getUser();
            $user->addBookmark($teaching);
            $this->getDoctrine()->getManager()->flush();

            return new JsonResponse(array('success' => 'Bookmark success added'), Response::HTTP_OK);
        } catch (AccessDeniedException $e) {
            return new JsonResponse(array('error' => 'You don`t have access'), Response::HTTP_FORBIDDEN);
        } catch (NotFoundHttpException $e) {
            return new JsonResponse(array('error' => 'Bookmark not found'), Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @ParamConverter(name="teaching")
     */
    public function removeAction(Teaching $teaching)
    {
        try {
            $user = $this->getUser();
            $user->removeBookmark($teaching);
            $this->getDoctrine()->getManager()->flush();

            return new JsonResponse(array('success' => 'Bookmark success added'), Response::HTTP_OK);
        } catch (AccessDeniedException $e) {
            return new JsonResponse(array('error' => 'You don`t have access'), Response::HTTP_FORBIDDEN);
        } catch (NotFoundHttpException $e) {
            return new JsonResponse(array('error' => 'Bookmark not found'), Response::HTTP_NOT_FOUND);
        }
    }

    private function setCurrentPageOr404($pagerfanta, $page)
    {
        try {
            $pagerfanta->setCurrentPage($page);
        } catch (NotValidCurrentPageException $e) {
            throw new NotFoundHttpException();
        }
    }
}
