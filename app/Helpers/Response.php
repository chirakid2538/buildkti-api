<?php

class Response {
    const CODE_ERROR_AUTH = 401;
    const CODE_NOTFOUND = 404;
    const CODE_SUCCESS = 200;

    public static function success( $data = [] )
    {
        return response()->json(['code' => static::CODE_SUCCESS , 'status' => true, 'message' => '' , 'data' => $data ]);
    }
    public static function error( $data = [] )
    {
        return response()->json(['code' => static::CODE_NOTFOUND , 'status' => false, 'message' => $data ]);
    }
    public static function authError( $message = 'permission error auth!' )
    {
        return response()->json(['code' => static::CODE_ERROR_AUTH , 'status' => false, 'message' => $message ]);
    }

}