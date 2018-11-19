<?php

require_once 'implementation/Google.php';

use App\ContactCrawler\Google;

function collectEmail($url) {
    $s = new \App\ContactCrawler\Search();
    $data = $s->getData($url);
    libxml_use_internal_errors(true);
    $emails = [];
    $dom = new DOMDocument();
    @$dom->loadHTML($data);
    libxml_clear_errors();
    $results = $dom->getElementsByTagName('a');
    foreach ($results as $r) {
        if (strstr($r->getAttribute('href'), 'mailto:')) {
            $emails[] = str_replace("mailto:", "", $r->getAttribute('href'));
        }
    }
    return $emails;
}

function collectUrls($url) {
    $s = new \App\ContactCrawler\Search();
    $data = $s->getData($url);
    // subpages
    libxml_use_internal_errors(true);
    $urls = [];
    $dom = new DOMDocument();
    @$dom->loadHTML($data);
    libxml_clear_errors();
    $results = $dom->getElementsByTagName('a');
    foreach ($results as $r) {
        $urls[$r->getAttribute('href')] = $r->getAttribute('href');
    }
    return $urls;
}

if (isset($argv[1])) {
    $google = new Google();
    $urls = $google->search($argv[1], "cs", 10000);
    $emails = [];
    foreach ($urls as $url) {
        $pages = collectUrls($url);
        foreach ($pages as $page) {
            $ems = collectEmail($page);
            foreach ($ems as $email) {
                echo $email . "\n";
                $emails[$email] = $email;
            }
        }
    }
    foreach ($emails as $email) {
        echo "$email\n";
    }
} else {
    echo "Help: <keyword>\n";
}