<?php
namespace EspaceMembers\MainBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use EspaceMembers\MainBundle\Entity\Event;
use EspaceMembers\MainBundle\Entity\User;
use EspaceMembers\MainBundle\Entity\Teaching;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Pagerfanta\Exception\NotValidCurrentPageException;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;

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

            /* Check this */
            'bookmarks' => $this->getUser()->getBookmarks(),
        ));
    }

    private function setCurrentPageOr404($pagerfanta, $page)
    {
        try {
            $pagerfanta->setCurrentPage($page);
        } catch (NotValidCurrentPageException $e) {
            throw new NotFoundHttpException();
        }
    }

    public function filterChronologyAction($year, $page)
    {
        $em = $this->getDoctrine()->getManager();
        $pagerfanta = $em->getRepository('EspaceMembersMainBundle:Event')
            ->filterByYearWithPaging($year, $this->getUser());

        $this->setCurrentPageOr404($pagerfanta, $page);

        return $this->render('EspaceMembersMainBundle:Teaching:index.html.twig', array(
            'paginator'  => $pagerfanta,
            'events'     => $pagerfanta->getCurrentPageResults(),
        ));
    }

    public function filterCategoryAction($category_id, $page)
    {
        $em = $this->getDoctrine()->getManager();
        $pagerfanta = $em->getRepository('EspaceMembersMainBundle:Event')
            ->filterByCategoryWithPaging($category_id, $this->getUser()->getId());

        $this->setCurrentPageOr404($pagerfanta, $page);

        return $this->render('EspaceMembersMainBundle:Teaching:index.html.twig', array(
            'paginator'  => $pagerfanta,
            'events'     => $pagerfanta->getCurrentPageResults(),
        ));
    }

    public function filterDirectionAction($direction_id, $page)
    {
        $em = $this->getDoctrine()->getManager();
        $pagerfanta = $em->getRepository('EspaceMembersMainBundle:Event')
            ->filterByDirectionWithPaging($direction_id, $this->getUser()->getId());

        $this->setCurrentPageOr404($pagerfanta, $page);

        return $this->render('EspaceMembersMainBundle:Teaching:index.html.twig', array(
            'paginator'  => $pagerfanta,
            'events'     => $pagerfanta->getCurrentPageResults(),
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
        ));
    }

    public function filterTagAction($tag_id, $page)
    {
        $em = $this->getDoctrine()->getManager();

        $filteredEvents = array_merge(
            $em->getRepository('EspaceMembersMainBundle:Event')->filterByTagTeaching($tag_id, $this->getUser()->getId()),
            $em->getRepository('EspaceMembersMainBundle:Event')->filterByTagEvent($tag_id, $this->getUser()->getId())
        );

        $adapter = new ArrayAdapter($filteredEvents);
        $pagerfanta = new Pagerfanta($adapter);

        $pagerfanta->setMaxPerPage(Event::MAX_PER_PAGE);

        $this->setCurrentPageOr404($pagerfanta, $page);

        return $this->render('EspaceMembersMainBundle:Teaching:index.html.twig', array(
            'paginator'  => $pagerfanta,
            'events'     => $pagerfanta->getCurrentPageResults(),
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
                ->findTeachingsByEvent($teacher_id, $event->getId()),
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
        return $this->render('EspaceMembersMainBundle:Teaching:detail.html.twig', array(
            'event' => $event,
        ));
    }
}
