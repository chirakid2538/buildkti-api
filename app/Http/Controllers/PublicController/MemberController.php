<?php

namespace App\Http\Controllers\PublicController;

use App\Http\Controllers\Controller as Controller;
use Response;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Users;
use App\Repositories\JWTRepository;


class MemberController extends Controller
{
	public function register( Request $request )
	{
		$rule = [
            'email'    	=> 'required|email|unique:users',
            'password'	=> 'required|min:6|max:18',
            'name'		=> 'required',
            'phone'		=> 'required',
        ];
        $validator      = Validator::make($request->all(), $rule );
        $errors         = [];

        if ( $validator->fails() ) {
            $errors = $validator->errors()->messages();
        }
        if( !empty( $errors ) ){
            return Response::error( $errors );
        }

        $user               = new Users();
        $user->uid 			= time();
        $user->email 		= strtolower( trim( $request->get('email') ) );
        $user->password 	= Hash::make( $request->get('password') );
        $user->name 		= trim( $request->get('name') );
        $user->phone 		= trim( $request->get('phone') );
        $user->avatar 		= NULL;
        
        if( $user->save() === false ){
            return Response::error( 'Can not create user' );
        }
        return Response::success(
            $this->response( $user )
        );
	}

	public function login( Request $request )
	{
        $email  = strtolower( trim( $request->get('email') ) );
        $user   = Users::where( 'email' , $email )->first();
        if( $user === null ){
            return Response::error( 'Not found user' );
        }
        return Response::success(
            $this->response( $user )
        );
    }
    
    public function response( $user )
    {
        return [
            'token' => JWTRepository::encode( $user )
        ];
    }
}