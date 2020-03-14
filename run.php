<?php
use Icinga\Application\Icinga;

if (Icinga::app()->isCli()) {
    return;
}

$oncallModuleConfig = new Zend_Controller_Router_Route(
	'oncall/configuration',
	[
		'controller' => 'index',
		'action'     => 'index',
		'module'     => 'oncall'
	]
);

$oncallModuleIncoming = new Zend_Controller_Router_Route(
	'oncall/incoming',
	[
		'controller' => 'incoming',
		'action'     => 'index',
		'module'     => 'oncall'
	]
);

$this->addRoute('oncall', $oncallModuleConfig);
$this->addRoute('oncall', $oncallModuleIncoming);
