<?php
namespace EspaceMembers\MainBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use EspaceMembers\MainBundle\Entity\Teaching;
use EspaceMembers\MainBundle\Controller\BaseController as Controller;

class TeachingController extends Controller
{
    public function indexAction($page)
    {
        $em = $this->getDoctrine()->getManager();
        $pagerfanta = $em->getRepository('EspaceMembersMainBundle:Event')->findAllWithPaging($page);

        $this->setCurrentPageOr404($pagerfanta, $page);

        return $this->render('EspaceMembersMainBundle:Teaching:index.html.twig', array(
            'paginator' => $pagerfanta,
            'events'  => $pagerfanta->getCurrentPageResults(),
            'bookmarksId' => $em->getRepository('EspaceMembersMainBundle:User')
                ->getBookmarksId($this->getUser()),
        ));
    }

    public function filterChronologyAction($year, $page)
    {
        $em = $this->getDoctrine()->getManager();
        $pagerfanta = $em->getRepository('EspaceMembersMainBundle:Event')
            ->filterByYearWithPaging($year);

        $this->setCurrentPageOr404($pagerfanta, $page);

        return $this->render('EspaceMembersMainBundle:Teaching:index.html.twig', array(
            'paginator'  => $pagerfanta,
            'events'     => $pagerfanta->getCurrentPageResults(),
            'bookmarksId' => $em->getRepository('EspaceMembersMainBundle:User')
                ->getBookmarksId($this->getUser()),
        ));
    }

    public function filterCategoryAction($category_id, $page)
    {
        $em = $this->getDoctrine()->getManager();
        $pagerfanta = $em->getRepository('EspaceMembersMainBundle:Event')
            ->filterByCategoryWithPaging($category_id);

        $this->setCurrentPageOr404($pagerfanta, $page);

        return $this->render('EspaceMembersMainBundle:Teaching:index.html.twig', array(
            'paginator'  => $pagerfanta,
            'events'     => $pagerfanta->getCurrentPageResults(),
            'bookmarksId' => $em->getRepository('EspaceMembersMainBundle:User')
                ->getBookmarksId($this->getUser()),
        ));
    }

    public function filterTeacherAction($teacher_id, $page)
    {
        $em = $this->getDoctrine()->getManager();
        $pagerfanta = $em->getRepository('EspaceMembersMainBundle:Event')
            ->filterByTeacherWithPaging($teacher_id);

        $this->setCurrentPageOr404($pagerfanta, $page);

        return $this->render('EspaceMembersMainBundle:Teaching:index.html.twig', array(
            'paginator'  => $pagerfanta,
            'events'     => $pagerfanta->getCurrentPageResults(),
            'bookmarksId' => $em->getRepository('EspaceMembersMainBundle:User')
                ->getBookmarksId($this->getUser()),
        ));
    }

    public function filterDirectionAction($direction_id, $page)
    {
        $em = $this->getDoctrine()->getManager();
        $pagerfanta = $em->getRepository('EspaceMembersMainBundle:Event')
            ->filterByDirectionWithPaging($direction_id);

        $this->setCurrentPageOr404($pagerfanta, $page);

        return $this->render('EspaceMembersMainBundle:Teaching:index.html.twig', array(
            'paginator'  => $pagerfanta,
            'events'     => $pagerfanta->getCurrentPageResults(),
            'bookmarksId' => $em->getRepository('EspaceMembersMainBundle:User')
                ->getBookmarksId($this->getUser()),
        ));
    }

    public function filterTagAction($tag_id, $page)
    {
        $em = $this->getDoctrine()->getManager();

        $pagerfanta = $em->getRepository('EspaceMembersMainBundle:Event')
            ->filterByTagTeachingsAndEvents($tag_id);

        $this->setCurrentPageOr404($pagerfanta, $page);

        return $this->render('EspaceMembersMainBundle:Teaching:index.html.twig', array(
            'paginator'  => $pagerfanta,
            'events'     => $pagerfanta->getCurrentPageResults(),
            'bookmarksId' => $em->getRepository('EspaceMembersMainBundle:User')
                ->getBookmarksId($this->getUser()),
        ));
    }

    public function playAction($event_id, $teacher_id, $teaching_id)
    {
        $em = $this->getDoctrine()->getManager();

        return $this->render('EspaceMembersMainBundle:Teaching:play.html.twig', array(
            'teachingCurr'=> $em->getRepository('EspaceMembersMainBundle:Teaching')
                ->findPartialOneById($teaching_id),

            'event' => $em->getRepository('EspaceMembersMainBundle:Event')
                ->findEventWithTeachers($event_id),

            'teacherCurr' => $em->getRepository('EspaceMembersMainBundle:User')
                ->findTeacherByEvent($teacher_id, $event_id),

            'bookmarksId' => $em->getRepository('EspaceMembersMainBundle:User')
                ->getBookmarksId($this->getUser()),
        ));
    }

    public function viewDetailAction($event_id)
    {
        $em = $this->getDoctrine()->getManager();

        return $this->render('EspaceMembersMainBundle:Teaching:detail.html.twig', array(
            'event' => $em->getRepository('EspaceMembersMainBundle:Event')
                ->findEventWithTeachers($event_id),
            'bookmarksId' => $em->getRepository('EspaceMembersMainBundle:User')
                ->getBookmarksId($this->getUser()),
        ));
    }
}
