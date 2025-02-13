<?php

namespace SYRADEV\RtSimpleosm\Hooks;

/***
 *
 * This file is part of the "Simple OpenStreetMap" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2021 Regis TEDONE <regis.tedone@gmail.com>, SYRADEV
 *
 ***/

use SYRADEV\RtSimpleosm\Domain\Repository\OsmRepository;
use TYPO3\CMS\Backend\View\PageLayoutView;
use TYPO3\CMS\Backend\View\PageLayoutViewDrawItemHookInterface;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/**
 * Hook to render preview widget of custom content elements in page module
 * @see \TYPO3\CMS\Backend\View\PageLayoutView::tt_content_drawItem()
 */
class PageLayoutViewDrawItemHook implements PageLayoutViewDrawItemHookInterface {

	/**
	 * @var string $osm_table : table storing OpenStreetMaps
	 */
	private $osm_table = 'tx_rtsimpleosm_domain_model_osm';

	/**
	 * @var string $beosm_template Simple OSM backend template
	 */
	private $beosm_template = 'EXT:rt_simpleosm/Resources/Private/Templates/Backend/Rtsimpleosm-Plugin-BackendTemplate.html';

	/**
	 * osmRepository
	 * @var OsmRepository $osmRepository
	 */
	protected $osmRepository;

