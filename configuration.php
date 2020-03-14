<?php
use Icinga\Authentication\Auth;

$auth = Auth::getInstance();

if ($auth->hasPermission('config/modules')) {
	$section = $this->menuSection(N_('OnCall'), [
		'title'    => 'OnCall',
		'icon'     => 'user',
		'url'      => 'oncall',
		'priority' => 1000,
	]);
}
