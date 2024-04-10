<?php

namespace App\Console\Commands;

use App\Jobs\NewArticlePosted;
use App\Models\Article;
use App\Services\ParserService;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

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
    public function handle(ParserService $parserService): void
    {
        $latestArticle = Article::whereDate('created_at', today())->first();
        if ($latestArticle) {
            return;
        }

        $url = 'https://jaremaga.online';
        $client = new Client();
        $response = $client->request('GET', $url);

        if ($response->getStatusCode() == 200) {
            $html = $response->getBody()->getContents();

            $article = $parserService->parse($html);
            if (! $article) {
                logger()->error('Failed to parse the article');
                $this->error('Failed to parse the article');

                return;
            }

            NewArticlePosted::dispatch($article);
        }

        logger()->info('Scraping completed!');
        $this->info('Scraping completed!');
    }
}
