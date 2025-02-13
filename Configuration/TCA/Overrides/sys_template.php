<?php
declare(strict_types=1);

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') || die();

// Load Extension Typoscipt Configuration
ExtensionManagementUtility::addStaticFile('rt_simpleosm', 'Configuration/TypoScript', 'Simple OpenStreetMap');
