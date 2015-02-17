<?php

namespace EspaceMembers\MainBundle\Features\Context;

use Behat\Behat\Context\BehatContext;
use Behat\Behat\Context\ClosuredContextInterface;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Context\TranslatedContextInterface;
use Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\MinkContext;

/**
 * Defines application features from the specific context.
 */
class WebContext extends MinkContext
{
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    /**
     * @Given /There is no "([^"]*)" in database/
     */
    public function thereIsNoRecordInDatabase($entityName)
    {
        $entities = $this->getEntityManager()
            ->getRepository('EspaceMembersMainBundle:'.$entityName)->findAll();
        foreach ($entities as $eachEntity) {
            $this->getEntityManager()->remove($eachEntity);
        }

        $this->getEntityManager()->flush();
    }

    /**
     * @Given /^I am logged in as "([^"]*)" with password "([^"]*)"$/
     */
    public function iAmLoggedInAsWithPassword($username, $password)
    {
        $this->visit('/login');
        $this->fillField('username', $username);
        $this->fillField('password', $password);
        $this->pressButton('Login');
    }
}
