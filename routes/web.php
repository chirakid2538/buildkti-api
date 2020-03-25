<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('/tmp/image/', function () use ($router) {
    header('Access-Control-Allow-Origin: *');
    $remoteImage = "https://www.idea2made.com/local/public/assets/uploads/240x401_871099515725898451881392.png";
    $imginfo = getimagesize($remoteImage);
    header("Content-type: {$imginfo['mime']}");
    readfile($remoteImage);
});
$router->group(['prefix' => 'v1'], function ($router) {
    $router->post('/register/', 'PublicController\MemberController@register' );
    $router->post('/login/', 'PublicController\MemberController@login' );

    $router->group(['middleware' => 'jwt:init'], function ($router) {
        $router->get('/fetch/imageGroups/', 'PublicController\ImageGroupsController@fetch' );
        $router->get('/fetch/imageGroup/{id}/', 'PublicController\ImageGroupsController@fetchImages' );

        $router->get('/fetch/fetchGroups/', 'PublicController\CustomItemsController@fetchGroups' );
        $router->get('/fetch/fetchProducts/{groupId}/', 'PublicController\CustomItemsController@fetchProduct' );
        $router->get('/fetch/fetchItems/{groupId}/{productId}/', 'PublicController\CustomItemsController@fetchItems' );
        $router->get('/fetch/fetchMocks/{groupId}/{productId}/{itemId}/', 'PublicController\CustomItemsController@fetchMocks' );

        $router->get('/cart/{cartId}/', 'PublicController\CartController@fetch' );
        $router->post('/cart/{cartId}/insert/', 'PublicController\CartController@post' );
        $router->post('/cart/{cartId}/increase/', 'PublicController\CartController@increase' );
        $router->post('/cart/{cartId}/decrease/', 'PublicController\CartController@decrease' );
        $router->post('/cart/{cartId}/delete/', 'PublicController\CartController@deleteItem' );
        $router->delete('/cart/{cartId}/', 'PublicController\CartController@delete' );


        $router->post('/cart/{cartId}/receiver/', 'PublicController\CartController@receiver' );
        $router->post('/cart/{cartId}/sender/', 'PublicController\CartController@sender' );
        $router->post('/cart/{cartId}/courier/', 'PublicController\CartController@courier' );
        $router->post('/cart/{cartId}/checkout/', 'PublicController\CartController@checkout' );


        $router->post('/cart/{cartId}/checkout/', 'PublicController\CartController@checkout' );

    });
});

