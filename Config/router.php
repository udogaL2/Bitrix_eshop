<?php

return array (
	 array (
		 'path' => '/',
		 'method' => 'GET',
		 'action' => [new App\Src\Controller\IndexController(), 'viewGoodByPage'],
	 ),
	 array (
		 'path' => '/orderPlaced',
		 'method' => 'GET',
		 'action' => [new App\Src\Controller\OrderController(), 'successOrderAction'],
	 ),
	 array (
		 'path' => '/orderError',
		 'method' => 'GET',
		 'action' => [new App\Src\Controller\OrderController(), 'errorOrderAction'],
	 ),
	 array (
		 'path' => '/page/:page',
		 'method' => 'GET',
		 'action' => [new App\Src\Controller\IndexController(), 'viewGoodByPage'],
	 ),
	 array (
		'path' => '/product/:id',
		'method' => 'GET',
		'action' => [new App\Src\Controller\GoodController(), 'getDetailedGoodAction'],
	),
	 array (
		 'path' => '/error/404',
		 'method' => 'GET',
		 'action' => [new App\Src\Controller\IndexController(), 'notFoundAction'],
	 ),
	 array (
		 'path' => '/order/:id',
		 'method' => 'GET',
		 'action' => [new App\Src\Controller\OrderController(), 'createOrderAction'],
	 ),
	 array (
		 'path' => '/order/:id',
		 'method' => 'POST',
		 'action' => [new App\Src\Controller\OrderController(), 'registerOrderAction'],
	 ),

     array (
        'path' => '/fatal',
        'method' => 'GET',
        'action' => [new App\Src\Controller\IndexController(), 'internalErrorAction'],
    ),
    array (
        'path' => '/auth',
        'method' => 'GET',
        'action' => [new App\Src\Controller\AuthController(), 'loginAction'],
    ),
    array (
        'path' => '/auth',
        'method' => 'POST',
        'action' => [new App\Src\Controller\AuthController(), 'loginAction'],
    ),
	array (
		 'path' => '/admin',
		 'method' => 'GET',
		 'action' => [new \App\Src\Controller\AdminController(), 'getMainAdminPageAction'],
	 ),
	 array (
		 'path' => '/admin',
		 'method' => 'POST',
		 'action' => [new \App\Src\Controller\AdminController(), 'addNewData'],
	 ),
	 array (
		 'path' => '/admin/delete/:section/:id',
		 'method' => 'POST',
		 'action' => [new \App\Src\Controller\AdminController(), 'deleteData'],
	 ),
    array (
        'path' => '/logout',
        'method' => 'GET',
        'action' => [new \App\Src\Controller\AuthController(), 'logoutAction'],
    ),
    array (
        'path' => '/edit/tags/:id',
        'method' => 'GET',
        'action' => [new \App\Src\Controller\AdminController(), 'detailedTagsAdminPageAction'],
    ),
    array (
        'path' => '/edit/tags/:id',
        'method' => 'POST',
        'action' => [new \App\Src\Controller\AdminController(), 'detailedTagsAdminPageAction'],
    ),
    array (
        'path' => '/edit/goods/:id',
        'method' => 'GET',
        'action' => [new \App\Src\Controller\AdminController(), 'detailedGoodAdminPageAction'],
    ),
    array (
        'path' => '/edit/goods/:id',
        'method' => 'POST',
        'action' => [new \App\Src\Controller\AdminController(), 'detailedGoodAdminPageAction'],
    ),
    array (
        'path' => '/edit/orders/:id',
        'method' => 'GET',
        'action' => [new \App\Src\Controller\AdminController(), 'detailedOrdersAdminPageAction'],
    ),
    array (
        'path' => '/edit/orders/:id',
        'method' => 'POST',
        'action' => [new \App\Src\Controller\AdminController(), 'detailedOrdersAdminPageAction'],
    ),
//    array(
//        'path' => '/admin/',
//        'method' => 'GET',
//        'action' => [new \App\Src\Controller\AuthController(), 'logoutAction'],
//    ),
//     array (
//         'path' => '/:search/:page',
//         'method' => 'POST',
//         'action' => [new App\Src\Controller\OrderController(), 'getProductsBySearchAction'],
//     ),
);