<?php

namespace App\Console\Commands;

use App\Jobs\NewArticlePosted;
use App\Models\Article;
use Illuminate\Console\Command;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class ScrapeLatestArticle extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:scrape-latest-article';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape the latest article from jaremaga.online';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $todaysArticle = Article::whereDate('created_at', today())->first();
        if ($todaysArticle) {
            $this->info('Today\'s article has already been scraped!');
            return;
        }

        $url = 'https://jaremaga.online';
        $client = new Client();
        $response = $client->request('GET', $url);

        if ($response->getStatusCode() == 200) {
            $html = $response->getBody()->getContents();
            $crawler = new Crawler($html);
            $title = $crawler->filter('.elementor-post__title > a')->text();
            $body = $crawler->filter('.elementor-post__excerpt > p')->text();

            $this->info("Latest Article: $title");
            $this->info("Article Body: $body");

            $article = Article::create([
                'title' => $title,
                'body' => $body,
            ]);

            NewArticlePosted::dispatch($article);
        }

        $this->info('Scraping completed!');
    }
}
