<?php

namespace EspaceMembers\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CommunityController extends Controller
{
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        return $this->render('EspaceMembersMainBundle:Community:communaute.html.twig', array(
            'users'  => $em->getRepository('EspaceMembersMainBundle:User')->findTeachers(),
            'groups' => $em->getRepository('EspaceMembersMainBundle:Group')->findAllNames(),
        ));
    }

}
