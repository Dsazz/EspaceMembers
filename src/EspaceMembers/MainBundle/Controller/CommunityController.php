<?php

namespace EspaceMembers\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CommunityController extends Controller
{
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('EspaceMembersMainBundle:User')->findAll();
        $groups = $em->getRepository('EspaceMembersMainBundle:Group')->findAll();

        return $this->render('EspaceMembersMainBundle:Community:communaute.html.twig', array(
            'users' => $users,
            'groups' => $groups,
        ));
    }

}
