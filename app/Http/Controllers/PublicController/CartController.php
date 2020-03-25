<?php

namespace App\Http\Controllers\PublicController;

use App\Http\Controllers\Controller as Controller;
use Response;
use Validator;
use Illuminate\Http\Request;
use App\Models\CustomItems;
use App\Models\Couriers;
use App\Models\CustomClass\CartItem;
use App\Repositories\CartRepository;

class CartController extends Controller
{
    public function fetch( $cartId )
    {
        $CartRepository = new CartRepository( $cartId );
        return Response::success( $CartRepository->fetchCartItemsArr() );
    }

    public function increase( Request $request, $cartId )
    {
        $rule = [
            'cartKey'    => 'required',
        ];
        $validator      = Validator::make($request->all(), $rule );
        $errors         = [];

        if ( $validator->fails() ) {
            $errors = $validator->errors()->messages();
        }
        if( !empty( $errors ) ){
            return Response::error( $errors );
        }

        $cartKey            = $request->get('cartKey');
        $CartRepository     = new CartRepository( $cartId );
        if( $CartRepository->inCart( $cartKey ) === false ){
            return Response::error( [ 'cartKey' => [ 'not found in cart' ] ] );
        } 
        $CartRepository->increase( $cartKey );
        return Response::success( $CartRepository->fetchCartItemsArr() );
    }
    
    public function decrease( Request $request, $cartId )
    {
        $rule = [
            'cartKey'    => 'required',
        ];
        $validator      = Validator::make($request->all(), $rule );
        $errors         = [];

        if ( $validator->fails() ) {
            $errors = $validator->errors()->messages();
        }
        if( !empty( $errors ) ){
            return Response::error( $errors );
        }

        $cartKey            = $request->get('cartKey');
        $CartRepository     = new CartRepository( $cartId );
        if( $CartRepository->inCart( $cartKey ) === false ){
            return Response::error( [ 'cartKey' => [ 'not found in cart' ] ] );
        } 
        $CartRepository->decrease( $cartKey );
        return Response::success( $CartRepository->fetchCartItemsArr() );   
    }

    public function deleteItem( Request $request, $cartId )
    {
        $rule = [
            'cartKey'    => 'required',
        ];
        $validator      = Validator::make($request->all(), $rule );
        $errors         = [];

        if ( $validator->fails() ) {
            $errors = $validator->errors()->messages();
        }
        if( !empty( $errors ) ){
            return Response::error( $errors );
        }

        $cartKey            = $request->get('cartKey');
        $CartRepository     = new CartRepository( $cartId );
        if( $CartRepository->inCart( $cartKey ) === false ){
            return Response::error( [ 'cartKey' => [ 'not found in cart' ] ] );
        } 
        $CartRepository->delete( $cartKey );
        return Response::success( $CartRepository->fetchCartItemsArr() );  
    }

    public function delete( $cartId )
    {
        $CartRepository     = new CartRepository( $cartId );
        $CartRepository->deleteCart();
        return Response::success( $CartRepository->fetchCartItemsArr() );  
    }

    public function post( Request $request , $cartId )
    {
        $rule = [
            'type'      => 'required',
            'itemId'    => 'required|integer',
            'amount'    => 'required|integer|min:1',
        ];

        $validator      = Validator::make($request->all(), $rule );
        $errors         = [];

        if ( $validator->fails() ) {
            $errors = $validator->errors()->messages();
        }

        if( !in_array( $request->get('type') , [ 'custom' ] ) ) {
            $errors['type'][]   = 'The type field is invalid.';
        }
        
        switch( $request->get('type') ){
            case 'custom' :
                $ruleArr = [ 
                    'data'                      => 'required|array|min:1' , 
                    'data.*.id'                 => 'required|integer|exists:custom_mocks,id' , 
                    'data.*.image'              => 'required' , 
                    'data.*.image_original'     => 'required' 
                    // 'data.*.image'              => 'required|url' , 
                    // 'data.*.image_original'     => 'required|url' 
                ];
                $validatorCase  = Validator::make( $request->all(), $ruleArr );
                if ( $validatorCase->fails() ) {
                    $errorsCase = $validatorCase->errors()->messages();
                    $errors     = array_merge( $errors , $errorsCase );
                }
                $customItems    = CustomItems::find( $request->get('itemId') );
                if( is_null( $customItems ) ) {
                    $errors['itemId'][]   = 'The itemId field is not found item.';
                }else{
                    $customProduct  = $customItems->customProducts;
                    $customGroup    = $customItems->customGroups;
                    if( $customProduct === NULL || $customGroup === NULL ){
                        $errors['itemId'][]   = 'The itemId field is invalid.';
                    }else{
                        if( 
                            $customGroup->active === 0 || 
                            $customProduct->active === 0 || 
                            $customItems->active === 0 
                        ){
                            $errors['itemId'][]   = 'The itemId field is not active.';
                        } 
                    }
                } 
            break;
        }

        if( !empty( $errors ) ){
            return Response::error( $errors );
        }
        $CartRepository = new CartRepository( $cartId );
        $amount      = $request->get('amount');
        /* success validate */
        switch( $request->get('type') ){
            case 'custom' :
                $data = $request->get('data');
                $data = ( is_array( $data ) ) ? $data : [];
                $totalPrice = $totalCost = 0;
                foreach( $data as &$item ){
                    $item['id']             = intval( $item['id'] );
                    $item['discount']       = 0;
                    $customMocks    = $customItems->customMocks->find( $item['id'] );
                    $totalPrice     += $item['price'] = floatval( $customMocks->price );
                    $totalCost      += $item['cost'] = floatval( $customMocks->cost );
                }
                $CartRepository->insert( new CartItem( 
                    'custom' , 
                    $customItems, 
                    $totalPrice , 
                    $totalCost , 
                    $amount , [ 'custom_group_id' => $customGroup->id, 'custom_product_id'=> $customProduct->id ] , $data ) );
            break;
        }
        return Response::success( $CartRepository->fetchCartItemsArr() );
    }

