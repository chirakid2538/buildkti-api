<?php

namespace App\Repositories;

use Cache;
use DB;
use App\Models\Orders;
use Storage;

class CartRepository 
{
    const START_STATE           = 'CREATE';
    const SESSION_KEY           = 'carts';
    const OPTIONS_COST          = 'COST';
    private $cartId             = '';
    private $options            = [];
    private $cartItems  = [];
    public function __construct( $cartId ) {
        $this->cartId = trim( $cartId );
        $cart               = Cache::get( static::SESSION_KEY . '.' . $cartId );
        $this->cartItems    = ( is_array( $cart ) ) ? $cart : [];


        $this->pushSender( 'chirakid', '1/2', 'ไพศาลี','ไพศาลี', 'นครสวรรค์', '10400', '0827639234', 'aum.chirakid@gmail.com' );
        
    }

    public function setUser( $user )
    {
        $this->cartItems['user_id'] = ( $user && $user->id > 0 ) ? $user->id : 0;
        $this->cartItems['type']    = ( $user && $user->id > 0 ) ? 'user' : 'guess';
        $this->pushSession();
    }

    public function getUserID()
    {
        return ( isset( $this->cartItems['user_id'] ) ) ? intval( $this->cartItems['user_id'] ) : 0;
    }
    public function getUserType()
    {
        return ( isset( $this->cartItems['type'] ) ) ? $this->cartItems['type'] : 'guess';
    }
    public function fetchAction()
    {
        return [ 'increase', 'decrease', 'delete' ];
    }
    public function fetchCartItems()
    {
        return $this->cartItems;
    }

    public function fetchCartItemsArr()
    {
        $data = [];
        $data['totalPrice'] = isset( $this->cartItems['totalPrice'] ) ? $this->cartItems['totalPrice'] : 0;
        $data['amount']     = isset( $this->cartItems['amount'] ) ? $this->cartItems['amount']: 0;
        $data['discount']   = isset( $this->cartItems['discount'] ) ? $this->cartItems['discount'] : 0;
        $data['items']      = [];

        $data['receiver']    = isset( $this->cartItems['receiver'] ) ? $this->cartItems['receiver'] : NULL;
        $data['sender']      = isset( $this->cartItems['sender'] ) ? $this->cartItems['sender'] : NULL;
        $data['courier']     = isset( $this->cartItems['courier'] ) ? $this->cartItems['courier'] : NULL;
        $data['remark']      = isset( $this->cartItems['remark'] ) ? $this->cartItems['remark'] : NULL;

        if( !empty( $data['courier'] ) ){
            unset( $data['courier']['cost'] );
        }
        if( !empty( $this->cartItems['items'] ) ){
            foreach( $this->cartItems['items'] as $_k => $item ){
                $data['items'][ $_k ] = $item->toArray();
                unset( $data['items'][ $_k ]['cost'] );
                unset( $data['items'][ $_k ]['totalCost'] );
                unset( $data['items'][ $_k ]['amountCost'] );

                if( !empty( $item->options() ) ){
                    foreach( $data['items'][ $_k ]['options'] as &$option ){
                        unset( $option['cost'] );
                    }
                }                
            }
        }        
        return $data;
    }
    public function pushSession()
    {

        $this->cartItems['totalPrice']      = 0;
        $this->cartItems['totalCost']       = 0;
        $this->cartItems['amount']      = 0;
        $this->cartItems['discount']    = 0;
        $this->cartItems['receiver']    = isset( $this->cartItems['receiver'] ) ? $this->cartItems['receiver'] : NULL;
        $this->cartItems['sender']      = isset( $this->cartItems['sender'] ) ? $this->cartItems['sender'] : NULL;
        $this->cartItems['courier']     = isset( $this->cartItems['courier'] ) ? $this->cartItems['courier'] : NULL;
        $this->cartItems['remark']      = isset( $this->cartItems['remark'] ) ? $this->cartItems['remark'] : NULL;
        $this->cartItems['items']       = isset( $this->cartItems['items'] ) ? $this->cartItems['items'] : [];

        if( !empty( $this->cartItems['items'] ) ){
            foreach( $this->cartItems['items'] as $item ){
                $this->cartItems['totalPrice']  += $item->getTotalPrice();
                $this->cartItems['totalCost']   += $item->getTotalCost();
                $this->cartItems['amount']      += $item->getAmount();
                $this->cartItems['discount']    += $item->getDiscount();
            }
        }

        Cache::set(  static::SESSION_KEY . '.' . $this->cartId , $this->cartItems );
    }

