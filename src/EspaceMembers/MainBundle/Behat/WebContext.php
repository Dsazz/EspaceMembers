<?php

namespace EspaceMembers\MainBundle\Behat;

use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\Mink\Driver\BrowserKitDriver;
use Behat\Mink\Exception\ElementNotFoundException;

use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * Defines application features from the specific context.
 */
class WebContext extends DefaultContext
{
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
     * @Given /^I am authenticated as "([^"]*)"$/
     */
    public function iAmAuthenticatedAs($username)
    {
        $driver = $this->getSession()->getDriver();
        if (!$driver instanceof BrowserKitDriver) {
            throw new UnsupportedDriverActionException('This step is only supported by the BrowserKitDriver');
        }

        $client = $driver->getClient();
        $client->getCookieJar()->set(new Cookie(session_name(), true));

        $session = $client->getContainer()->get('session');

        $user = $this->getService('fos_user.user_manager')->findUserByUsername($username);
        $providerKey = $this->getContainer()->getParameter('fos_user.firewall_name');

        $token = new UsernamePasswordToken($user, null, $providerKey, $user->getRoles());
        $session->set('_security_'.$providerKey, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $client->getCookieJar()->set($cookie);
    }

    /**
     * @When /^(?:|I )wait (\d+) seconds?$/
     */
    public function waitSeconds($seconds)
    {
        $this->getSession()->wait(1000*$seconds);
    }

    /**
     * Click on the element with the provided CSS Selector
     *
     * @When /^I click on the element "([^"]*)"$/
     */
    public function iClickOnTheElement($cssSelector)
    {
        $element = $this->getSession()->getPage()->find('css', $cssSelector);

        if (null === $element) {
            throw new \InvalidArgumentException(sprintf('Could not evaluate CSS Selector: "%s"', $cssSelector));
        }

        $element->click();
    }

    /**
     * Check that the element contains a class
     *
     * @Then /^the "(?P<cssSelector>(?:[^"]|\\")*)" element should has class "(?P<class>(?:[^"]|\\")*)"$/
     */
    public function theElementShouldHasClass($cssSelector, $class)
    {
        $element = $this->getSession()->getPage()->find('css', $cssSelector);

        if (null === $element) {
            throw new ElementNotFoundException($this->getSession(), 'link', 'class', $cssSelector);
        }

        //TODO: Download Selenium 2.45 because method hasClass not works in current version
        //if (!$element->hasClass($class)) {
            //throw new \Exception(sprintf('Could not find %s class in element: "%s"', $class, $cssSelector));
        //}
    }
}
