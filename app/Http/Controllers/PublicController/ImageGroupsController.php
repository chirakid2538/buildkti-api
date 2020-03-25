<?php

namespace App\Http\Controllers\PublicController;

use App\Http\Controllers\Controller as Controller;
use Response;
use App\Models\ImageGroups;

class ImageGroupsController extends Controller
{

    public function fetch()
    {
        $data   = [];
        $items  = ImageGroups::all()->toArray();
        if( empty( $items ) ){
            return Response::success();
        }
        foreach ( $items as $item ) {
            $data[] = [
                'id'        => $item['id'],
                'name'      => $item['name'],
                'image'     => $item['image'],
            ];
        }

        return Response::success( $data );
    }
    public function fetchImages( $id )
    {
        $ImageGroups  = ImageGroups::find( $id );
        if( $ImageGroups === NULL ){
            return Response::success();
        } 
        $items = $ImageGroups->images->toArray();
        if( empty( $items ) ){
            return Response::success();
        }
        foreach ( $items as $item ) {
            $data[] = [
                'id'        => $item['id'],
                'image'     => $item['image'],
            ];
        }
        return Response::success( $data );
    }
}
