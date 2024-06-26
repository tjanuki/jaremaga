<?php

use Illuminate\Support\Facades\Queue;

beforeEach(function () {
    // mock HTML response
    $html = <<<'HTML'
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test</title>
</head>
<body>
<h4 class="elementor-heading-title elementor-size-default">Today's じゃれマガ Post</h4></div>
</div>
<div class="elementor-element elementor-element-373c481 elementor-grid-1 elementor-grid-tablet-1 elementor-posts__hover-none elementor-widget__width-inherit elementor-grid-mobile-1 elementor-posts--thumbnail-top elementor-widget elementor-widget-posts" data-id="373c481" data-element_type="widget" data-settings="{&quot;cards_columns&quot;:&quot;1&quot;,&quot;cards_columns_tablet&quot;:&quot;1&quot;,&quot;cards_columns_mobile&quot;:&quot;1&quot;,&quot;cards_row_gap&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:35,&quot;sizes&quot;:[]},&quot;cards_row_gap_tablet&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]},&quot;cards_row_gap_mobile&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]}}" data-widget_type="posts.cards">
<div class="elementor-widget-container">
<div class="elementor-posts-container elementor-posts elementor-posts--skin-cards elementor-grid">
<article class="elementor-post elementor-grid-item post-3551 post type-post status-publish format-standard has-post-thumbnail hentry category-readers-corner">
<div class="elementor-post__card">
<a class="elementor-post__thumbnail__link" href="https://jaremaga.online/readers-corner-families-wanted/" tabindex="-1" ><div class="elementor-post__thumbnail"><img decoding="async" width="768" height="455" src="https://i0.wp.com/jaremaga.online/wp-content/uploads/2024/03/FamiliesWanted.jpg?fit=768%2C455&amp;ssl=1" class="attachment-medium_large size-medium_large wp-image-3552" alt="" srcset="https://i0.wp.com/jaremaga.online/wp-content/uploads/2024/03/FamiliesWanted.jpg?w=1182&amp;ssl=1 1182w, https://i0.wp.com/jaremaga.online/wp-content/uploads/2024/03/FamiliesWanted.jpg?resize=300%2C178&amp;ssl=1 300w, https://i0.wp.com/jaremaga.online/wp-content/uploads/2024/03/FamiliesWanted.jpg?resize=1024%2C606&amp;ssl=1 1024w, https://i0.wp.com/jaremaga.online/wp-content/uploads/2024/03/FamiliesWanted.jpg?resize=768%2C455&amp;ssl=1 768w" sizes="(max-width: 768px) 100vw, 768px" /></div></a>
<div class="elementor-post__text">
<h3 class="elementor-post__title">
<a href="https://jaremaga.online/readers-corner-families-wanted/" >some title</a>
</h3>
<div class="elementor-post__excerpt">
<p>some body here</p>
</div>
</div>
<div class="elementor-post__meta-data">
<span class="elementor-post-date">
March 29, 2024</span>
<span class="elementor-post-avatar">
No Comments</span>
</div>
</div>
</article>
</div>
</div>
</body>
</html>
HTML;
    // mock the response
    $client = Mockery::mock(\GuzzleHttp\Client::class);
    $client->shouldReceive('request')->andReturn(new \GuzzleHttp\Psr7\Response(200, [], $html));
    $this->instance(\GuzzleHttp\Client::class, $client);

    \App\Models\Article::whereDate('created_at', today())->delete();
});
it('execute scrape latest article', function () {

    Illuminate\Support\Facades\Queue::fake();

    $this->artisan('app:scrape-latest-article')
        ->expectsOutput('Scraping completed!')
        ->assertExitCode(0);

    // assert that the job was dispatched
    Queue::assertPushed(\App\Jobs\NewArticlePosted::class);
});
