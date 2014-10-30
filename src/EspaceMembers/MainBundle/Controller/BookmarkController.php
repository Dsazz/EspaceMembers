<?php

namespace EspaceMembers\MainBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\UserBundle\Model\UserInterface;

class BookmarkController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $securityContext = $this->container->get('security.context');
        $user = $securityContext->getToken()->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $chronologiesAll = $em->getRepository('EspaceMembersMainBundle:Chronology')->findAll();
        $categoriesAll   = $em->getRepository('EspaceMembersMainBundle:Category')->findAll();
        $voiesAll        = $em->getRepository('EspaceMembersMainBundle:Voie')->findAll();
        $teachersAll     = $em->getRepository('EspaceMembersMainBundle:User')->findTeachers();
        $tagsAll         = $em->getRepository('EspaceMembersMainBundle:Tag')->findAll();
        $filteredChronologies = new ArrayCollection();

        $userBookmarks = $user->getBookmarks();

        foreach( $userBookmarks->toArray() as $teaching ) {
            $event = $teaching->getEvent();
            $event->getUsers()->clear();
            foreach( $teaching->getUsers()->toArray() as $user ) {
                $user->getTeachings()->clear();
            }
        }

        foreach( $userBookmarks->toArray() as $teaching ) {
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
            'bookmarks_page'        => true,

            'chronologies'          => $chronologiesAll,
            'categories'            => $categoriesAll,
            'voies'                 => $voiesAll,
            'teachers'              => $teachersAll,
            'tags'                  => $tagsAll,
        ));
    }

    public function addAction(Request $request)
    {   
        $em = $this->getDoctrine()->getManager();
        $securityContext = $this->container->get('security.context');
        $user = $securityContext->getToken()->getUser();
        $teaching_id = $request->request->get('teaching_id');
        
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $teaching = $em->getRepository('EspaceMembersMainBundle:Teaching')->find($teaching_id);
        if ($teaching) {
            $user->addBookmark($teaching);
            $em->merge($user);
            $em->flush();
            $response = array("code" => 100, "success" => true);
        } else { $response = array("code" => 100, "success" => false); }

        return new Response(json_encode($response));
    }

    public function removeAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $securityContext = $this->container->get('security.context');
        $user = $securityContext->getToken()->getUser();
        $teaching_id = $request->request->get('teaching_id');
        
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $teaching = $em->getRepository('EspaceMembersMainBundle:Teaching')->find($teaching_id);
        if ($teaching) {
            $user->removeBookmark($teaching);
            $em->merge($user);
            $em->flush();
            $response = array("code" => 100, "success" => true);
        } else { $response = array("code" => 100, "success" => false); }

        return new Response(json_encode($response));
    }

    public function filteringCollectionById($collection, $id) {
        $collection->filter(
            function($element) use ($id) {
                return $element->getId() === $id;
            });
        return $collection->first();
    }
}
