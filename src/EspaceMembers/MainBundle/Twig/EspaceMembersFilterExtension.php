<?php
namespace EspaceMembers\MainBundle\Twig;

use Doctrine\Common\Collections\Criteria;
use Twig_Extension;
use Twig_Filter_Method;

class EspaceMembersFilterExtension extends Twig_Extension
{
    protected $em;

    public function __construct(\Doctrine\ORM\EntityManager $em)
    {
        $this->em = $em;
    }

    public function getFilters()
    {
        return array(
            'filterLesson' => new Twig_Filter_Method($this, 'filterLesson'),
            'filterIsBookmark' => new Twig_Filter_Method($this, 'filterIsBookmark'),
        );
    }

    public function filterLesson($entityCollection, $filter_id)
    {
        $entityCollection = $entityCollection->filter(
            function($element) use ($filter_id) {
                return $element->getEvent()->getTitle() === $filter_id && $element->getIsShow();
            });

        return $entityCollection;
    }

    public function filterIsBookmark($bookmarkCollection, $teaching_id)
    {
        $bookmarkCollection = $bookmarkCollection->filter(
            function($element) use ($teaching_id) {
                return $element->getId() === $teaching_id;
            });


        return $bookmarkCollection->isEmpty() ? false : true;
    }

    public function getName()
    {
        return 'espace_members_extension';
    }
}
