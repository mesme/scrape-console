BBC Home Page Scrape Console Application
========================

Using object oriented PHP, build a console application that scrapes the BBC news homepage (http://www.bbc.co.uk/news/) and returns a JSON array of the most popular
shared articles table

REQUIREMENTS:
----------------------------------

1. PHP5.4
2. php5-curl extension
3. allow_url_fopen


1) Installing the application
----------------------------------

    Git clone https://github.com/mesme/scrape-console.git

    ### Use Composer (*recommended*)

    If you don't have Composer yet, download it following the instructions on
    http://getcomposer.org/ or just run the following command:

    curl -s http://getcomposer.org/installer | php

    php composer.phar install

2. How to Run
----------------------------------
    1. Go to the root location of the application
    2. type: php app/console scrape

3. Unit Tests
----------------------------------

   1. Located at /src/BBC/ScrapeBundle/Tests
   2. To run unit test, Go to the root location of the application and run php bin/phpunit -c app
   3. PHPUnit xml config is located at app/phpunit.xml.dist

4. The service class that process the content is located at /src/BBC/ScrapeBundle/Service/Scrape.php which is injected
    from app/config/services.yml

    services:
        scrape_web_page:
            class: BBC\ScrapeBundle\Service\Scrape
            arguments: [@session]

5. Output Example
--------------------------------
    {
       "results":[
          {
             "title":"Mapped: The beaches where Lego washes up",
             "href":"http:\/\/www.bbc.co.uk\/news\/magazine-28582621",
             "size":"18.00kb",
             "most_used_word":"Lego",
             "most_used_word_count":28
          },
          {
             "title":"UK Ebola nurse Pauline Cafferkey &#039;in critical condition&#039;",
             "href":"http:\/\/www.bbc.co.uk\/news\/uk-30666265",
             "size":"24.52kb",
             "most_used_word":"Ebola",
             "most_used_word_count":34
          },
          {
             "title":"India arrests five for kidnap and rape of Japanese woman",
             "href":"http:\/\/www.bbc.co.uk\/news\/world-asia-india-30665581",
             "size":"17.76kb",
             "most_used_word":"India",
             "most_used_word_count":17
          },
          {
             "title":"Constipated goldfish operated on by North Walsham vet",
             "href":"http:\/\/www.bbc.co.uk\/news\/uk-england-norfolk-30655444",
             "size":"14.81kb",
             "most_used_word":"goldfish",
             "most_used_word_count":14
          },
          {
             "title":"Major search after ship overturns off north of Scotland",
             "href":"http:\/\/www.bbc.co.uk\/news\/uk-scotland-highlands-islands-30667084",
             "size":"15.55kb",
             "most_used_word":"ship",
             "most_used_word_count":11
          }
       ]
    }
