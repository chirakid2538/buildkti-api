<?php
namespace App\Http\Middleware;

use App\Models\Users;
use Closure;
use Exception;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Response;

class JwtMiddleware
{
    public function handle( $request, Closure $next, $guard = null )
    {
        $token              = $request->header('Authorization');
        $request->auth      = null;

        if( $guard === 'init' && empty( $token ) ){
            return $next($request);
        }
        if (!$token) {
            // Unauthorized response if token not there
            return Response::authError( 'Token not provided.' );
        }
        try {
            $credentials = JWT::decode($token, env('JWT_SECRET'), ['HS256']);
        } catch (ExpiredException $e) {
            return Response::authError( 'Provided token is expired.' );
        } catch (Exception $e) {
            return Response::authError( 'An error while decoding token.' );
        }
        $user = Users::find($credentials->sub);
        // Now let's put the user in the request class so that you can grab it from there
        $request->auth = $user;
        return $next($request);
    }
}