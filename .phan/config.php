<?php

$cfg = require __DIR__ . '/../vendor/mediawiki/mediawiki-phan-config/src/config.php';

$cfg['directory_list'] = [
	'src',
	'tests',
	'tools',
	'vendor/consolidation/robo/src',
	'vendor/nikic/php-parser/lib',
	'vendor/phpunit/phpunit/src/Framework',
	'vendor/symfony',
	'vendor/wikimedia/idle-dom/src',
	'vendor/wikimedia/remex-html/src/Serializer',
	'vendor/wikimedia/remex-html/src/Tokenizer',
	'vendor/wikimedia/remex-html/src/TreeBuilder',
	'vendor/wikimedia/zest-css/src',
	'.phan/stubs',
];
$cfg['suppress_issue_types'] = [];
$cfg['exclude_analysis_directory_list'][] = 'vendor/';
$cfg['exclude_analysis_directory_list'][] = 'tests/W3C/';
$cfg['exclude_analysis_directory_list'][] = 'tests/WPT/';

if ( getenv( "DODO_CHECK_MAGIC_PROPERTIES" ) ) {
	// Don't let Dodo itself use magic properties
	$cfg['read_magic_property_annotations'] = false;
	// Except in tests, that's fine.
	$cfg['exclude_analysis_directory_list'][] = 'tests/';
}

// Don't whine about PHP 8.1+ issue suppressions.
if ( PHP_VERSION_ID < 81000 ) {
	$cfg['suppress_issue_types'][] = 'UnusedPluginSuppression';
}

return $cfg;
