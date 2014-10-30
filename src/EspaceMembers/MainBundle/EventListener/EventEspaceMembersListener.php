<?php
namespace EspaceMembers\MainBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use EspaceMembers\MainBundle\Entity\Group;

class EventEspaceMembersListener {
    //public function prePersist(LifecycleEventArgs $args) {
        //$entity = $args->getEntity();
        //$em = $args->getEntityManager();
 
        //if ($entity instanceof \EspaceMembers\MainBundle\Entity\Event) {
            //$membersGroup = $em->getRepository('EspaceMembersMainBundle:Group')
                //->findOneBy(
                    //array('name' => 'Members')
                //); 

            //if (!$membersGroup) {
                //$membersGroup = new Group();
                //$membersGroup->setName('Members');
                //$membersGroup->addEvent($entity);
            //} else {
                //$membersGroup->addEvent($entity);
            //}
        //}
    //}
}
