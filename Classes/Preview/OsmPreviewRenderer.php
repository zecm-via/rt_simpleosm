<?php

declare(strict_types=1);

namespace SYRADEV\RtSimpleosm\Preview;

use SYRADEV\RtSimpleosm\Domain\Repository\OsmRepository;
use TYPO3\CMS\Backend\Preview\StandardContentPreviewRenderer;
use TYPO3\CMS\Backend\View\BackendLayout\Grid\GridColumnItem;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;

/**
 * Renderer to render preview widget of custom content elements in page module
 * @see \TYPO3\CMS\Backend\Preview\PreviewRendererInterface;
 */
class OsmPreviewRenderer extends StandardContentPreviewRenderer
{
    /**
     * @var string $beosm_template Simple OSM backend template
     */
    private $beosm_template = 'EXT:rt_simpleosm/Resources/Private/Templates/Backend/Rtsimpleosm-Plugin-BackendTemplate.html';

    /**
     * osmRepository
     * @var OsmRepository $osmRepository
     */
    protected $osmRepository;

    public function __construct()
    {
        $this->osmRepository = GeneralUtility::makeInstance(OsmRepository::class);
    }

    public function renderPageModulePreviewContent(GridColumnItem $item): string
    {
        $row = $item->getRecord();

        // If no flexform data is provided, prevent to go further
        if (!$row['pi_flexform']) {
            return '';
        }

        $pi_flexform = GeneralUtility::xml2array($row['pi_flexform']);

        // If parsing failed, return early with the error message
        if (is_string($pi_flexform)){
            return $pi_flexform;
        }

        $flexform = $this->cleanUpArray($pi_flexform, ['data', 'lDEF', 'vDEF']);
        $flex = [];

        // Get extension configuration
        $extConf = GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('rt_simpleosm');

        switch ($extConf['backendPluginInterfaceBackground']) {
            case '0':
                $flex['background_style'] = 'background-image: url(\'' . PathUtility::getAbsoluteWebPath(GeneralUtility::getFileAbsFileName($extConf['backendPluginInterfaceBackgroundImage'])) . '\')';
                break;

            case '1':
                $flex['background_style'] = 'background-color: ' . $extConf['backendPluginInterfaceBackgroundColor'];
                break;
        }

        $flex['contents'] = [];

        //****************************
        // Plugin Simple OSM
        //****************************
        $fluidTmplFilePath = GeneralUtility::getFileAbsFileName($this->beosm_template);
        $flex['contents']['markers'] = [];

        if (!empty($flexform['mapselection']['settings.MapRecord'])) {
            // Get Map records
            preg_match_all('/tx_rtsimpleosm_domain_model_osm_(\d+),?/', $flexform['mapselection']['settings.MapRecord'],
                $mapRecords);
            $mapRecordIds = array_map('intval', $mapRecords[1]);
            // Get all OSM Objects with those IDs
            $markersOsm = array_map(array($this->osmRepository, "findByUid"), $mapRecordIds);

            $markersTtAddress = [];
            if (ExtensionManagementUtility::isLoaded('tt_address')) {
                // Get tt_address records IDs
                preg_match_all('/tt_address_(\d+),?/', $flexform['mapselection']['settings.MapRecord'],
                    $ttAddressRecords);
                // Get all tt_address Objects with those IDs
                $markersTtAddress = $this->osmRepository->findByTtAddressUid($ttAddressRecords[1]);
            }

            // Remove empty markers (in case findByUid returns null)
            $selectedMarkers = array_filter(array_merge($markersOsm, $markersTtAddress));

            if (!empty($selectedMarkers)) {
                $markers = [];
                foreach ($selectedMarkers as $selectedMarker) {
                    $markers[] = [
                        'icon' => $GLOBALS['TCA']['tx_rtsimpleosm_domain_model_osm']['columns']['markericon']['config']['items'][$selectedMarker->getMarkericon()]['icon'],
                        'uid' => $selectedMarker->getUid(),
                        'title' => $selectedMarker->getTitle(),
                        'attributes' => [
                            'address' => $selectedMarker->getAddress(),
                            'latitude' => $selectedMarker->getLatitude(),
                            'longitude' => $selectedMarker->getLongitude(),
                        ]
                    ];
                }
                $flex['contents']['markers'] = $markers;
            }
        }

        if (isset($flexform['styling']['settings.MapStyle'])) {
            $flex['contents']['mapStyle'] = $flexform['styling']['settings.MapStyle'];
        }

        if (!empty($flexform['styling']['settings.MapWidth'])) {
            $flex['contents']['styling']['MapWidth'] = $flexform['styling']['settings.MapWidth'];
        }

        if (!empty($flexform['styling']['settings.MapHeight'])) {
            $flex['contents']['styling']['mapHeight'] = $flexform['styling']['settings.MapHeight'];
        }

        if (!empty($flexform['styling']['settings.BorderRadiusMap'])) {
            $flex['contents']['styling']['borderRadiusMap'] = $flexform['styling']['settings.BorderRadiusMap'];
        }

        if (!empty($flexform['options']['settings.Zoom'])) {
            $flex['contents']['options']['zoom'] = $flexform['options']['settings.Zoom'];
        }

        if (!empty($flexform['options']['settings.PopupOptions'])) {
            $flex['contents']['options']['popupOptions'] = LocalizationUtility::translate('LLL:EXT:rt_simpleosm/Resources/Private/Language/locallang_db.xlf:tx_rt_simpleosm_sosm.popupOptions.' . $flexform['options']['settings.PopupOptions']);
        }

        if (!empty($flexform['options']['settings.ScrollWheelZoom'])) {
            $flex['contents']['options']['scrollWheelZoom'] = $this->getTextForBoolean($flexform['options']['settings.ScrollWheelZoom']);
        }

        if (!empty($flexform['options']['settings.DisplayZoomButtons'])) {
            $flex['contents']['options']['displayZoomButtons'] = $this->getTextForBoolean($flexform['options']['settings.DisplayZoomButtons']);
        }

        if (!empty($flexform['options']['settings.DisplayFullScreenButton'])) {
            $flex['contents']['options']['displayFullScreenButton'] = $this->getTextForBoolean($flexform['options']['settings.DisplayFullScreenButton']);
        }

        if (!empty($flexform['options']['settings.DisplayMiniMap'])) {
            $flex['contents']['options']['displayMiniMap'] = $this->getTextForBoolean($flexform['options']['settings.DisplayMiniMap']);
        }

        if (!empty($flexform['options']['settings.DisplayCaptionMenu'])) {
            $flex['contents']['options']['displayCaptionMenu'] = $this->getTextForBoolean($flexform['options']['settings.DisplayCaptionMenu']);
        }

        // HTML Template loading
        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $view->setTemplatePathAndFilename($fluidTmplFilePath);
        $view->assign('flex', $flex);

        // Render final content
        return $view->render();
    }

    /**
     * @param array $cleanUpArray
     * @param array $notAllowed
     *
     * @return array|mixed
     */
    protected function cleanUpArray(array $cleanUpArray, array $notAllowed)
    {
        $cleanArray = array();
        foreach ($cleanUpArray as $key => $value) {
            if (in_array($key, $notAllowed)) {
                return is_array($value) ? $this->cleanUpArray($value, $notAllowed) : $value;
            } else {
                if (is_array($value)) {
                    $cleanArray[$key] = $this->cleanUpArray($value, $notAllowed);
                }
            }
        }

        return $cleanArray;
    }

    /**
     * Returns a translated text ("activated" or "deactivated") based on the provided value
     * @param $value
     * @return string
     */
    protected function getTextForBoolean($value): string
    {
        return LocalizationUtility::translate(
            'LLL:EXT:core/Resources/Private/Language/locallang_common.xlf:' .
            (($value) ? 'enabled' : 'disabled')
        );
    }
}
