<?php

/***************************************************************
 * Extension Manager/Repository config file for ext: "rt_simpleosm"
 *
 * Auto generated by Extension Builder 2019-03-01
 *
 * Manual updates:
 * Only the data in the array - anything else is removed by next write.
 * "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = [
    'title' => 'Simple OpenStreetMap',
    'description' => 'Insert a simple OpenStreetMap. No API key required!',
    'category' => 'plugin',
    'author' => 'Regis TEDONE',
    'author_email' => 'regis.tedone@gmail.com',
    'state' => 'stable',
    'uploadfolder' => 0,
    'createDirs' => '',
    'clearCacheOnLoad' => 0,
    'version' => '2.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '7.6.0-9.5.99',
        ],
        'conflicts' => [],
        'suggests' => [
	        'tt_address' => '3.0.0-4.99.99',
        ],
    ],
];
