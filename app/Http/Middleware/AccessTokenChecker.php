<?php
/**
 * Created by PhpStorm.
 * User: paulo
 * Date: 04/12/2017
 * Time: 17:52
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Application;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class AccessTokenChecker
{
    private $app;
    private $oAuthMiddleware;
    public function __construct(
        Application $app,
        Authenticate $authenticate
    ) {
        $this->app = $app;
        $this->authenticate = $authenticate;
    }
    public function handle($request, Closure $next, $scopesString = null)
    {
        if ($this->authenticate->guard($scopesString)->guest()) {
            return response('Unauthorized.', 401);
        }
        return $next($request);
    }
}
