<?php

return array (
	 array (
		 'path' => '/',
		 'method' => 'GET',
		 'action' => [new App\Src\Controller\IndexController(), 'viewGoodByPage'],
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
		 'path' => '/404',
		 'method' => 'GET',
		 'action' => [new App\Src\Controller\IndexController(), 'notFoundAction'],
	 ),
	 array(
		 'path' => '/order/:id',
		 'method' => 'GET',
		 'action' => [new App\Src\Controller\OrderController(), 'createOrderAction'],
	 ),
	 array(
		 'path' => '/src/Service/OrderPostService.php',
		 'method' => 'POST',
		 'action' => [new \App\Src\Service\OrderPostService(), 'func'],
	 ),
    array (
        'path' => '/fatal',
        'method' => 'GET',
        'action' => [new App\Src\Controller\IndexController(), 'internalErrorAction'],
    ),
	 // 21232f297a57a5a743894a0e4a801fc3 - md5 hash of 'admin'
	 // array (
		//  'path' => '/21232f297a57a5a743894a0e4a801fc3',
		//  'method' => 'GET',
		//  'action' => [new App\Controller\AdminController(), 'adminAction'],
	 // ),
);