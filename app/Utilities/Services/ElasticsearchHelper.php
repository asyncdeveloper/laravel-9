<?php

namespace App\Utilities\Services;
use App\Utilities\Contracts\ElasticsearchHelperInterface;
use Elasticsearch\Client;

class ElasticsearchHelper implements ElasticsearchHelperInterface
{
    public function __construct(private Client $client)
    {
    }

    public function storeEmail(string $messageBody, string $messageSubject, string $toEmailAddress): mixed
    {
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
    }

    public function getStoredEmails(): array
    {
        $results = $this->client->search([
            'type' => $this->getType(),
            'index' => $this->getIndex(),
        ]);

        $emails = [];

        foreach ($results['hits']['hits'] as $item) {
            $emails[] = array_merge($item['_source'], [ 'id' => $item['_id']] );
        }

        return $emails;
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
