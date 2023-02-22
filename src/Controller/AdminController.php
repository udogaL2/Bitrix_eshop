<?php

namespace App\Src\Controller;

use App\Src\Service\AdminService;

class AdminController extends BaseController
{
	public function getMainAdminPageAction(): void
	{
		$section = $_GET['section'] ?? 'tags';

		$content = AdminService::getContentBySection($section);

		if ($content === [])
		{
			$content = AdminService::getContentBySection("tags");
		}

        echo self::view( 'Main/index.html', [
            'content' => self::view(
                'Admin/main.html' , ['content' => $content,])
        ]);
	}
}


