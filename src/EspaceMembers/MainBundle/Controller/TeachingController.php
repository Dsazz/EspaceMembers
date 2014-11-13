<?php

namespace EspaceMembers\MainBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use EspaceMembers\MainBundle\Entity\Event;
use EspaceMembers\MainBundle\Entity\User;
use EspaceMembers\MainBundle\Entity\Teaching;

class TeachingController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        return $this->render('EspaceMembersMainBundle:Teaching:index.html.twig', array(
            'chronologies' => $em->getRepository('EspaceMembersMainBundle:Chronology')
                ->findAllWithBookmarks($this->getUser()->getId()),
        ));
    }

    public function filterChronologyAction($chronology_id)
    {
        $em = $this->getDoctrine()->getManager();

        return $this->render('EspaceMembersMainBundle:Teaching:index.html.twig', array(
            'chronologies'  => $em->getRepository('EspaceMembersMainBundle:Chronology')
                ->filterById($chronology_id, $this->getUser()->getId()),
            'filteredId'   => $chronology_id,
            'accordion'    => '0',
        ));
    }

    public function filterCategoryAction($category_id)
    {
        $em = $this->getDoctrine()->getManager();

        return $this->render('EspaceMembersMainBundle:Teaching:index.html.twig', array(
            'chronologies'  => $em->getRepository('EspaceMembersMainBundle:Chronology')
                ->filterByCategory($category_id, $this->getUser()->getId()),
            'filteredId'   => $category_id,
            'accordion'    => '1',
        ));
    }

    public function filterVoieAction($voie_id)
    {
        $em = $this->getDoctrine()->getManager();

        return $this->render('EspaceMembersMainBundle:Teaching:index.html.twig', array(
            'chronologies' => $em->getRepository('EspaceMembersMainBundle:Chronology')
                ->filterByVoie($voie_id, $this->getUser()->getId()),
            'filteredId'   => $voie_id,
            'accordion'    => '2',
        ));
    }

    public function filterTeacherAction($teacher_id)
    {
        $em = $this->getDoctrine()->getManager();

        return $this->render('EspaceMembersMainBundle:Teaching:index.html.twig', array(
            'chronologies' => $em->getRepository('EspaceMembersMainBundle:Chronology')
                ->filterByTeacher($teacher_id),
            'filteredId'   => $teacher_id,
            'accordion'    => '3',
        ));
    }

    public function filterTagAction($tag_id)
    {
        $em = $this->getDoctrine()->getManager();
        $filteredChronologies = new ArrayCollection(
            array_merge(
                ($filteredByTeaching = $em->getRepository('EspaceMembersMainBundle:Chronology')
                    ->filterByTagTeaching($tag_id, $this->getUser()->getId()))
                    ? $filteredByTeaching : array(),
                ($filteredByTagEvent = $em->getRepository('EspaceMembersMainBundle:Chronology')
                    ->filterByTagEvent($tag_id, $this->getUser()->getId()))
                    ? $filteredByTagEvent : array()
            )
        );

        return $this->render('EspaceMembersMainBundle:Teaching:index.html.twig', array(
            'chronologies' => $filteredChronologies,
            'filteredId'   => $tag_id,
            'accordion'    => '4',
        ));
    }

    /**
     * @ParamConverter(name="teaching", class="EspaceMembersMainBundle:Teaching", options={"id" = "teaching_id"})
     */
    public function playAction($event_id, $teacher_id, Teaching $teaching)
    {
        $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository('EspaceMembersMainBundle:Event')->findEventWithTeachers($event_id);

        return $this->render('EspaceMembersMainBundle:Teaching:play.html.twig', array(
            'event'        => $event,
            'teacherCurr'  => $em->getRepository('EspaceMembersMainBundle:User')
                    ->findTeachingsByEvent($teacher_id, $event->getTitle()),
            'teachingCurr' => $teaching,
            'isBookmark'   => $em->getRepository('EspaceMembersMainBundle:User')
                    ->isBookmark($this->getUser()->getId(), $teaching->getId()),
        ));
    }

    /**
     * @ParamConverter(name="event", class="EspaceMembersMainBundle:Event", options={"id" = "event_id"})
     */
    public function viewDetailAction(Event $event)
    {
        $em = $this->getDoctrine()->getManager();

        return $this->render('EspaceMembersMainBundle:Teaching:detail.html.twig', array(
            'event' => $event,
        ));
    }
}
