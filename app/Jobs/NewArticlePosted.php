<?php

namespace App\Jobs;

use App\Mail\ArticlePosted;
use App\Models\Article;
use App\Models\Subscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class NewArticlePosted implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public Article $article)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Subscriber::all()
            ->each(function (Subscriber $subscriber) {
                Mail::to($subscriber->email)
                    ->send(new ArticlePosted($this->article));
                logger()->info("Email sent to $subscriber->email");
            });
    }
}
