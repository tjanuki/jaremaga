<?php

namespace App\Services;

use App\Models\Article;
use Symfony\Component\DomCrawler\Crawler;

class ParserService
{

    const READERS_CORNER = 'Readers’ Corner: ';

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
            'title' => $this->addTitleCorner($articleTitle),
            'body' => $this->removeWordCount($articleBody),
        ]);
    }

    public function removeWordCount(string $body) : string
    {
        return preg_replace('/\s*（\d+ words）/', '', $body);
    }

    public function addTitleCorner(string $title) : string
    {
        // if title is starts with 'Readers’ Corner: ', return title
        if (str_starts_with($title, self::READERS_CORNER)) {
            return $title;
        }

        // if today is Thursday, add 'Readers’ Corner: ' to the title
        if (today()->isThursday()) {
            return self::READERS_CORNER . $title;
        }

        return $title;
    }
}
