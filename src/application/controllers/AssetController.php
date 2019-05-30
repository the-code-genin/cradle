<?php
namespace Cradle\Application\Controllers;

use Cradle\Framework\Components\BaseController;

class AssetController extends BaseController
{
	/**
	 * Serves the 'favicon.ico' file located in the 'assets/images' directory
	 */
	public function serveFavicon(): void
	{
		$this->setOutputFile('images/favicon.ico');
	}
}
