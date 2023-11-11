<?php

namespace App\Utilities\Services;

use App\Utilities\Contracts\RedisHelperInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class RedisHelper implements RedisHelperInterface
{
    public function storeRecentMessage(mixed $id, string $messageSubject, string $toEmailAddress): void {
        if (!is_null($id)) {
            Cache::put($id, [ 'subject' => $messageSubject, 'toEmailAddress' => $toEmailAddress ]);
        }

        Log::info(RedisHelper::class . "Cache miss id {$id}");
    }
}
