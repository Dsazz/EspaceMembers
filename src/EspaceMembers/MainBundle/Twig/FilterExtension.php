<?php
/**
 * This file is part of the EspaceMembers project.
 *
 * (c) Stanislav Stepanenko <dsazztazz@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EspaceMembers\MainBundle\Twig;

/**
 * FilterExtension
 *
 * @author Stepanenko Stanislav <dsazztazz@gmail.com>
 */
class FilterExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('isBookmark', [$this, 'isBookmark']),
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