    public function receiver( Request $request , $cartId )
    {
        $rule = [
            'name'              => 'required',
            'address'           => 'required',
            'subDistrict'       => 'required',
            'district'          => 'required',
            'province'          => 'required',
            'postcode'          => 'required',
            'phone'             => 'required',
        ];

        $validator      = Validator::make($request->all(), $rule );
        $errors         = [];

        if ( $validator->fails() ) {
            $errors = $validator->errors()->messages();
        }
        if( !empty( $errors ) ){
            return Response::error( $errors );
        }
        $CartRepository = new CartRepository( $cartId );
        $CartRepository->pushReceiver( 
            $request->get( 'name' ),
            $request->get( 'address' ),
            $request->get( 'subDistrict' ),
            $request->get( 'district' ),
            $request->get( 'province' ),
            $request->get( 'postcode' ),
            $request->get( 'phone' )
         );
        return Response::success( $CartRepository->fetchCartItemsArr() );
    }
    public function sender( Request $request , $cartId )
    {
        $rule = [
            'name'              => 'required',
            'address'           => 'required',
            'subDistrict'       => 'required',
            'district'          => 'required',
            'province'          => 'required',
            'postcode'          => 'required',
            'phone'             => 'required',
        ];

        $validator      = Validator::make($request->all(), $rule );
        $errors         = [];

        if ( $validator->fails() ) {
            $errors = $validator->errors()->messages();
        }
        if( !empty( $errors ) ){
            return Response::error( $errors );
        }
        $CartRepository = new CartRepository( $cartId );
        $CartRepository->pushSender( 
            $request->get( 'name' ),
            $request->get( 'address' ),
            $request->get( 'subDistrict' ),
            $request->get( 'district' ),
            $request->get( 'province' ),
            $request->get( 'postcode' ),
            $request->get( 'phone' )
         );
        return Response::success( $CartRepository->fetchCartItemsArr() );
    }
    public function courier( Request $request , $cartId )
    {
        $rule = [
            'courierId'         => 'required|exists:couriers,id',
        ];
        $validator      = Validator::make($request->all(), $rule );
        $errors         = [];
        

        if ( $validator->fails() ) {
            $errors = $validator->errors()->messages();
        }
        if( !empty( $errors ) ){
            return Response::error( $errors );
        }
        $courier = Couriers::find( $request->get( 'courierId' ) );
        $CartRepository = new CartRepository( $cartId );
        $CartRepository->pushCourier( $courier );
        return Response::success( $CartRepository->fetchCartItemsArr() );
    }
    public function checkout( Request $request , $cartId )
    {   
        $remark = trim( $request->get('remark') );
        $remark = !empty( $remark ) ? $remark : NULL;
        
        $CartRepository = new CartRepository( $cartId );
        $CartRepository->setUser( $request->auth );

        if( $CartRepository->isCartHasItems() === false ){
            return Response::error( 'Your cart is empty items' );
        }
        $CartRepository->pushRemark( $remark );
        $res = $CartRepository->checkOut();
        if( $res['status'] !== true ){
            return Response::error( $res['message'] );
        }
        return Response::success( ['orderNo' => $this->encodeOrderCode( $res['orderId'] ) ] );
    }

    public function encodeOrderCode( $orderId )
    {
        return 'BK'.sprintf("%08d",$orderId);
    }
}