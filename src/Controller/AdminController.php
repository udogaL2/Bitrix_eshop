<?php

namespace App\Src\Controller;

use App\Src\Service\AdminService;

class AdminController extends BaseController
{
	public function getMainAdminPageAction(): void
	{
        AuthController::notAdminSessionAction();

		$section = $_GET['section'] ?? 'tags';

		$content = AdminService::getContentBySection($section);

		if ($content === [])
		{
			$content = AdminService::getContentBySection("tags");
		}

        $isOrderSection = $section === 'orders';

        echo self::view( 'Main/index.html', [
            'content' => self::view('Admin/main.html' , [
                'content' => $content,
                'isOrderSection' => $isOrderSection,
                ]),
            'isAdmin' => true,
        ]);
	}
}


