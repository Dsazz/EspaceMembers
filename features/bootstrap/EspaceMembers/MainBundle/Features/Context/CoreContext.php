<?php

namespace EspaceMembers\MainBundle\Features\Context;

use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Exception\PendingException;
use Behat\CommonContexts\DoctrineFixturesContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\MinkContext;
use Behat\Symfony2Extension\Context\KernelDictionary;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class CoreContext extends MinkContext
{
    use KernelDictionary;

    private $kernel;

    /**
     * Sets the context kernel
     *
     * @param KernelInterface $kernel
     */
    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * BeforeFeature hook
     *
     * @BeforeFeature
     */
    public static function beforeFeature()
    {
        $process = new Process('php app/console doctrine:database:drop --env=test --force && php app/console doctrine:database:create --env=test && php app/console doctrine:schema:drop --env=test --force && php app/console doctrine:schema:update --env=test --force && php app/console doctrine:fixtures:load --env=test && php app/console cache:clear --env=test');

        try {
            $process->mustRun();

            echo $process->getOutput();
        } catch (ProcessFailedException $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @Given /^I am in a directory "([^"]*)"$/
     */
    public function iAmInADirectory($dir)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        if (!file_exists($dir)) {
            mkdir($dir);
        }
        chdir($dir);
    }

    /**
     * @Given /^table "([^"]*)" is empty$/
     */
    public function tableIsEmpty($table)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $em->createQuery(sprintf('DELETE EspaceMembersMainBundle:%s', $table))->execute();
        $em->flush();
    }

    /**
     * Drop entered tables
     *
     * @Given /^(?:|I )drop tables:$/
     */
    public function iDropTables(TableNode $tables)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        foreach ($tables->getHash() as $table) {
            $em->createQuery(sprintf('DELETE EspaceMembersMainBundle:%s', $table['name']))->execute();
        }

        $em->flush();
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
      //print_r(get_class_methods($this->getSession()));
      $headers = $this->getSession()->getResponseHeaders();
      echo "I am Printing all response headers here  \n";
      print_r($headers);
      $content_type =  $headers['content-type'];
      assert($content_type == "application/pdf");
    }
}
