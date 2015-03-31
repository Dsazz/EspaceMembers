<?php
namespace EspaceMembers\MainBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use EspaceMembers\MainBundle\Entity\Teaching;
use EspaceMembers\MainBundle\Entity\Event;
use EspaceMembers\MainBundle\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;
use EspaceMembers\MainBundle\Controller\BaseController as Controller;

class TeachingController extends Controller
{
    public function indexAction($page)
    {
        $em = $this->getDoctrine()->getManager();
        $pagerfanta = $em->getRepository('EspaceMembersMainBundle:Event')->findAllWithPaging($page);

        $this->setCurrentPageOr404($pagerfanta, $page);

        return $this->render('EspaceMembersMainBundle:Teaching:index.html.twig', array(
            'paginator'   => $pagerfanta,
            'events'      => $pagerfanta->getCurrentPageResults(),
            'bookmarksId' => $em->getRepository('ApplicationSonataUserBundle:User')
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
            'paginator'   => $pagerfanta,
            'events'      => $pagerfanta->getCurrentPageResults(),
            'bookmarksId' => $em->getRepository('ApplicationSonataUserBundle:User')
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
            'paginator'   => $pagerfanta,
            'events'      => $pagerfanta->getCurrentPageResults(),
            'bookmarksId' => $em->getRepository('ApplicationSonataUserBundle:User')
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
            'paginator'   => $pagerfanta,
            'events'      => $pagerfanta->getCurrentPageResults(),
            'bookmarksId' => $em->getRepository('ApplicationSonataUserBundle:User')
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
            'paginator'   => $pagerfanta,
            'events'      => $pagerfanta->getCurrentPageResults(),
            'bookmarksId' => $em->getRepository('ApplicationSonataUserBundle:User')
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
            'paginator'   => $pagerfanta,
            'events'      => $pagerfanta->getCurrentPageResults(),
            'bookmarksId' => $em->getRepository('ApplicationSonataUserBundle:User')
                ->getBookmarksId($this->getUser()),
        ));
    }

    /**
     * @ParamConverter("event", class="EspaceMembersMainBundle:Event", options={
     *    "repository_method" = "findEventWithTeachers",
     *    "id" = "event_id",
     *  })
     * @ParamConverter("teacher", class="ApplicationSonataUserBundle:User", converter="teacher_by_event_converter")
     */
    public function playAction(Event $event, UserInterface $teacher, $teaching_id)
    {
        $em = $this->getDoctrine()->getManager();

        $teaching = $em->getRepository('EspaceMembersMainBundle:Teaching')
                ->findPartialOneById($teaching_id);

        if (is_null($teaching) || empty($teaching)) {
            throw $this->createNotFoundException('The teaching does not exist');
        }

        return $this->render('EspaceMembersMainBundle:Teaching:play.html.twig', array(
            'event'        => $event,
            'teachingCurr' => $teaching,
            'teacherCurr'  => $teacher,
            'bookmarksId' => $em->getRepository('ApplicationSonataUserBundle:User')
                ->getBookmarksId($this->getUser()),
        ));
    }

    /**
     * @ParamConverter("event", class="EspaceMembersMainBundle:Event", options={
     *    "repository_method" = "findEventWithTeachers",
     *    "id" = "event_id",
     *  })
     */
    public function viewDetailAction(Event $event)
    {
        $em = $this->getDoctrine()->getManager();

        return $this->render('EspaceMembersMainBundle:Teaching:detail.html.twig', array(
            'event'       => $event,
            'bookmarksId' => $em->getRepository('ApplicationSonataUserBundle:User')
                ->getBookmarksId($this->getUser()),
        ));
    }
}
