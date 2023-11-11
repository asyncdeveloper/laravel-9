<?php

namespace App\Utilities\Services;
use App\Utilities\Contracts\ElasticsearchHelperInterface;
use Elasticsearch\Client;
use Illuminate\Support\Facades\Log;
use PHPUnit\Framework\MockObject\Exception;

class ElasticsearchHelper implements ElasticsearchHelperInterface
{
    public function __construct(private Client $client)
    {
    }

    public function storeEmail(string $messageBody, string $messageSubject, string $toEmailAddress): mixed
    {
        try {
            $data = $this->client->index([
                'type' => $this->getType(),
                'index' => $this->getIndex(),
                'id' => uniqid(),
                'body' => [
                    'message' => $messageBody,
                    'subject' => $messageSubject,
                    'toEmailAddress' => $toEmailAddress,
                ],
            ]);

            return $data['_id'];
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return null;
        }
    }

    public function getStoredEmails(): array
    {
        try {
            $results = $this->client->search([
                'type' => $this->getType(),
                'index' => $this->getIndex(),
            ]);

            $emails = [];

            foreach ($results['hits']['hits'] as $item) {
                $emails[] = array_merge($item['_source'], [ 'id' => $item['_id']] );
            }

            return $emails;
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return [];
        }
    }

    private function getIndex(): string
    {
        return 'emails';
    }

    private function getType(): string
    {
        return 'array';
    }

}
