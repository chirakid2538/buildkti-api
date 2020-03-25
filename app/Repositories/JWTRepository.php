<?php
namespace App\Repositories;

use Firebase\JWT\JWT;

class JWTRepository 
{
    static function encode( $user )
    {
        $payload = [
            'iss' => "buildkit-jwt", // Issuer of the token
            'sub' => $user->id, // Subject of the token
            'iat' => time(), // Time when JWT was issued.
            'exp' => time() + env('JWT_EXPIRE_HOUR') * 60 * 60, // Expiration time
            'payload'   => [
                'email'     => $user->email,
                'name'      => $user->name,
                'avatar'    => $user->avatar
            ]
        ];

        return JWT::encode($payload, env('JWT_SECRET'));
    }
}