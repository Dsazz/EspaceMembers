<?php

namespace EspaceMembers\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class RenderController extends Controller
{
    public function rightBlockAction($accordion, $filteredId)
    {
        $em = $this->getDoctrine()->getManager();

        return $this->render('EspaceMembersMainBundle:Template:right-block.html.twig', array(
            'chronologies' => $em->getRepository('EspaceMembersMainBundle:Event')->getYears(),
            'categories'   => $em->getRepository('EspaceMembersMainBundle:Category')->getTitles(),
            'voies'        => $em->getRepository('EspaceMembersMainBundle:Voie')->getTitles(),
            'teachers'     => $em->getRepository('EspaceMembersMainBundle:User')->findTeachers(),
            'tags'         => $em->getRepository('EspaceMembersMainBundle:Tag')->getTitles(),
            'accordion'    => $accordion,
            'filteredId'   => $filteredId,
        ));
    }

    public function leftBlockAction()
    {
        $em = $this->getDoctrine()->getManager();

        return $this->render('EspaceMembersMainBundle:Template:left-block.html.twig', array(
            'events' =>  $em->getRepository('EspaceMembersMainBundle:Event')->findAllForEnseignements(),
        ));
    }
}
