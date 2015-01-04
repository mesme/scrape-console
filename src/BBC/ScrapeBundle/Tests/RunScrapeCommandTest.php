<?php


namespace BBC\ScrapeBundle\Tests;

use BBC\ScrapeBundle\Command\RunScrapeCommand;
use MyProject\Proxies\__CG__\OtherProject\Proxies\__CG__\stdClass;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class RunScrapeCommandTest extends KernelTestCase
{
    public function testExecute()
    {
        $kernel = $this->createKernel();
        $kernel->boot();

        $application = new Application($kernel);
        $application->add(new RunScrapeCommand());

        $command = $application->find('scrape');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array('command' => $command->getName()));

        $array = json_decode($commandTester->getDisplay());

        // check if json response has "results" object
        $this->assertObjectHasAttribute('results', $array);

        // check if there are any articles in the response
        $this->assertGreaterThanOrEqual(1,sizeof($array->results));

        // check if json response has title, href, size, most_used_word & most_used_word_count object for each article
        foreach($array->results as $value)
        {
            $this->assertObjectHasAttribute('title', $value);
            $this->assertObjectHasAttribute('href', $value);
            $this->assertObjectHasAttribute('size', $value);
            $this->assertObjectHasAttribute('most_used_word', $value);
            $this->assertObjectHasAttribute('most_used_word_count', $value);
        }
    }
} 