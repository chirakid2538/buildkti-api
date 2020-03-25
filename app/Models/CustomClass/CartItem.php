<?php
namespace App\Models\CustomClass;


class CartItem {

    private $itemId     = 0;
    private $type       = 'custom';
    private $name       = '';
    private $image      = '';

    private $price      = 0;
    private $cost       = 0;
    private $discount   = 0;
    private $amount     = 1;
    private $optionsId  = [];
    private $options    = [];

    private $totalPrice      = 0;
    private $totalCost       = 0;

    public function __construct( $type = 'custom' , $item , $price , $cost , $amount , $optionsId = [], $options = [] ) {
        $this->type         = $type;
        $this->name         = $item->name;
        $this->itemId       = intval( $item->id );

        $this->image        = $item->image;
        $this->amount       = intval( $amount );
        
        $this->price                = floatval( $item->price );
        $this->cost                 = floatval( $item->cost );
        $this->amountPrice          = ( floatval( $price ) + $this->price );
        $this->amountCost           = ( floatval( $cost ) + $this->cost );

        $this->totalPrice   = $this->amountPrice * $this->amount;
        $this->totalCost    = $this->amountCost * $this->amount;

        
        $this->optionsId    = $optionsId;
        $this->options      = $options;
    }

    public function toArray(){
        return [
            'itemId'        => $this->itemId,
            'name'          => $this->name,
            'type'          => $this->type,
            'image'         => $this->image,
            'price'         => $this->price,
            'cost'          => $this->cost,
            'discount'      => $this->discount,
            'amount'        => $this->amount,
            'amountPrice'    => $this->amountPrice,
            'amountCost'    => $this->amountCost,
            'totalPrice'    => $this->totalPrice,
            'totalCost'     => $this->totalCost,
            'options'       => $this->options,
            'optionsId'     => $this->optionsId,
        ];
    }
    public function getItemId()
    {
        return $this->itemId;
    }
    public function getDiscount()
    {
        return $this->discount;
    }

    public function optionsId()
    {
        return $this->optionsId;
    }
    public function options()
    {
        return $this->options;
    }
    public function getPrice()
    {
        return $this->price;
    }
    public function getCost()
    {
        return $this->cost;
    }
    public function getTotalPrice()
    {
        return $this->totalPrice;
    }
    public function getTotalCost()
    {
        return $this->totalCost;
    }
    public function getAmount()
    {
        return $this->amount;
    }
    public function increase()
    {
        $this->amount++;
        $this->totalPrice   = $this->amountPrice * $this->amount;
        $this->totalCost    = $this->amountCost * $this->amount;
        return $this;
    }
    public function decrease()
    {
        $this->amount--;
        $this->totalPrice   = $this->amountPrice * $this->amount;
        $this->totalCost    = $this->amountCost * $this->amount;
        return $this;
    }
    
    public function getCartKey()
    {

        $data = [
            $this->type,
            $this->itemId
        ];
        if( $this->type == 'custom' ){
            $data[] = time();
        }
        return md5( implode( '-' , $data ) );
    }
}
