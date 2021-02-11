<?php

$cfg = require __DIR__ . '/../vendor/mediawiki/mediawiki-phan-config/src/config.php';

$cfg['directory_list'] = [
	'src',
	'tests',
	'vendor/wikimedia/idle-dom/src',
	'.phan/stubs',
];
$cfg['suppress_issue_types'] = [];

return $cfg;
