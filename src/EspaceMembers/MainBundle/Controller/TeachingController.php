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
            'filteredId' => $year,
            'accordion'  => '0',
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
            'filteredId' => $category_id,
            'accordion'  => '1',
        ));
    }

    public function filterVoieAction($voie_id, $page)
    {
        $em = $this->getDoctrine()->getManager();
        $pagerfanta = $em->getRepository('EspaceMembersMainBundle:Event')
            ->filterByVoieWithPaging($voie_id, $this->getUser()->getId());

        $this->setCurrentPageOr404($pagerfanta, $page);

        return $this->render('EspaceMembersMainBundle:Teaching:index.html.twig', array(
            'paginator'  => $pagerfanta,
            'events'     => $pagerfanta->getCurrentPageResults(),
            'filteredId'   => $voie_id,
            'accordion'    => '2',
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
            'filteredId'   => $teacher_id,
            'accordion'    => '3',
        ));
    }

    public function filterTagAction($tag_id, $page)
    {
        $em = $this->getDoctrine()->getManager();
        $pagerfanta = $em->getRepository('EspaceMembersMainBundle:Event')
            ->filterTeachingsAndEventsByTagWithPaging($tag_id, $this->getUser()->getId());

        $this->setCurrentPageOr404($pagerfanta, $page);

        return $this->render('EspaceMembersMainBundle:Teaching:index.html.twig', array(
            'paginator'  => $pagerfanta,
            'events'     => $pagerfanta->getCurrentPageResults(),
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
