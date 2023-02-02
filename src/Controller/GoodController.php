<?php

namespace App\Src\Controller;

use App\Src\Model\Good;

class GoodController extends BaseController
{
	public function getDetailedGoodAction($id)
	{
		// product example
		$good = new Good($id, 'myProduct', 45, "No description", ['home', 'tech']);
		//

		echo $this->view('Main/index.html', [
			'content' => $this->view('Good/index.html', [
				'goods' => [$good],
			]),
		]);
	}
}