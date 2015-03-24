<?php
namespace EspaceMembers\MainBundle\Twig;

use Twig_Extension;
use Twig_Filter_Method;
use Twig_SimpleFunction;

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
            'filterIsBookmark' => new Twig_Filter_Method($this, 'filterIsBookmark'),
        );
    }

    public function getFunctions()
    {
        return array(
            'preg_match_result' => new Twig_SimpleFunction('preg_match_result', array($this, 'getPregMatchResult'))
        );
    }

    public function filterIsBookmark($bookmarkCollection, $teaching_id)
    {
        $bookmarkCollection = $bookmarkCollection->filter(
            function ($element) use ($teaching_id) {
                return $element->getId() === $teaching_id;
            });

        return $bookmarkCollection->isEmpty() ? false : true;
    }

    public function getPregMatchResult($pattern = '', $string = '')
    {
        preg_match($pattern, $string, $matches);

        return $matches;
    }

    public function getName()
    {
        return 'espace_members_extension';
    }
}
