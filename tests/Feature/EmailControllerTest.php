<?php

namespace Tests\Feature;

use App\Jobs\SendBulkEmails;
use App\Models\User;
use App\Utilities\Contracts\ElasticsearchHelperInterface;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Queue;
use Mockery;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class EmailControllerTest extends TestCase
{
    use WithFaker, DatabaseMigrations;

    private string $apiToken;


    protected function setUp(): void
    {
        parent::setUp();

        $this->apiToken = config('app.api_token');
    }

    /**
     * @test
     */
    public function sendEndpointShouldQueueSendBulkEmailsJobOnValidRequestBody()
    {
        Queue::fake();

        $user = User::factory()->create();

        $response = $this->postJson("/api/{$user->id}/send?api_token={$this->apiToken}", [
            'data' => [
                [
                    'subject' => 'Email Subject',
                    'body' => $this->faker->sentence(),
                    'email' => $this->faker->safeEmail(),

                ],
                [
                    'subject' => 'Second Email Subject',
                    'body' => $this->faker->sentence(),
                    'email' => $this->faker->safeEmail(),
                ],
            ],
        ]);

        $response->assertStatus(Response::HTTP_ACCEPTED)
            ->assertJsonFragment([ "message" => "Processing 2 email(s)" ]);

        Queue::assertPushed(SendBulkEmails::class);
    }

    /**
     * @test
     */
    public function sendEndpointShouldNotQueueSendBulkEmailsJobOnInValidRequestBody()
    {
        Queue::fake();
        Queue::assertNothingPushed();

        $user = User::factory()->create();

        $response = $this->postJson("/api/{$user->id}/send?api_token={$this->apiToken}", [
            'data' => [
                [
                    'body' => $this->faker->sentence(),
                    'email' => $this->faker->safeEmail(),

                ],
                [
                    'subject' => 'Second Email Subject',
                    'body' => $this->faker->sentence(),
                    'email' => $this->faker->safeEmail(),
                ],
            ],
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @test
     */
    public function listEndpointShouldReturnStoredEmailsInElasticSearch()
    {
        $elasticSearchHelperMock = Mockery::mock(ElasticsearchHelperInterface::class);
        $elasticSearchHelperMock
            ->shouldReceive('getStoredEmails')
            ->once()
            ->andReturn($this->getStoredEmailsData());

        $this->app->instance(ElasticsearchHelperInterface::class, $elasticSearchHelperMock);

        $response = $this->getJson("/api/list");

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                "message" => "Sent Emails Retrieved",
                "data" => $this->getStoredEmailsData()
            ]);
    }

    public function tearDown(): void
    {
        Mockery::close();
    }

    private function getStoredEmailsData(): array
    {
        return [
            [
                "message" => "Hello World",
                "subject" => "Email Subject",
                "toEmailAddress" => "me@example.com",
                "id" => "654ff2832f69e",
            ],
            [
                "message" => "Boring Email :(",
                "subject" => "Click Me",
                "toEmailAddress" => "olu@gmail.com",
                "id" => "654ff2832f68e",
            ]
        ];
    }

}
