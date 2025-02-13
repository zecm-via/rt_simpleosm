<?php

use SYRADEV\RtSimpleosm\Controller\OsmController;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') || die('Access denied.');

// configure plugin
ExtensionUtility::configurePlugin(
    'RtSimpleosm',
    'Sosm',
    [
        OsmController::class => 'displayMap'
    ],
    // non-cacheable actions
    [
        OsmController::class => ''
    ]
);

// Page module hook for backend plugin display
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['cms/layout/class.tx_cms_layout.php']['tt_content_drawItem']['Rtsimpleosm'] = 'SYRADEV\RtSimpleosm\Hooks\PageLayoutViewDrawItemHook';

$GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry']['1609276343'] = [
    'nodeName' => 'rtGPSCoordinates',
    'priority' => 40,
    'class' => \SYRADEV\RtSimpleosm\FormElements\RtGPSCoodinates::class
];
$GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry']['1609276344'] = [
    'nodeName' => 'rtCopyrigthsLogo',
    'priority' => 40,
    'class' => \SYRADEV\RtSimpleosm\FormElements\RtCopyrigthsLogo::class
];
