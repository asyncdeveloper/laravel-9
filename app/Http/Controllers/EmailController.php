<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendEmailRequest;
use App\Jobs\SendBulkEmails;
use App\Models\User;
use App\Traits\ApiResponse;
use App\Utilities\Contracts\ElasticsearchHelperInterface;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class EmailController extends Controller
{
    use ApiResponse;

    /**
     * @throws BindingResolutionException
     */
    public function __construct(private ElasticsearchHelperInterface $elasticsearchHelper)
    {
        /** @var ElasticsearchHelper $elasticsearchHelper */
        $this->elasticsearchHelper = app()->make(ElasticsearchHelperInterface::class);
    }

    public function send(User $user, SendEmailRequest $request): JsonResponse
    {
        $body = $request->validated();

        $emailsData = $body['data'];

        SendBulkEmails::dispatch($emailsData, $user);

        $numberOfEmails = count($emailsData);

        return $this->success("Processing {$numberOfEmails} email(s)", Response::HTTP_ACCEPTED);
    }

    public function list(): JsonResponse
    {
        $emails = $this->elasticsearchHelper->getStoredEmails();

        return $this->success("Sent Emails Retrieved",Response::HTTP_OK, $emails);
    }
}
