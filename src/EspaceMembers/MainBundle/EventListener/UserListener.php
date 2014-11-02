<?php
namespace EspaceMembers\MainBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use EspaceMembers\MainBundle\Entity\UserGroup;

class UserListener
{
    //public function prePersist(LifecycleEventArgs $args) {
        //$entity = $args->getEntity();
        //$em = $args->getEntityManager();

        //if ($entity instanceof \EspaceMembers\MainBundle\Entity\User) {
            //$membersGroup = $em->getRepository('EspaceMembersMainBundle:UserGroup')
                //->findOneBy(
                    //array('title' => 'Members')
                //);

            //if (!$membersGroup) {
                //$membersGroup = new UserGroup();
                //$membersGroup->setTitle('Members');
                //$membersGroup->addUser($entity);
            //} else {
                //$membersGroup->addUser($entity);
            //}
        //}
    //}
}
