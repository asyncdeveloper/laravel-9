<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendBulkEmails implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private array $emailsData;
    private User $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $emailsData, User $user)
    {
        $this->emailsData = $emailsData;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        foreach ($this->emailsData as $emailBody) {
            SendEmail::dispatch($emailBody, $this->user);
        }
    }
}
