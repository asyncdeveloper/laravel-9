<?php

namespace App\Http\Middleware;

use App\Traits\ApiResponse;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAuthorized
{
    use ApiResponse;

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(Request): (Response|RedirectResponse) $next
     * @return JsonResponse
     */
    public function handle(Request $request, Closure $next): JsonResponse
    {
        if (!$request->has('api_token')) {
            return $this->authorizationError('Missing API token');
        }

        $apiToken = $request->get('api_token');

        if ($apiToken !== config('app.api_token')) {
            return $this->authorizationError('Invalid API token');
        }

        return $next($request);
    }
}
