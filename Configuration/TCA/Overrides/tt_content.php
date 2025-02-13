<?php
declare(strict_types=1);

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') || die();

// Register plugin
ExtensionUtility::registerPlugin(
	'RtSimpleosm',
	'Sosm',
	'Simple OSM',
    'rt_simpleosm-plugin-sosm'
);

$pluginSignature = str_replace( '_', '', 'rt_simpleosm' ) . '_sosm';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignature] = 'layout,select_key,pages,recursive';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';

$flexformXml = ExtensionManagementUtility::isLoaded('tt_address')
    ? 'FILE:EXT:rt_simpleosm/Configuration/FlexForms/flexform_simpleosm_tt_address.xml'
    : 'FILE:EXT:rt_simpleosm/Configuration/FlexForms/flexform_simpleosm.xml';
ExtensionManagementUtility::addPiFlexFormValue($pluginSignature, $flexformXml);
