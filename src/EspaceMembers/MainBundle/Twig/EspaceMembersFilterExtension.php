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

    public function filterIsBookmark($bookmarksId, $teachingId)
    {
        if (empty($bookmarksId)) {
            return false;
        }

        $bookmarksId = array_filter($bookmarksId, function ($element) use ($teachingId) {
            return $element === $teachingId;
        });

        return count($bookmarksId) > 0;
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
