<?php

namespace App\Services;

use App\Models\Article;
use Symfony\Component\DomCrawler\Crawler;

class ParserService
{

    const READERS_CORNER = 'Readers’ Corner: ';

    /**
     * @throws \Exception
     */
    public function parse(string $html): ?Article
    {
        $crawler = new Crawler($html);
        if ($crawler->filter('.elementor-post__title > a')->count() == 0) {
            throw new \Exception('Title not found');
        }

        $articleTitle = trim($crawler->filter('.elementor-post__title > a')->text());
        // check duplicate title
        if (Article::where('title', 'like', '%' . $articleTitle . '%')->exists()) {
            throw new \Exception('Title already exists');
        }

        if ($crawler->filter('.elementor-post__excerpt > p')->count() == 0) {
            throw new \Exception('Body not found');
        }
        $articleBody = $crawler->filter('.elementor-post__excerpt > p')->text();
        $body = $this->removeWordCount($articleBody);
        $body = $this->removeEmailSignature($body);

        return Article::create([
            'title' => $this->addTitleCorner($articleTitle),
            'body' => $body,
        ]);
    }

    public function removeWordCount(string $body) : string
    {
        // Normalise the parenthesis format
        $body = str_replace(['（', '）'], ['(', ')'], $body);

        // Remove word count pattern with flexible spacing
        return preg_replace('/\s*\(\s*\d+\s+words?\s*\)/i', '', $body);
    }

    public function addTitleCorner(string $title) : string
    {
        // if title is starts with 'Readers’ Corner: ', return title
        if (str_starts_with($title, 'Reader')) {
            return $title;
        }

        // if today is Thursday, add 'Readers’ Corner: ' to the title
        if (today()->isThursday()) {
            return self::READERS_CORNER . $title;
        }

        return $title;
    }

    public function removeEmailSignature(string $body): string
    {
        // Split into sentences (handling multiple types of punctuation)
        $sentences = preg_split('/(?<=[.!?])\s+/', trim($body));

        // If we have at least one sentence and the last one contains the email
        if (count($sentences) > 0 && str_contains(end($sentences), 'jaremaga@gmail.com')) {
            // Remove the last sentence and join the rest
            array_pop($sentences);
        }

        // Join sentences back together with a space after each punctuation
        return implode(' ', $sentences);
    }
}
