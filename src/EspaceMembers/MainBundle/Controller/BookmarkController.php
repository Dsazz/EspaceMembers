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

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use EspaceMembers\MainBundle\Controller\BaseController as Controller;
use EspaceMembers\MainBundle\Entity\Teaching;

/**
 * BookmarkController
 *
 * @author Stepanenko Stanislav <dsazztazz@gmail.com>
 */
class BookmarkController extends Controller
{
    public function indexAction($page)
    {
        $em = $this->getDoctrine()->getManager();

        $bookmarkData = $em->getRepository('ApplicationSonataUserBundle:User')
            ->getBookmarksData($this->getUser());

        $pagerfanta = $em->getRepository('EspaceMembersMainBundle:Event')
            ->findBookmarks($bookmarkData);

        $this->setCurrentPageOr404($pagerfanta, $page);

        return $this->render('EspaceMembersMainBundle:Teaching:index.html.twig', array(
            'paginator'   => $pagerfanta,
            'events'      => $pagerfanta->getCurrentPageResults(),
            'bookmarksId' => $em->getRepository('ApplicationSonataUserBundle:User')
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
}
