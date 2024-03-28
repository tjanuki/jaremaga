<?php

namespace App\Console\Commands;

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
        $url = 'https://jaremaga.online';
        $client = new Client();
        $response = $client->request('GET', $url);

        if ($response->getStatusCode() == 200) {
            $html = $response->getBody()->getContents();
            dd($html);
            $crawler = new Crawler($html);
            dd($crawler);
            $articleTitle = $crawler->filter('.elementor-post__title')->text();
            $articleBody = $crawler->filter('.elementor-post__content > p')->text();

            $this->info("Latest Article: $articleTitle");
            $this->info("Article Body: $articleBody");
            // You can further process the data, such as saving it to the database
        }
    }
}
