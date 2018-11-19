<?php

namespace App\ContactCrawler;

use App\ContactCrawler\interfaces\ISearch;
use DOMDocument;
use DOMXPath;

require_once 'interfaces/ISearch.php';
require_once 'implementation/Search.php';

/**
 * Description of Google
 *
 * @author jpuchky
 */
class Google extends Search implements ISearch {

    private $url = 'https://www.google.com/search?q=';

    public function search($keyword, $language, $num, $from = 0) {
        libxml_use_internal_errors(true);
        $urls = [];
        $data = $this->getData($this->url . urlencode($keyword) . "&num=$num&hl=$language&start=$from");
        $dom = new DOMDocument();
        @$dom->loadHTML($data);
        libxml_clear_errors();
        $xp = new DOMXPath($dom);
        $results = $xp->query('//*/div[@class="r"]');
        foreach ($results as $r) {
            $as = $r->getElementsByTagName('a');
            foreach ($as as $a) {
                if (!strstr($a->getAttribute('href'), '://webcache.googleusercontent.com/') && !strstr($a->getAttribute('href'), '://translate.google.com/translate') && !strstr($a->getAttribute('href'), "/search?hl=$language&q=related") && $a->getAttribute('href') !== "#") {
                    $urls[$a->getAttribute('href')]=$a->getAttribute('href');
                }
            }
        }
        return $urls;
    }

}
