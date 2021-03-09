<?php

$cfg = require __DIR__ . '/../vendor/mediawiki/mediawiki-phan-config/src/config.php';

$cfg['directory_list'] = [
	'src',
	'tests',
	'vendor/wikimedia/idle-dom/src',
	'.phan/stubs',
];
$cfg['suppress_issue_types'] = [];
$cfg['exclude_analysis_directory_list'][] = 'tests/w3c/';
$cfg['exclude_analysis_directory_list'][] = 'tests/wpt/';

return $cfg;
