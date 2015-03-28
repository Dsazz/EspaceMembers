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
            'categories'   => $em->getRepository('EspaceMembersMainBundle:Category')->getTitles(),
            'directions'   => $em->getRepository('EspaceMembersMainBundle:Voie')->getTitles(),
            'teachers'     => $em->getRepository('EspaceMembersMainBundle:User')->findTeachers(),
            'tags'         => $em->getRepository('EspaceMembersMainBundle:Tag')->getTitles(),
            'currentPath'  => $currentPath
        ));
    }
}
