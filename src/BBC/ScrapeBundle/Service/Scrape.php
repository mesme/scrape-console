<?php

namespace BBC\ScrapeBundle\Service;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Sunra\PhpSimple\HtmlDomParser;

class Scrape {

    public function __construct(\Symfony\Component\HttpFoundation\Session\Session $session)
    {
        $this->session  = $session;
    }

    /**
     * @var array
     */
    private $_page_contents;

    /**
     * Set content
     *
     * @param string $content
     * @return content
     */
    public function setContent($content)
    {
        $this->_page_contents = $content;

        return $this;
    }

    /**
     * get content
     *
     * @return content
     */
    public function getContent()
    {
        return $this->_page_contents;
    }

    /**
     * @var string
     */
    private $_start;

    /**
     * Set start
     *
     * @param string $start
     * @return _start
     */
    public function setStart($start)
    {
        $this->_start = $start;

        return $this;
    }

    /**
     * get start
     *
     * @return start
     */
    public function getStart()
    {
        return $this->_start;
    }


    /**
     * @var string
     */
    private $_end;

    /**
     * Set end
     *
     * @param string $end
     * @return _end
     */
    public function setEnd($end)
    {
        $this->_end = $end;

        return $this;
    }

    /**
     * get end
     *
     * @return end
     */
    public function getEnd()
    {
        return $this->_end;
    }

    /**
     * @var string
     */
    private $_link;

    /**
     * Set link
     *
     * @param string $link
     * @return _link
     */
    public function setLink($link)
    {
        $this->_link = $link;

        return $this;
    }

    /**
     * Get link
     *
     * @return string
     */
    public function getLink()
    {
        return $this->_link;
    }

    /**
     * curl
     * Defining the basic cURL function
     */
    public function curl()
    {
        $ch     = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->getLink());
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $data   = curl_exec($ch);
        if(curl_errno($ch))
        {
            throw new \Exception('error:' . curl_error($ch));
        }
        curl_close($ch);
        return $data;
    }

    /**
     * getDivSection
     * To get content between a HTML tag
     */

    public function getDivSection()
    {
        $data = stristr(
                $this->getContent(), $this->getStart()
                );
        $data = substr(
                $data, strlen($this->getStart())
                );
        $stop = stripos(
                $data, $this->getEnd()
                );
        $data = substr($data, 0, $stop);
        return $data;
    }

    /**
     * getAnchors
     * To get links within a given content
     * @param string input
     * @return array href
     */

    public function getAnchors($input)
    {
        $href   = array();
        $regexp = "<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>";

        if(preg_match_all("/$regexp/siU", $input, $matches, PREG_SET_ORDER))
        {
            foreach($matches as $match)
            {
                $href[] = $match[2];
            }
        }

        return $href;
    }

    /**
     * mostCommonWord
     * To get most used numbers of words and size of the content
     * @param string url
     * @param array filter
     * @return array
     */

    public function mostCommonWord($url, $filter)
    {
        // used Sunra\PhpSimple\HtmlDomParser which uses simple php dom parser to read plaintext from html
        $page       = HtmlDomParser::file_get_html( $url )->plaintext;
        $words      = str_word_count($page, 1);

        $frequency  = array_count_values(array_udiff($words, $filter,'strcasecmp'));
        arsort($frequency);

        return array(
            'word'  => current(array_keys($frequency)),
            'count' => array_values($frequency)[0],
            'size'  => number_format(strlen($page)/1024,2)."kb"
        );

    }
}
?>