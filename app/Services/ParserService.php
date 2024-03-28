<?php

namespace App\Services;

use App\Models\Article;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class ParserService
{
    public function parse(string $html) : string
    {
        $crawler = new Crawler($html);
        $articleTitle = $crawler->filter('.elementor-post__title > a')->text();
        $articleBody = $crawler->filter('.elementor-post__excerpt > p')->text();

        dd($articleTitle, $articleBody);
    }
}
