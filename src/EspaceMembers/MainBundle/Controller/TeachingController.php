<?php

namespace EspaceMembers\MainBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class TeachingController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $chronologiesAll = $em->getRepository('EspaceMembersMainBundle:Chronology')->findAll();
        $categoriesAll   = $em->getRepository('EspaceMembersMainBundle:Category')->findAll();
        $voiesAll        = $em->getRepository('EspaceMembersMainBundle:Voie')->findAll();
        $teachersAll     = $em->getRepository('EspaceMembersMainBundle:User')->findTeachers();
        $tagsAll         = $em->getRepository('EspaceMembersMainBundle:Tag')->findAll();

        return $this->render('EspaceMembersMainBundle:Default:index.html.twig', array(
            'chronologies' => $chronologiesAll,
            'categories'   => $categoriesAll,
            'voies'        => $voiesAll,
            'teachers'     => $teachersAll,
            'tags'         => $tagsAll,
        ));
    }

    public function filterChronologyAction($chronology_id)
    {
        $em = $this->getDoctrine()->getManager();

        $chronologiesAll = $em->getRepository('EspaceMembersMainBundle:Chronology')->findAll();
        $categoriesAll   = $em->getRepository('EspaceMembersMainBundle:Category')->findAll();
        $voiesAll        = $em->getRepository('EspaceMembersMainBundle:Voie')->findAll();
        $teachersAll     = $em->getRepository('EspaceMembersMainBundle:User')->findTeachers();
        $tagsAll         = $em->getRepository('EspaceMembersMainBundle:Tag')->findAll();

        $filteredChronologies = $em->getRepository('EspaceMembersMainBundle:Chronology')
            ->findBy(
                array('id' => $chronology_id),
                array('year' => 'ASC')
        );

        return $this->render('EspaceMembersMainBundle:Default:index.html.twig', array(
            'filtered_chronologies'  => $filteredChronologies,
            'filtered_chronology_id' => $chronology_id,
            'filtered_accordion'    => '0',

            'chronologies' => $chronologiesAll,
            'categories'   => $categoriesAll,
            'voies'        => $voiesAll,
            'teachers'     => $teachersAll,
            'tags'         => $tagsAll,
        ));
    }

    public function filterCategoryAction($category_id)
    {
        $em = $this->getDoctrine()->getManager();
        $filteredChronologies = new ArrayCollection();
        $filteredEvents       = new ArrayCollection();

        $chronologiesAll = $em->getRepository('EspaceMembersMainBundle:Chronology')->findAll();
        $categoriesAll   = $em->getRepository('EspaceMembersMainBundle:Category')->findAll();
        $voiesAll        = $em->getRepository('EspaceMembersMainBundle:Voie')->findAll();
        $teachersAll     = $em->getRepository('EspaceMembersMainBundle:User')->findTeachers();
        $tagsAll         = $em->getRepository('EspaceMembersMainBundle:Tag')->findAll();

        $filteredCategory = $em->getRepository('EspaceMembersMainBundle:Category')
            ->findOneBy(
                array('id' => $category_id)
        );

        $filteredEvents = $filteredCategory->getEvents();
        foreach( $filteredEvents->toArray() as $event ) {
            if (false === $filteredChronologies->contains($event)) {
                $filteredChronologies->add($event->getChronology());
            }
        }

        return $this->render('EspaceMembersMainBundle:Default:index.html.twig', array(
            'filtered_chronologies' => $filteredChronologies,
            'filtered_category_id'  => $category_id,
            'filtered_accordion'    => '1',

            'chronologies' => $chronologiesAll,
            'categories'   => $categoriesAll,
            'voies'        => $voiesAll,
            'teachers'     => $teachersAll,
            'tags'         => $tagsAll,
        ));
    }

    public function filterTeacherAction($teacher_id)
    {
        $em = $this->getDoctrine()->getManager();
        $filteredChronologies = new ArrayCollection();
        $filteredEvents       = new ArrayCollection();

        $chronologiesAll = $em->getRepository('EspaceMembersMainBundle:Chronology')->findAll();
        $categoriesAll   = $em->getRepository('EspaceMembersMainBundle:Category')->findAll();
        $voiesAll        = $em->getRepository('EspaceMembersMainBundle:Voie')->findAll();
        $teachersAll     = $em->getRepository('EspaceMembersMainBundle:User')->findTeachers();
        $tagsAll         = $em->getRepository('EspaceMembersMainBundle:Tag')->findAll();

        $filteredTeacher = $em->getRepository('EspaceMembersMainBundle:User')
            ->findOneBy(
                array('id' => $teacher_id)
        );

        $filteredEvents = $filteredTeacher->getEvents();
        foreach( $filteredEvents->toArray() as $event ) {
            if (false === $filteredChronologies->contains($event)) {
                $event->getUsers()->clear();
                $event->addUser($filteredTeacher);
                $filteredChronologies->add($event->getChronology());
            }
        }

        return $this->render('EspaceMembersMainBundle:Default:index.html.twig', array(
            'filtered_chronologies' => $filteredChronologies,
            'filtered_teacher_id'   => $teacher_id,
            'filtered_accordion'    => '3',

            'chronologies' => $chronologiesAll,
            'categories'   => $categoriesAll,
            'voies'        => $voiesAll,
            'teachers'     => $teachersAll,
            'tags'         => $tagsAll,
        ));
    }

    public function filterVoieAction($voie_id)
    {
        $em = $this->getDoctrine()->getManager();
        $filteredChronologies = new ArrayCollection();
        $filteredEvents       = new ArrayCollection();
        $filteredTeachings    = new ArrayCollection();

        $chronologiesAll = $em->getRepository('EspaceMembersMainBundle:Chronology')->findAll();
        $categoriesAll   = $em->getRepository('EspaceMembersMainBundle:Category')->findAll();
        $voiesAll        = $em->getRepository('EspaceMembersMainBundle:Voie')->findAll();
        $teachersAll     = $em->getRepository('EspaceMembersMainBundle:User')->findTeachers();
        $tagsAll         = $em->getRepository('EspaceMembersMainBundle:Tag')->findAll();

        $filteredVoie = $em->getRepository('EspaceMembersMainBundle:Voie')
            ->findOneBy(
                array('id' => $voie_id)
        );

        $filteredTeachings = $filteredVoie->getTeachings();
        foreach( $filteredTeachings->toArray() as $teaching ) {
            $event = $teaching->getEvent();
            $event->getUsers()->clear();
            foreach( $teaching->getUsers()->toArray() as $user ) {
                $user->getTeachings()->clear();
            }
        }

        foreach( $filteredTeachings->toArray() as $teaching ) {
            $event = $teaching->getEvent();
            foreach( $teaching->getUsers()->toArray() as $user ) {
                $user->addTeaching($teaching);
                $event->addUser($user);
            }

            $chronology = $event->getChronology();

            //echo "<pre>";
            //\Doctrine\Common\Util\Debug::dump($chronology);die;
            if( $filteredChronologies->contains($chronology) ) {
                $chronology = $this->filteringCollectionById($filteredChronologies, $chronology->getId());

                if( $chronology->getEvents()->contains($event) ) {
                    $event_curr = $this->filteringCollectionById($chronology->getEvents(), $event->getId());
                    foreach( $event->getUsers()->toArray() as $user ) {
                        $event_curr->addUser($user);
                    }
                }
            } else {
                if( $chronology->getEvents()->contains($event) ) {
                    $event_curr = $this->filteringCollectionById($chronology->getEvents(), $event->getId());
                    foreach( $event->getUsers()->toArray() as $user ) {
                        $event_curr->addUser($user);
                    }
                }

                $filteredChronologies->add($chronology);
            }
        }

        $iterator = $filteredChronologies->getIterator();

        $iterator->uasort(function ($first, $second) {
            return (int) $first->getYear() > (int) $second->getYear() ? 1 : -1;
        });

        $filteredChronologies = new ArrayCollection(iterator_to_array($iterator));

        return $this->render('EspaceMembersMainBundle:Default:index.html.twig', array(
            'filtered_chronologies' => $filteredChronologies,
            'filtered_voie_id'      => $voie_id,
            'filtered_accordion'    => '2',

            'chronologies' => $chronologiesAll,
            'categories'   => $categoriesAll,
            'voies'        => $voiesAll,
            'teachers'     => $teachersAll,
            'tags'         => $tagsAll,
        ));
    }

    public function filterTagAction($tag_id)
    {
        $em = $this->getDoctrine()->getManager();
        $filteredChronologies = new ArrayCollection();
        $filteredEvents       = new ArrayCollection();
        $filteredTeachings    = new ArrayCollection();

        $chronologiesAll = $em->getRepository('EspaceMembersMainBundle:Chronology')->findAll();
        $categoriesAll   = $em->getRepository('EspaceMembersMainBundle:Category')->findAll();
        $voiesAll        = $em->getRepository('EspaceMembersMainBundle:Voie')->findAll();
        $teachersAll     = $em->getRepository('EspaceMembersMainBundle:User')->findTeachers();
        $tagsAll         = $em->getRepository('EspaceMembersMainBundle:Tag')->findAll();

        $filteredTag = $em->getRepository('EspaceMembersMainBundle:Tag')
            ->findOneBy(
                array('id' => $tag_id)
        );

        $filteredTeachings = $filteredTag->getTeachings();
        $filteredEvents    = $filteredTag->getEvents();
        foreach($filteredTeachings->toArray() as $teaching) {
            if( $filteredEvents->contains($teaching->getEvent()) ) {
                $event = $this->filteringCollectionById($filteredEvents, $teaching->getEvent()->getId());
                if( $event->getTags()->contains($filteredTag) === false ) {
                    $event->getTeachings()->addTeaching($teaching);
                }
            } else {
                $event = $teaching->getEvent();
                $event->getTeachings()->clear();
                $event->addTeaching($teaching);
                $filteredEvents->add($event);
            }
        }

        foreach( $filteredEvents->toArray() as $event ) {
            if( $filteredChronologies->contains( $event->getChronology() ) === false ) {
                $filteredChronologies->add( $event->getChronology() );
            }
        }

        $iterator = $filteredChronologies->getIterator();

        $iterator->uasort(function ($first, $second) {
            return (int) $first->getYear() > (int) $second->getYear() ? 1 : -1;
        });

        $filteredChronologies = new ArrayCollection(iterator_to_array($iterator));

        return $this->render('EspaceMembersMainBundle:Default:index.html.twig', array(
            'filtered_chronologies' => $filteredChronologies,
            'filtered_tag_id'       => $tag_id,
            'filtered_accordion'    => '4',

            'chronologies' => $chronologiesAll,
            'categories'   => $categoriesAll,
            'voies'        => $voiesAll,
            'teachers'     => $teachersAll,
            'tags'         => $tagsAll,
        ));
    }

    public function filteringCollectionById($collection, $id) {
        $collection->filter(
            function($element) use ($id) {
                return $element->getId() === $id;
            });
        return $collection->first();
    }

    //public function rightBlockAction(Request $request)
    //{
        //$em = $this->getDoctrine()->getManager();
        //$chronologiesAll = $em->getRepository('EspaceMembersMainBundle:Chronology')->findAll();
        //$categoriesAll   = $em->getRepository('EspaceMembersMainBundle:Category')->findAll();
        //$voiesAll        = $em->getRepository('EspaceMembersMainBundle:Voie')->findAll();
        //$teachersAll     = $em->getRepository('EspaceMembersMainBundle:User')->findTeachers();
        //$tagsAll         = $em->getRepository('EspaceMembersMainBundle:Tag')->findAll();

        //return $this->render('EspaceMembersMainBundle:Default:enseignements-right-block.html.twig', array(
            //'chronologies' => $chronologiesAll,
            //'categories'   => $categoriesAll,
            //'voies'        => $voiesAll,
            //'teachers'     => $teachersAll,
            //'tags'         => $tagsAll,
        //));
    //}

    public function playAction($event_id, $teacher_id, $teaching_id) {
        $em = $this->getDoctrine()->getManager();

        $event    = $em->getRepository('EspaceMembersMainBundle:Event')->find($event_id);
        $teacher  = $em->getRepository('EspaceMembersMainBundle:User')->find($teacher_id);
        $teaching = $em->getRepository('EspaceMembersMainBundle:Teaching')->find($teaching_id);

        return $this->render('EspaceMembersMainBundle:Default:enseignements-play.html.twig', array(
            'event'        => $event,
            'teacherCurr'  => $teacher,
            'teachingCurr' => $teaching
        ));
    }

    public function viewDetailAction($event_id) {
        $em = $this->getDoctrine()->getManager();

        $event = $em->getRepository('EspaceMembersMainBundle:Event')->find($event_id);
        return $this->render('EspaceMembersMainBundle:Default:enseignements-detail.html.twig', array(
            'event' => $event,
        ));
    }
}
