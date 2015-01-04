<?php

namespace BBC\ScrapeBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class RunScrapeCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('scrape')
            ->setDescription('Command to scrape the BBC news homepage (http://www.bbc.co.uk/news/) and returns a JSON array of the most popular shared articles table')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container          = $this->getContainer();
        //load the Scrape.php service class
        $scrape             = $container->get('scrape_web_page');

        //set home page to scraped
        $scrape->setLink('http://www.bbc.co.uk/news/');
        $page               = $scrape->curl();

        //set the content of the home page
        $scrape->setContent($page);
        //set the delimiters to get inner html content of most popular shared articles
        $scrape->setStart('<div class="panel open">');
        $scrape->setEnd('</div>');
        $section            = $scrape->getDivSection();

        //get href of the articles
        $anchors            = $scrape->getAnchors($section);
        $results['results'] = array();

        //loop through articles and follow each link and title, size, most commonly used words the linked content
        if(!empty($anchors))
        {
            foreach($anchors as $href)
            {
                $scrape->setLink($href);
                $page  = $scrape->curl();
                $scrape->setContent($page);

                //filter certain words (this can be improved by using an abstract)
                $usage = $scrape->mostCommonWord(
                    $href, array("the", "a", "is", "and", "or", "i" , "to", "in" , "of" , "quot","bbc", "on")
                );
                //get title of the article
                $scrape->setStart('<h1 class="story-header">');
                $scrape->setEnd('</h1>');
                $section = $scrape->getDivSection();
                $results['results'][] = array(
                    'title'                 => $section,
                    'href'                  => $href,
                    'size'                  => $usage['size'],
                    'most_used_word'        => $usage['word'],
                    'most_used_word_count'  => $usage['count']
                );
            }
        }
        //return the results in json format
        $json = json_encode($results);

        //output the results to the console
        $output->writeln($json);
    }


} 