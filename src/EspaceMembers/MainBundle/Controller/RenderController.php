<?php

namespace EspaceMembers\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class RenderController extends Controller
{
    public function rightBlockAction($currentPath)
    {
        $em = $this->getDoctrine()->getManager();

        return $this->render('EspaceMembersMainBundle:Template\right-block:index.html.twig', array(
            'chronologies' => $em->getRepository('EspaceMembersMainBundle:Event')->getYears(),
            'categories'   => $em->getRepository('EspaceMembersMainBundle:Category')->getNames(),
            'directions'   => $em->getRepository('EspaceMembersMainBundle:Direction')->getNames(),
            'teachers'     => $em->getRepository('ApplicationSonataUserBundle:User')->findTeachers(),
            'tags'         => $em->getRepository('EspaceMembersMainBundle:Tag')->getNames(),
            'currentPath'  => $currentPath
        ));
    }
}