    public function pushSender( $name, $address, $subDistrict = '', $district = '', $province, $postcode, $phone = '', $email = '' )
    {
        $this->cartItems['sender']    = [
            'name'          => trim( $name ),
            'address'       => trim( $address ),
            'subDistrict'   => trim( $subDistrict ),
            'district'      => trim( $district ),
            'province'      => trim( $province ),
            'postcode'      => trim( $postcode ),
            'phone'         => trim( $phone ),
            'email'         => trim( $email ),
        ];
        $this->pushSession();
    }

    public function pushReceiver( $name, $address, $subDistrict = '', $district = '', $province, $postcode, $phone = '', $email = '' )
    {
        $this->cartItems['receiver']    = [
            'name'          => trim( $name ),
            'address'       => trim( $address ),
            'subDistrict'   => trim( $subDistrict ),
            'district'      => trim( $district ),
            'province'      => trim( $province ),
            'postcode'      => trim( $postcode ),
            'phone'         => trim( $phone ),
            'email'         => trim( $email ),
        ];
        $this->pushSession();
    }

    public function pushCourier( $courier )
    {
        $this->cartItems['courier']    = [
            'id'            => $courier->id,
            'code'          => $courier->code,
            'name'          => $courier->name,
            'description'   => $courier->description,
            'image'         => $courier->image,
            'cost'          => $courier->cost,
            'price'         => $courier->price,
        ];
        $this->pushSession();
    }
    public function getReceiver(){
        return $this->cartItems['receiver'];
    }
    public function getSender(){
        return $this->cartItems['sender'];
    }

    public function pushRemark( $remark )
    {
        $this->cartItems['remark']    = trim( $remark );
        $this->pushSession();
    }

    public function insert( $cart )
    {
        $this->cartItems['items'][ $cart->getCartKey() ] = $cart;
        $this->pushSession();
    }
    public function inCart( $cartItemKey )
    {
        if( array_key_exists( $cartItemKey, $this->cartItems['items'] ) ) return true;
        return false;
    }
    public function increase( $cartItemKey )
    {
        if( $this->inCart( $cartItemKey ) === false ) return false;
        $this->cartItems['items'][ $cartItemKey ]->increase();
        $this->pushSession();
    }
    public function decrease( $cartItemKey )
    {
        if( $this->inCart( $cartItemKey ) === false ) return false;
        if( $this->cartItems['items'][ $cartItemKey ]->getAmount() > 1 ){
            $this->cartItems['items'][ $cartItemKey ]->decrease();
            $this->pushSession();
        }else{
            $this->delete( $cartItemKey );
        }
        
    }
    public function delete( $cartItemKey )
    {
        if( $this->inCart( $cartItemKey ) === false ) return false;
        unset( $this->cartItems['items'][ $cartItemKey ] );
        $this->pushSession();
    }

    public function deleteCart()
    {
        if( array_key_exists( 'items' , $this->cartItems ) ){
            unset( $this->cartItems['items'] );
        }
        $this->pushSession();
    }


