<?php

namespace App\Src\Controller;

use App\Src\Model\Good;

class GoodController extends BaseController
{
	public function getDetailedGoodAction($id)
	{
		// product example
		$good = new Good($id, 45, date('Y-m-d'));
		//

		echo $this->view('Main/index.html', [
			'content' => $this->view('Good/index.html', [
				'goods' => [$good],
			]),
		]);
	}
}