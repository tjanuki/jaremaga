<?php

use App\Jobs\NewArticlePosted;
use App\Mail\ArticlePosted;
use App\Models\Article;
use App\Models\Subscriber;
use Illuminate\Support\Facades\Mail;

beforeEach(function () {
    Mail::fake();
    Subscriber::all()->each->delete();
});

it('sends emails to subscribers', function () {
    Mail::fake();
    $article = Article::factory()->create();
    $subscriber1 = Subscriber::factory()->create();
    $subscriber2 = Subscriber::factory()->create();

    NewArticlePosted::dispatch($article);

    Mail::assertSent(ArticlePosted::class, 2);
    Mail::assertSent(ArticlePosted::class, function ($mail) use ($article, $subscriber1) {
        return $mail->hasTo($subscriber1->email) && $mail->article->is($article);
    });
    Mail::assertSent(ArticlePosted::class, function ($mail) use ($article, $subscriber2) {
        return $mail->hasTo($subscriber2->email) && $mail->article->is($article);
    });
});
