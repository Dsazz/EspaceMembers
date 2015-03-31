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
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

/**
 * ControllerContext
 *
 * @author Stepanenko Stanislav <dsazztazz@gmail.com>
 */
class ControllerContext extends DefaultContext
{
    /**
     * @param \Behat\Behat\Event\ScenarioEvent|\Behat\Behat\Event\OutlineExampleEvent $event - event
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
     * Drop database process
     *
     * @return string - output of the work process
     */
    public function processDropDatabase()
    {
        $process = new Process(
            'php app/console doctrine:database:drop --env=test --force'
        );

        $process->run();

        return $process->getOutput();
    }

    /**
     * Create database and schema tables process
     *
     * @return string - output of the work process
     */
    public function processCreateDbAndSchemaTables()
    {
        $process = new Process(
            'php app/console doctrine:database:create --env=test && php app/console doctrine:schema:update --env=test --force'
        );

        $process->run();

        return $process->getOutput();
    }

    /**
     * Clear apc cache
     *
     * @return void
     */
    public function processApcCacheClear()
    {
        apc_clear_cache();
        apc_clear_cache('user');
        apc_clear_cache('opcode');
    }

    /**
     * Clear all tables.
     *
     * @return void
     *
     * @Given /^all tables is empty$/
     */
    public function allTableIsEmpty()
    {
        echo $this->processDropDatabase();
        echo $this->processCreateDbAndSchemaTables();
        echo $this->processApcCacheClear();
    }

    /**
     * Clear entered tables.
     *
     * @param Behat\Gherkin\Node\TableNode $tables - a list of names for tables
     *
     * @return void
     *
     * @Given /^following tables is empty:$/
     */
    public function followingTablesIsEmpty(TableNode $tables)
    {
        $manager = $this->getEntityManager();

        foreach ($tables->getHash() as $table) {
            $manager->createQuery(sprintf('DELETE EspaceMembersMainBundle:%s', $table['name']))->execute();
        }

        $manager->flush();
    }

    /**
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
     * @return void
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
     * @param string $page - page for redirection
     *
     * @return void
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
