<?php

namespace EspaceMembers\MainBundle\Behat;

use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

use Behat\Mink\Exception\UnsupportedDriverActionException;
use Behat\Mink\Driver\BrowserKitDriver;

class ControllerContext extends DefaultContext
{
    /**
     * BeforeFeature hook
     *
     * @BeforeFeature
     */
    public static function beforeFeature()
    {
        //$process = new Process('php app/console doctrine:database:drop --env=test --force && php app/console doctrine:database:create --env=test && php app/console doctrine:schema:drop --env=test --force && php app/console doctrine:schema:update --env=test --force && php app/console doctrine:fixtures:load --env=test && php app/console cache:clear --env=test');
        //$process->setTimeout(3600);
        //$process->mustRun();

        //echo $process->getOutput();
    }

    /**
     * @param \Behat\Behat\Event\ScenarioEvent|\Behat\Behat\Event\OutlineExampleEvent $event
     *
     * @return void
     *
     * @AfterScenario
     */
    public function afterScenario($event)
    {
        if ($this->getSession()->getDriver() instanceof BrowserKitDriver) {
            $this->getClient()->followRedirects(true);
        }
    }

    /**
     * @Given /^table "([^"]*)" is empty$/
     */
    public function tableIsEmpty($table)
    {
        $manager = $this->getEntityManager();

        $manager->createQuery(sprintf('DELETE EspaceMembersMainBundle:%s', $table))->execute();
        $manager->flush();
    }

    /**
     * Drop entered tables
     *
     * @Given /^(?:|I )drop tables:$/
     */
    public function iDropTables(TableNode $tables)
    {
        $manager = $this->getEntityManager();

        foreach ($tables->getHash() as $table) {
            $manager->createQuery(sprintf('DELETE EspaceMembersMainBundle:%s', $table['name']))->execute();
        }

        $manager->flush();
    }

    /*
     * @Given /^ответ должен быть "([^"]*)" с телом:$/
     */
    public function responseShouldBeJsonBody($statusCode, PyStringNode $body)
    {
        $response = $this->response;

        if (null === $response) {
            throw new \LogicException('No request was made');
        }

        if ((int) $statusCode !== $response->getStatusCode()) {
            throw new \LogicException(sprintf('Expected %d status code but %d received', $statusCode, $response->getStatusCode()));
        }

        $body = $this->twig->render($body->__toString(), $this->getData());
        if (!$this->matcher->match($response->getBody(), $body)) {
            throw new \LogicException($this->matcher->getError());
        }
    }

    /*
     * @When /^я делаю (GET|DELETE) запрос "([^"]*)"$/
     */
    public function iRequest($method, $url)
    {
        $data = $this->getData();

        $this->requestUrl = $this->getUrl($url, $data);

        switch ($method) {
            case 'GET':
                $response = $this->client->get($this->requestUrl);
                break;

            case 'DELETE':
                $response = $this->client->delete($this->requestUrl);
                break;

            default:
                throw new \LogicException("Unknow request method: " . $method);
                break;
        }

        $this->response = $response;
    }

    /**
     * Prevent following redirects.
     *
     * @return  void
     *
     * @When /^I do not follow redirects$/
     */
    public function iDoNotFollowRedirects()
    {
        $this->getClient()->followRedirects(false);
    }

    /**
     * Follow redirect instructions.
     *
     * @param   string  $page
     *
     * @return  void
     *
     * @Then /^I (?:am|should be) redirected(?: to "([^"]*)")?$/
     */
    public function iAmRedirected($page = null)
    {
        $headers = $this->getSession()->getResponseHeaders();
        if (empty($headers['Location']) && empty($headers['location'])) {
            throw new \RuntimeException('The response should contain a "Location" header');
        }
        if (null !== $page) {
            $header = empty($headers['Location']) ? $headers['location'] : $headers['Location'];
            if (is_array($header)) {
                $header = current($header);
            }
            \PHPUnit_Framework_Assert::assertEquals($header, $this->locatePath($page), 'The "Location" header points to the correct URI');
        }
        $client = $this->getClient();
        $client->followRedirects(true);
        $client->followRedirect();
    }

}
