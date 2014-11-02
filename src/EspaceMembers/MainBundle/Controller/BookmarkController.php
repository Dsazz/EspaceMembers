<?php

namespace EspaceMembers\MainBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use EspaceMembers\MainBundle\Entity\Teaching;

class BookmarkController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        return $this->render('EspaceMembersMainBundle:Bookmark:index.html.twig', array(
            'user' => $em->getRepository('EspaceMembersMainBundle:User')
                ->findUserBookmark($this->getUser()->getId()),
        ));
    }

    /**
     * @ParamConverter(name="teaching")
     */
    public function addAction(Teaching $teaching)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $user->addBookmark($teaching);
        $em->merge($user);
        $em->flush();
        $response["success"] = true;

        return new JsonResponse($response);
    }

    /**
     * @ParamConverter(name="teaching")
     */
    public function removeAction(Teaching $teaching)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $user->removeBookmark($teaching);
        $em->merge($user);
        $em->flush();
        $response["success"] = true;

        return new JsonResponse($response);
    }
}
