<?php

namespace App\Utilities\Services;
use App\Utilities\Contracts\ElasticsearchHelperInterface;
use Elasticsearch\Client;
use Illuminate\Support\Facades\Log;

class ElasticsearchHelper implements ElasticsearchHelperInterface
{
    const TYPE = 'emails';
    const INDEX = 'array';
    public function __construct(private Client $client)
    {
    }

    public function storeEmail(string $messageBody, string $messageSubject, string $toEmailAddress): mixed
    {
        try {
            $data = $this->client->index([
                'type' => self::TYPE,
                'index' => self::INDEX,
                'id' => uniqid(),
                'body' => [
                    'message' => $messageBody,
                    'subject' => $messageSubject,
                    'toEmailAddress' => $toEmailAddress,
                ],
            ]);

            return $data['_id'];
        } catch (\Exception $exception) {
            Log::error($exception->getMessage(), $exception->getTrace());
            return null;
        }
    }

    public function getStoredEmails(): array
    {
        try {
            $results = $this->client->search([
                'type' => self::TYPE,
                'index' => self::INDEX,
            ]);

            $emails = [];

            foreach ($results['hits']['hits'] as $item) {
                $emails[] = array_merge($item['_source'], [ 'id' => $item['_id']] );
            }

            return $emails;
        } catch (\Exception $exception) {
            Log::error($exception->getMessage(), $exception->getTrace());
            return [];
        }
    }

}
