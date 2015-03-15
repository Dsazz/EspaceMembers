<?php

namespace EspaceMembers\MainBundle\Behat;

use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

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
