<?php

namespace App\Utilities\Services;

use App\Utilities\Contracts\RedisHelperInterface;
use Illuminate\Support\Facades\Cache;

class RedisHelper implements RedisHelperInterface
{
    public function storeRecentMessage(mixed $id, string $messageSubject, string $toEmailAddress): void {
        Cache::put($id, [ 'subject' => $messageSubject, 'toEmailAddress' => $toEmailAddress ]);
    }
}
