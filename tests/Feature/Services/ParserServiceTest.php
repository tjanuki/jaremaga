<?php

use Carbon\Carbon;

it('parse title and body from a html', function () {

    $html = <<<'HTML'
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
HTML;

    $this->travelTo(Carbon::create(2024, 3, 31));
    $article = app(\App\Services\ParserService::class)->parse($html);

    // prettier-ignore
    expect($article)->not()->toBeNull()
        ->and($article->title)->toBe('some title')
        ->and($article->body)->toBe('some body here');
});

it('removes word count from body', function () {
    $body = 'Many children find the first day of school exciting but sometimes a little frightening. （116 words）';

    $body = app(\App\Services\ParserService::class)->removeWordCount($body);

    // prettier-ignore
    expect($body)->toBe('Many children find the first day of school exciting but sometimes a little frightening.');
});

it('removes word counts from body', function () {
    $body = 'Many children find the first day of school exciting but sometimes a little frightening. (120 words)';

    $body = app(\App\Services\ParserService::class)->removeWordCount($body);

    // prettier-ignore
    expect($body)->toBe('Many children find the first day of school exciting but sometimes a little frightening.');
});


it('adds Readers corner if the date is thursday', function () {

    $title = 'some title';

    $this->travelTo(Carbon::create(2024, 3, 28)); // Tuesday
    $title = app(\App\Services\ParserService::class)->addTitleCorner($title);

    // prettier-ignore
    expect($title)->toBe('Readers’ Corner: some title');
});

it('adds nothing if the title already starts with Readers corner', function () {
    $title = 'Readers’ Corner: some title';

    $this->travelTo(Carbon::create(2024, 3, 28)); // Tuesday
    $title = app(\App\Services\ParserService::class)->addTitleCorner($title);

    // prettier-ignore
    expect($title)->toBe('Readers’ Corner: some title');
});