    public function fetchItems()
    {
        return $this->cartItems['items'];
    }
    public function fetchCourier()
    {
        return $this->cartItems['courier'];
    }
    public function isCartHasItems()
    {
        return ( isset( $this->cartItems['items'] ) && !empty( $this->cartItems['items'] ) );
    }
    public function isCartHasSender()
    {
        return ( isset( $this->cartItems['sender'] ) && !empty( $this->cartItems['sender'] ) );
    }
    public function isCartHasReceiver()
    {
        return ( isset( $this->cartItems['receiver'] ) && !empty( $this->cartItems['receiver'] ) );
    }
    public function isCartHasCourier()
    {
        return ( isset( $this->cartItems['courier'] ) && !empty( $this->cartItems['courier'] ) );
    }
    public function getAmount()
    {
        return $this->cartItems['amount'];
    }
    public function getTotalPrice()
    {
        return $this->cartItems['totalPrice'];
    }
    public function getTotalCost()
    {
        return $this->cartItems['totalCost'];
    }
    public function getTotalDiscount()
    {
        return $this->cartItems['discount'];
    }
    public function getRemark()
    {
        return $this->cartItems['remark'];
    }
    public function checkOut()
    {
        $response = [ 'status' => false , 'message' => 'error' , 'orderId' => 0 ];
        if( $this->isCartHasItems() === false ){
            $response['status']         = false;
            $response['message']        = 'Your cart is empty';
            return $response;
        }
        if( $this->isCartHasSender() === false ){
            $response['status']         = false;
            $response['message']        = 'Your cart not found sender';
            return $response;
        }
        if( $this->isCartHasReceiver() === false ){
            $response['status']         = false;
            $response['message']        = 'Your cart not found receiver';
            return $response;
        }
        if( $this->isCartHasCourier() === false ){
            $response['status']         = false;
            $response['message']        = 'Your cart not found courier';
            return $response;
        }
        DB::beginTransaction();

        $Order             = new Orders();
        $Order->type       = $this->getUserType();
        $Order->user_id    = $this->getUserID();
        $Order->count      = $this->getAmount();
        $Order->cost       = $this->getTotalCost();
        $Order->discount   = $this->getTotalDiscount();
        $Order->price      = $this->getTotalPrice();
        $Order->state      = static::START_STATE;
        $Order->remark     = $this->getRemark();
        $Order->save();
        if( $Order->id < 1 ){
            DB::rollBack();
            $response['status']         = false;
            $response['message']        = 'Your cart can not create order';
            return $response;
        } 

    


        $sender     = $this->getSender();
        $receiver   = $this->getReceiver();

        $orderSendersCreate = $Order->orderSenders()->create([
            'name'              => $sender['name'],
            'address'           => $sender['address'],
            'subDistrict'       => $sender['subDistrict'],
            'district'          => $sender['district'],
            'province'          => $sender['province'],
            'postcode'          => $sender['postcode'],
            'phone'             => $sender['phone'],
            'email'             => $sender['email'],
        ]);
        if( $orderSendersCreate->id < 1 ){
            DB::rollBack();
            $response['status']         = false;
            $response['message']        = 'Your cart can not create sender';
            return $response;
        }
        $orderReceiverCreate = $Order->orderReceivers()->create([
            'name'              => $receiver['name'],
            'address'           => $receiver['address'],
            'subDistrict'       => $receiver['subDistrict'],
            'district'          => $receiver['district'],
            'province'          => $receiver['province'],
            'postcode'          => $receiver['postcode'],
            'phone'             => $receiver['phone'],
            'email'             => $receiver['email'],
        ]);
        if( $orderReceiverCreate->id < 1 ){
            DB::rollBack();
            $response['status']         = false;
            $response['message']        = 'Your cart can not create receiver';
            return $response;
        }

        $courier = $this->fetchCourier();
        $orderCouriersCreate = $Order->orderCouriers()->create([
            'courier_id'    => $courier['id'],
            'code'          => $courier['code'],
            'state'         => 'CREATE',
            'cost'          => $courier['cost'],
            'price'         => $courier['price'],
        ]);
        if( $orderCouriersCreate->id < 1 ){
            DB::rollBack();
            $response['status']         = false;
            $response['message']        = 'Your cart can not create shipping order';
            return $response;
        }

        $orderStatusesCreate = $Order->orderStatuses()->create([
            'state'             => static::START_STATE,
            'state_text'        => '',
        ]);
        if( $orderStatusesCreate->id < 1 ){
            DB::rollBack();
            $response['status']         = false;
            $response['message']        = 'Your cart can not create order status';
            return $response;
        }


        foreach( $this->fetchItems() as $_k => $item ){
            $optionsId          = $item->optionsId();
            $orderCustoms       = $Order->orderCustoms();
            $orderCustomsCreate = $orderCustoms->create(
                [
                    'custom_group_id'           => $optionsId['custom_group_id'],
                    'custom_product_id'         => $optionsId['custom_product_id'],
                    'custom_item_id'            => $item->getItemId(),
                    'cost'                      => $item->getCost(),
                    'discount'                  => $item->getDiscount(),
                    'price'                     => $item->getPrice(),
                    'amount'                    => $item->getAmount(),
                ]
            );
            if( $orderCustomsCreate->id < 1 ){
                DB::rollBack();
                $response['status']         = false;
                $response['message']        = 'Your cart can not create custom order';
                return $response;
            } 
            $customMocks = [];
            foreach( $item->options() as $opt ){

                $customMocks[] = [
                    'custom_mock_id'    => $opt['id'],
                    'cost'              => $opt['cost'],
                    'discount'          => $opt['discount'],
                    'price'             => $opt['price'],
                    'image'             => $this->saveImage( $opt['image'] ),
                    'image_original'    => $this->saveImage( $opt['image_original'] )
                ];
            }

            $OrderCustomMocksCreate = $orderCustomsCreate->OrderCustomMocks()->createMany(
                $customMocks
            ); 
             
            foreach ( $OrderCustomMocksCreate as $itemMock) {
                if( $itemMock->id < 1 ){
                    DB::rollBack();
                    $response['status']         = false;
                    $response['message']        = 'Your cart can not create custom mock order';
                    return $response;
                } 
            }

            DB::commit();
            $response['status']         = true;
            $response['message']        = 'Created';
            $response['orderId']        = $Order->id;

            $this->cartItems = [];
            $this->pushSession();
            return $response;
        }
    }
    function saveImage( $base64 )
    {
        $image = str_replace('data:image/png;base64,', '', $base64);
        $image          = str_replace(' ', '+', $image);
        $imageName      = \Illuminate\Support\Str::random(32) . '.png';
        $path           = date('Y-m-d') . '/' . $imageName;
        Storage::disk('public')->put( $path , base64_decode($image) );
        return $path;
    }
}