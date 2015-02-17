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
}
