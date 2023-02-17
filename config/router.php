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
		 'path' => '/:page',
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
//     array (
//         'path' => '/:search/:page',
//         'method' => 'POST',
//         'action' => [new App\Src\Controller\OrderController(), 'getProductsBySearchAction'],
//     ),
	 // 21232f297a57a5a743894a0e4a801fc3 - md5 hash of 'admin'
	 // array (
		//  'path' => '/21232f297a57a5a743894a0e4a801fc3',
		//  'method' => 'GET',
		//  'action' => [new App\Controller\AdminController(), 'adminAction'],
	 // ),
);