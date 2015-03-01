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

require_once 'PHPUnit/Autoload.php';
require_once 'PHPUnit/Framework/Assert/Functions.php';

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

    /**
     * @Given /^I request Google guide for PDF$/
     */
    public function iRequestGoogleGuideForPdf()
    {
        $this->visit("http://www.googleguide.com/print/adv_op_ref.pdf");
    }

    /**
     * @Then /^I should see response headers with content type PDF$/
     */
    public function iShouldSeeResponseHeadersWithContentTypePdf()
    {
      $headers = $this->getSession()->getResponseHeaders();
      echo "I am Printing all response headers here  \n";
      print_r($headers['Content-Type']);
      assertEquals($headers['Content-Type'], array('application/pdf'));
    }
}
