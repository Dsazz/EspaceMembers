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
            'users'  => $em->getRepository('ApplicationSonataUserBundle:User')->findTeachers(),
            'groups' => $em->getRepository('ApplicationSonataUserBundle:Group')->findAllNames(),
        ));
    }

    public function filterByGroupAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        return $this->render('EspaceMembersMainBundle:Community:communaute.html.twig', array(
            'users'  => $em->getRepository('ApplicationSonataUserBundle:User')
                ->findTeachersByGroup($request->get('group_name')),
            'groups' => $em->getRepository('ApplicationSonataUserBundle:Group')->findAllNames(),
        ));
    }

}
