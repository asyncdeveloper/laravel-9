<?php

namespace App\Utilities\Services;

use App\Utilities\Contracts\RedisHelperInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class RedisHelper implements RedisHelperInterface
{
    public function storeRecentMessage(mixed $id, string $messageSubject, string $toEmailAddress): void {
        if (is_null($id)) {
            Log::info(sprintf("%s CacheMiss 'messageSubject':%s 'toEmailAddress':%s", RedisHelper::class, $messageSubject, $toEmailAddress));
            return;
        }

        Cache::put($id, [ 'subject' => $messageSubject, 'toEmailAddress' => $toEmailAddress ]);;
    }
}
