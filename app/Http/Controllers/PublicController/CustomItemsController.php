<?php

namespace App\Http\Controllers\PublicController;

use App\Http\Controllers\Controller as Controller;
use Response;

use App\Models\CustomGroups;
use App\Models\CustomProducts;


class CustomItemsController extends Controller
{

    public function fetchGroups()
    {
        $CustomGroups   = CustomGroups::all();
        $data           = [];
        if( $CustomGroups === null ) return Response::success();

        foreach( $CustomGroups as $item ){
            $configCanvas       = [
                'widthJson'     => json_decode( $item->customCanvases->widthJson , true ), 
                'heightJson'    => json_decode( $item->customCanvases->heightJson , true ), 
                'tierJson'      => json_decode( $item->customCanvases->tierJson , true )
            ];

            $data[] = [
                'id'            => $item->id,
                'name'          => $item->name,
                'description'   => $item->description,
                'image'         => $item->image,
                'configCanvas'  => $configCanvas
            ];
        }
        
        return Response::success( $data );
    }
    public function fetchProduct( $groupId )
    {
        $CustomGroups = CustomGroups::find( $groupId );
        if( $CustomGroups === null ) return Response::success();
        if( $CustomGroups->customProducts === null ) return Response::success();
        
        foreach( $CustomGroups->customProducts as $item ){
            $data[] = [
                'id'            => $item->id,
                'groupId'       => $item->custom_group_id,
                'name'          => $item->name,
                'description'   => $item->description,
                'image'         => $item->image,
            ];
        }
    
        return Response::success( $data );
    }
    public function fetchItems( $groupId , $productId )
    {
        $CustomGroups = CustomGroups::find( $groupId );
        if( $CustomGroups === null ) return Response::success();
        if( $CustomGroups->customProducts->find( $productId ) === null ) return Response::success();
        if( $CustomGroups->customProducts->find( $productId )->customItems === null ) return Response::success();
        
        foreach( $CustomGroups->customProducts->find( $productId )->customItems as $item ){
            $data[] = [
                'id'            => $item->id,
                'groupId'       => $item->custom_group_id,
                'productId'     => $item->custom_product_id,
                'name'          => $item->name,
                'price'         => floatval($item->price),
                'description'   => $item->description,
                'image'         => $item->image,
            ];
        }
        return Response::success( $data );
    }
    public function fetchMocks( $groupId , $productId , $itemId )
    {
        $CustomGroups = CustomGroups::find( $groupId );
        if( $CustomGroups === null ) return Response::success();
        if( $CustomGroups->find( $productId ) === null ) return Response::success();
        if( $CustomGroups->customProducts->find( $productId )->customItems === null ) return Response::success();
        if( null === $itemObj = $CustomGroups->customProducts->find( $productId )->customItems->find( $itemId ) ) return Response::success();
        if( null === $arr = $CustomGroups->customProducts->find( $productId )->customItems->find( $itemId )->customMocks ) return Response::success();
        
        foreach( $arr as $item ){
            $data[] = [
                'id'            => $item->id,
                'groupId'       => $item->custom_group_id,
                'productId'     => $item->custom_product_id,
                'itemId'        => $item->custom_item_id,
                'name'          => $item->name,
                'description'   => $item->description,
                'price'         => floatval( $item->price ),
                'image'         => $item->image,
            ];
        }
        return Response::success( [
            'id'            => $itemObj->id,
            'groupId'       => $itemObj->custom_group_id,
            'productId'     => $itemObj->custom_product_id,
            'name'          => $itemObj->name,
            'description'   => $itemObj->description,
            'image'         => $itemObj->image,
            'price'         => floatval($itemObj->price),
            'items' => $data
        ] );
    }
}