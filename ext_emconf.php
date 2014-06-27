<?php

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Preview API for Extbase plugins',
	'description' => '',
	'category' => 'plugin',
	'author' => 'Jose Antonio Guerra',
	'author_email' => 'jaguerra@icti.es',
	'author_company' => 'ICTI Internet Passion S.L.',
	'shy' => '',
	'priority' => '',
	'module' => '',
	'state' => 'stable',
	'internal' => '',
	'uploadfolder' => '1',
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 1,
	'lockType' => '',
	'version' => '0.0.0-dev',
	'constraints' => array(
    'depends' => array(
      'cms' => '6.1.0-6.1.99',
			'extbase' => '6.1.0-6.1.99',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
);

?>