	/**
	 * Rendering for custom content elements
	 *
	 * @param PageLayoutView $parentObject
	 * @param bool $drawItem
	 * @param string $headerContent
	 * @param string $itemContent
	 * @param array $row
	 */
	public function preProcess( PageLayoutView &$parentObject, &$drawItem, &$headerContent, &$itemContent, array &$row ): void {

		$plugins = [ 'rtsimpleosm_sosm' ];

		if ( ! in_array( $row['list_type'], $plugins ) ) {
			return;
		}

		$drawItem = false;

		$noHeaderFound = LocalizationUtility::translate( 'LLL:EXT:rt_simpleosm/Resources/Private/Language/locallang_be.xlf:simpleosm.backend.noHeaderFound');

		$title         = empty( $row['header'] ) ? '[ ' . $noHeaderFound . ' ]' : $row['header'] . ' ' . LocalizationUtility::translate( 'LLL:EXT:rt_simpleosm/Resources/Private/Language/locallang_be.xlf:simpleosm.backend.headerLayout.' . $row['header_layout']);
		$headerContent = '<strong>' . htmlspecialchars( $title ) . '</strong><br />';

		// If no flexform data is provided, prevent to go further
		if ( !$row['pi_flexform'] ) {
			return;
		} else {
			$flexform = $this->cleanUpArray( GeneralUtility::xml2array( $row['pi_flexform'] ), array(
				'data',
				'lDEF',
				'vDEF'
			) );
		}

		$flex = [];

		// Get extension configuration
		$extConf = GeneralUtility::makeInstance( \TYPO3\CMS\Core\Configuration\ExtensionConfiguration::class )->get( 'rt_simpleosm' );

		switch ( $extConf['backendPluginInterfaceBackground'] ) {
			case '0':
				$flex['background_style'] = 'background: url(\'' . $extConf['backendPluginInterfaceBackgroundImage'] . '\')';
				break;

			case '1':
				$flex['background_style'] = 'background-color: ' . $extConf['backendPluginInterfaceBackgroundColor'];
				break;
		}

		$flex['contents'] = [];

		//****************************
		// Plugin Simple OSM
		//****************************
		$fluidTmplFilePath    = GeneralUtility::getFileAbsFileName( $this->beosm_template );
		$flex['plugin_icon']  = 'rt_simpleosm-plugin-sosm';
		$flex['plugin_title'] = LocalizationUtility::translate( 'LLL:EXT:rt_simpleosm/Resources/Private/Language/locallang_db.xlf:tx_rt_simpleosm_sosm.name');

		$flex['contents']['markers'] = [];

		if ( empty( $flexform['mapselection']['settings.MapRecord'] ) ) {
			$flex['contents']['selectAMapRecord'] = '<h4><i class="fa fa-warning" style="color:#FF0000;"></i>&nbsp;' . LocalizationUtility::translate( 'LLL:EXT:rt_simpleosm/Resources/Private/Language/locallang_be.xlf:simpleosm.message.selectAMapRecord') . '</h4>';
		} else {
			// Get Map records
			preg_match_all( '/tx_rtsimpleosm_domain_model_osm_(\d+),?/', $flexform['mapselection']['settings.MapRecord'], $mapRecords );
			$mapRecordIds = array_map( 'intval', $mapRecords[1] );
			// Get all OSM Objects with those IDs
			$objectManager = GeneralUtility::makeInstance( ObjectManager::class );
			$this->osmRepository = $objectManager->get( OsmRepository::class );
			$markersOsm = array_map( array( $this->osmRepository, "findByUid" ), $mapRecordIds );

			$markersTtAddress = [];
			if(ExtensionManagementUtility::isLoaded('tt_address')) {
				// Get tt_address records IDs
				preg_match_all( '/tt_address_(\d+),?/', $flexform['mapselection']['settings.MapRecord'], $ttAddressRecords );
				// Get all tt_address Objects with those IDs
				$markersTtAddress = $this->osmRepository->findByTtAddressUid($ttAddressRecords[1]);
			}

			// Remove empty markers (in case findByUid returns null)
			$selectedMarkers = array_filter( array_merge($markersOsm, $markersTtAddress) );

			if(!empty($selectedMarkers)) {
				$markers = [];
				foreach ( $selectedMarkers as $selectedMarker ) {
					$markers[] = [
						'icon' => PathUtility::getAbsoluteWebPath(GeneralUtility::getFileAbsFileName($GLOBALS['TCA']['tx_rtsimpleosm_domain_model_osm']['columns']['markericon']['config']['items'][$selectedMarker->getMarkericon()][2])),
						'uid' => $selectedMarker->getUid(),
						'title' => $selectedMarker->getTitle(),
						'latitude' => '<strong><em>' . LocalizationUtility::translate('LLL:EXT:rt_simpleosm/Resources/Private/Language/locallang_db.xlf:tx_rtsimpleosm_domain_model_osm.latitude') . '</em></strong>: ' . $selectedMarker->getLatitude() . '.<br />',
						'longitude' => '<strong><em>' . LocalizationUtility::translate('LLL:EXT:rt_simpleosm/Resources/Private/Language/locallang_db.xlf:tx_rtsimpleosm_domain_model_osm.longitude') . '</em></strong>: ' . $selectedMarker->getLongitude() . '.<br />',
						'address' => '<strong><em>' . LocalizationUtility::translate('LLL:EXT:rt_simpleosm/Resources/Private/Language/locallang_db.xlf:tx_rtsimpleosm_domain_model_osm.address') . '</em></strong>: ' . $selectedMarker->getAddress() . '.<br />',
					];
				}
				$flex['contents']['markers'] = $markers;
			} else {
				$flex['contents']['noValidMapRecordSelected'] =  '<h4><i class="fa fa-warning" style="color:#FF0000;"></i>&nbsp;' . LocalizationUtility::translate( 'LLL:EXT:rt_simpleosm/Resources/Private/Language/locallang.xlf:map.label.noValidMapRecordSelected') . '</h4>';
			}
		}

		if ( !empty( $flexform['styling']['settings.MapStyle'] || $flexform['styling']['settings.MapStyle'] === '0') ) {
			$flex['contents']['mapStyleImg']   = '../typo3conf/ext/rt_simpleosm/Resources/Public/maps/map_' . $flexform['styling']['settings.MapStyle'] . '.png';
			$flex['contents']['mapStyleLabel'] = '<strong><em>' . LocalizationUtility::translate( 'LLL:EXT:rt_simpleosm/Resources/Private/Language/locallang_db.xlf:tx_rt_simpleosm_sosm.mapStyle') . '</em></strong>: ' . LocalizationUtility::translate( 'LLL:EXT:rt_simpleosm/Resources/Private/Language/locallang_db.xlf:tx_rt_simpleosm_sosm.mapStyle.' . $flexform['styling']['settings.MapStyle']) . '.';
		}

		if ( !empty( $flexform['styling']['settings.MapWidth'] ) ) {
			$flex['contents']['mapWidth'] = '<strong><em>' . LocalizationUtility::translate( 'LLL:EXT:rt_simpleosm/Resources/Private/Language/locallang_db.xlf:tx_rt_simpleosm_sosm.mapWidth') . '</em></strong>: ' . $flexform['styling']['settings.MapWidth'] . '.<br />';
		}

		if ( !empty( $flexform['styling']['settings.MapHeight'] ) ) {
			$flex['contents']['mapHeight'] = '<strong><em>' . LocalizationUtility::translate( 'LLL:EXT:rt_simpleosm/Resources/Private/Language/locallang_db.xlf:tx_rt_simpleosm_sosm.mapHeight') . '</em></strong>: ' . $flexform['styling']['settings.MapHeight'] . '.<br />';
		}

		if ( !empty( $flexform['styling']['settings.BorderRadiusMap'] ) ) {
			$flex['contents']['borderRadiusMap'] = '<strong><em>' . LocalizationUtility::translate( 'LLL:EXT:rt_simpleosm/Resources/Private/Language/locallang_db.xlf:tx_rt_simpleosm_sosm.borderRadiusMap') . '</em></strong>: ' . $flexform['styling']['settings.BorderRadiusMap'] . '.<br />';
		}

		if ( !empty( $flexform['options']['settings.Zoom'] ) ) {
			$flex['contents']['zoom'] = '<strong><em>' . LocalizationUtility::translate( 'LLL:EXT:rt_simpleosm/Resources/Private/Language/locallang_db.xlf:tx_rt_simpleosm_sosm.zoom') . '</em></strong>: ' . $flexform['options']['settings.Zoom'] . '.<br />';
		}

		if ( !empty( $flexform['options']['settings.PopupOptions'] ) ) {
			$flex['contents']['popupOptions'] = '<strong><em>' . LocalizationUtility::translate( 'LLL:EXT:rt_simpleosm/Resources/Private/Language/locallang_db.xlf:tx_rt_simpleosm_sosm.popupOptions') . '</em></strong>: ' . LocalizationUtility::translate( 'LLL:EXT:rt_simpleosm/Resources/Private/Language/locallang_db.xlf:tx_rt_simpleosm_sosm.popupOptions.' . $flexform['options']['settings.PopupOptions']) . '.<br />';
		}

		if ( !empty( $flexform['options']['settings.ScrollWheelZoom'] ) ) {
			$flex['contents']['scrollWheelZoom'] = '<strong><em>' . LocalizationUtility::translate( 'LLL:EXT:rt_simpleosm/Resources/Private/Language/locallang_db.xlf:tx_rt_simpleosm_sosm.scrollWheelZoom') . '</em></strong>: ' . $flexform['options']['settings.ScrollWheelZoom'] . '.<br />';
		}

		if ( !empty( $flexform['options']['settings.DisplayZoomButtons'] ) ) {
			$flex['contents']['displayZoomButtons'] = '<strong><em>' . LocalizationUtility::translate( 'LLL:EXT:rt_simpleosm/Resources/Private/Language/locallang_db.xlf:tx_rt_simpleosm_sosm.displayZoomButtons') . '</em></strong>: ' . $flexform['options']['settings.DisplayZoomButtons'] . '.<br />';
		}

		if ( !empty( $flexform['options']['settings.DisplayFullScreenButton'] ) ) {
			$flex['contents']['displayFullScreenButton'] = '<strong><em>' . LocalizationUtility::translate( 'LLL:EXT:rt_simpleosm/Resources/Private/Language/locallang_db.xlf:tx_rt_simpleosm_sosm.displayFullScreenButton') . '</em></strong>: ' . $flexform['options']['settings.DisplayFullScreenButton'] . '.<br />';
		}

		if ( !empty( $flexform['options']['settings.DisplayMiniMap'] ) ) {
			$flex['contents']['displayMiniMap'] = '<strong><em>' . LocalizationUtility::translate( 'LLL:EXT:rt_simpleosm/Resources/Private/Language/locallang_db.xlf:tx_rt_simpleosm_sosm.displayMiniMap') . '</em></strong>: ' . $flexform['options']['settings.DisplayMiniMap'] . '.<br />';
		}

		if ( !empty( $flexform['options']['settings.DisplayCaptionMenu'] ) ) {
			$flex['contents']['displayCaptionMenu'] = '<strong><em>' . LocalizationUtility::translate( 'LLL:EXT:rt_simpleosm/Resources/Private/Language/locallang_db.xlf:tx_rt_simpleosm_sosm.displayCaptionMenu') . '</em></strong>: ' . $flexform['options']['settings.DisplayCaptionMenu'] . '.<br />';
		}

		// HTML Template loading
		$fluidTmpl = GeneralUtility::makeInstance( 'TYPO3\CMS\Fluid\View\StandaloneView' );
		$fluidTmpl->setTemplatePathAndFilename( $fluidTmplFilePath );
		$fluidTmpl->assign( 'flex', $flex );

		// Render final content
		$itemContent = $parentObject->linkEditContent( $fluidTmpl->render(), $row );
	}

	/**
	 * @param array $cleanUpArray
	 * @param array $notAllowed
	 *
	 * @return array|mixed
	 */
	protected function cleanUpArray( array $cleanUpArray, array $notAllowed ) {
		$cleanArray = array();
		foreach ( $cleanUpArray as $key => $value ) {
			if ( in_array( $key, $notAllowed ) ) {
				return is_array( $value ) ? $this->cleanUpArray( $value, $notAllowed ) : $value;
			} else if ( is_array( $value ) ) {
				$cleanArray[ $key ] = $this->cleanUpArray( $value, $notAllowed );
			}
		}

		return $cleanArray;
	}
}
