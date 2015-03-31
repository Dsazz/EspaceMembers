<?php
/**
 * This file is part of the EspaceMembers project.
 *
 * (c) Stanislav Stepanenko <dsazztazz@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EspaceMembers\MainBundle\Behat;

use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

/**
 * CommunityContext
 *
 * @author Stepanenko Stanislav <dsazztazz@gmail.com>
 */
class CommunityContext extends DefaultContext
{
    /**
     * Checks, that element with specified CSS contains specified text.
     *
     * @Then /^(?:|I )should see the correct number teachers in the "(?P<element>[^"]*)" element$/
     */
    public function assertElementContainsTeachers($element)
    {
        $em = $this->getService('doctrine.orm.entity_manager');
        $query = $em->createQuery('SELECT COUNT(u.id) FROM EspaceMembersMainBundle:User u WHERE u.is_teacher=1');
        $count = $query->getSingleScalarResult();

        $this->assertSession()->elementsCount('css', $element, intval($count));
    }
}
