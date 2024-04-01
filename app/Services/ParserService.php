<?php

namespace App\Services;

use App\Models\Article;
use Symfony\Component\DomCrawler\Crawler;

class ParserService
{
    public function parse(string $html): ?Article
    {
        $crawler = new Crawler($html);
        if ($crawler->filter('.elementor-post__title > a')->count() == 0) {
            throw new \Exception('Title not found');
        }
        $articleTitle = $crawler->filter('.elementor-post__title > a')->text();

        if ($crawler->filter('.elementor-post__excerpt > p')->count() == 0) {
            throw new \Exception('Body not found');
        }
        $articleBody = $crawler->filter('.elementor-post__excerpt > p')->text();

        return Article::create([
            'title' => $articleTitle,
            'body' => $articleBody,
        ]);
    }
}
