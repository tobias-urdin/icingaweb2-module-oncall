<?php
namespace Icinga\Module\Oncall\Controllers;

use Icinga\Module\Oncall\Forms\OnCallConfigForm;
use Icinga\Web\Controller;

/**
 * Oncall module index
 */
class IndexController extends Controller
{
	public function init()
	{
		$this->assertPermission('config/modules');
		parent::init();
	}

	/**
	 * Oncall module index page
	 */
	public function indexAction()
	{
		$this->view->form = $form = new OnCallConfigForm();
		$form->assertPermission('config/modules');
		$form->setIniConfig($this->Config())->handleRequest();
	}
}
