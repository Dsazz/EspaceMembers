<?php

namespace EspaceMembers\MainBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RenderController extends Controller
{
    public function rightBlockAction() {
        $em = $this->getDoctrine()->getManager();

        return $this->render('EspaceMembersMainBundle:Template:right-block.html.twig', array(
            'chronologies' => $em->getRepository('EspaceMembersMainBundle:Chronology')->getYears(),
            'categories'   => $em->getRepository('EspaceMembersMainBundle:Category')->findAll(),
            'voies'        => $em->getRepository('EspaceMembersMainBundle:Voie')->findAll(),
            'teachers'     => $em->getRepository('EspaceMembersMainBundle:User')->findTeachers(),
            'tags'         => $em->getRepository('EspaceMembersMainBundle:Tag')->findAll(),
        ));
    }

    public function leftBlockAction() {
        $em = $this->getDoctrine()->getManager();

        return $this->render('EspaceMembersMainBundle:Template:left-block.html.twig', array(
            'chronologies' =>  $em->getRepository('EspaceMembersMainBundle:Chronology')->getYears(),
        ));
    }
}
