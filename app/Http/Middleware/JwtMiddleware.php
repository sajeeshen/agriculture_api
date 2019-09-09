<?php
namespace App\Http\Middleware;
use Closure;
use Exception;
use App\User;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use App\Traits\ApiResponser;
class JwtMiddleware
{
    use ApiResponser;
    public function handle($request, Closure $next,  ...$roles)
    {
        $token = $request->get('token');
        //$token = $request->bearerToken();
        if(!$token) {
            // Unauthorized response if token not there

            return $this->errorResponse('Token not provided.'
            , 401);
        }
        try {
            $credentials = JWT::decode($token, env('JWT_SECRET'), ['HS256']);
        } catch(ExpiredException $e) {

            return $this->errorResponse('Provided token is expired.'
            , 401);
        } catch(Exception $e) {

            return $this->errorResponse('An error while decoding token.'
            , 401);
        }
        $user = User::find($credentials->sub);
        // Now let's put the user in the request class so that you can grab it from there
        if(count($roles) == 0){
            $request->auth = $user;
            return $next($request);
        }
        if ($user && $user->hasAnyRole($roles)) {
            $request->auth = $user;
            return $next($request);
        }else{
            return $this->errorResponse('You are unauthorized to access this resource.'
            , 401);
        }

    }
}