<?php

use App\Models\Article;

it('sends article posted email', function () {
    $article = Article::factory()->create();

    $mailable = new \App\Mail\ArticlePosted($article);

    // prettier-ignore
    expect($mailable->envelope()->subject)->toBe('Jareamaga: "'.$article->title.'"')
        ->and($mailable->content()->view)->toBe('view.emails.article-posted')
        ->and($mailable->attachments())->toBeArray();
});
