<?php

namespace EspaceMembers\MainBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\JsonResponse;
use EspaceMembers\MainBundle\Entity\Teaching;

class BookmarkController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        \Kint::dump($em->getRepository('EspaceMembersMainBundle:Chronology')
                ->findUserBookmark($this->getUser()->getId()));die;
        return $this->render('EspaceMembersMainBundle:Bookmark:index.html.twig', array(
            'chronologies' => $em->getRepository('EspaceMembersMainBundle:Chronology')
                ->findUserBookmark($this->getUser()->getId()),
        ));
    }

    /**
     * @ParamConverter(name="teaching")
     */
    public function addAction(Request $request, Teaching $teaching)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        if ($teaching) {
            $user->addBookmark($teaching);
            $em->merge($user);
            $em->flush();
            $response["success"] = true;
        }

        return new JsonResponse($response);
    }

    /**
     * @ParamConverter(name="teaching")
     */
    public function removeAction(Request $request, Teaching $teaching)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        if ($teaching) {
            $user->removeBookmark($teaching);
            $em->merge($user);
            $em->flush();
            $response["success"] = true;
        }

        return new JsonResponse($response);
    }
}
