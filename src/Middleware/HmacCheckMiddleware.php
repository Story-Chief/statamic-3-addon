<?php

namespace StoryChief\StoryChief\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Statamic\Exceptions\UnauthorizedHttpException;

class HmacCheckMiddleware
{

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     * @throws UnauthorizedHttpException
     */
    public function handle(Request $request, Closure $next)
    {
        $payload = $request->post();

        $given_mac = Arr::pull($payload, 'meta.mac');
        $calc_mac = hash_hmac(
          'sha256',
          json_encode($payload),
          config('storychief.encryption_key')
        );

        if (!hash_equals($given_mac, $calc_mac)) {
            throw new UnauthorizedHttpException(
              401,
              'Invalid payload hash, please check your encryption key.'
            );
        }

        return $next($request);
    }

}
