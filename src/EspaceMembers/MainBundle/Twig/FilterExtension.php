<?php
namespace EspaceMembers\MainBundle\Twig;

class FilterExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('isBookmark', array($this, 'isBookmark')),
        );
    }

    public function isBookmark($bookmarksId, $teachingId)
    {
        if (empty($bookmarksId)) {
            return false;
        }

        $bookmarksId = array_filter($bookmarksId, function ($element) use ($teachingId) {
            return $element === $teachingId;
        });

        return count($bookmarksId) > 0;
    }


    public function getName()
    {
        return 'espace_members_filter_extension';
    }
}
