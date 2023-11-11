<?php

namespace App\Jobs;

use App\Mail\CustomEmail;
use App\Models\User;
use App\Utilities\Contracts\ElasticsearchHelperInterface;
use App\Utilities\Contracts\RedisHelperInterface;
use App\Utilities\Services\ElasticsearchHelper;
use App\Utilities\Services\RedisHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private array $emailData;
    private User $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $emailData, User $user)
    {
        $this->emailData = $emailData;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws BindingResolutionException
     */
    public function handle(): void
    {
        Mail::to($this->emailData['email'])->queue(new CustomEmail($this->emailData, $this->user));

        /** @var ElasticsearchHelper $elasticsearchHelper */
        $id = app()
                ->make(ElasticsearchHelperInterface::class)
                ->storeEmail($this->emailData['body'], $this->emailData['subject'], $this->emailData['email']);

        /** @var RedisHelper $redisHelper */
        app()->make(RedisHelperInterface::class)
            ->storeRecentMessage($id, $this->emailData['subject'], $this->emailData['email']);
    }
}
