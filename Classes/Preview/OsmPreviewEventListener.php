<?php

declare(strict_types=1);

namespace SYRADEV\RtSimpleosm\Preview;

use SYRADEV\RtSimpleosm\Domain\Repository\OsmRepository;
use TYPO3\CMS\Backend\View\Event\PageContentPreviewRenderingEvent;
use TYPO3\CMS\Core\Attribute\AsEventListener;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;
use TYPO3\CMS\Core\View\ViewFactoryData;
use TYPO3\CMS\Core\View\ViewFactoryInterface;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

/**
 * Renderer to render preview widget of custom content elements in page module
 */
readonly class OsmPreviewEventListener
{
    public function __construct(
        protected ViewFactoryInterface $viewFactory,
        protected OsmRepository $osmRepository,
    )
    {
    }

    #[AsEventListener]
    public function __invoke(PageContentPreviewRenderingEvent $event): void
    {
        $row = $event->getRecord();

        // Check if this is the specific content element type
        if ($event->getTable() !== 'tt_content'
            || $event->getRecordType() !== 'list'
            || $row['list_type'] !== 'rtsimpleosm_sosm'
            || !$row['pi_flexform']
        ) {
            return;
        }

        $pi_flexform = GeneralUtility::xml2array($row['pi_flexform']);

        // If parsing failed, return early with the error message
        if (is_string($pi_flexform)){
            $event->setPreviewContent($pi_flexform);
            return;
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
        $flex['contents']['markers'] = [];

        if (!empty($flexform['mapselection']['settings.MapRecord'])) {
            // Get Map records
            preg_match_all('/tx_rtsimpleosm_domain_model_osm_(\d+),?/', (string) $flexform['mapselection']['settings.MapRecord'],
                $mapRecords);
            $mapRecordIds = array_map(intval(...), $mapRecords[1]);
            // Get all OSM Objects with those IDs
            $markersOsm = array_map($this->osmRepository->findByUid(...), $mapRecordIds);

            $markersTtAddress = [];
            if (ExtensionManagementUtility::isLoaded('tt_address')) {
                // Get tt_address records IDs
                preg_match_all('/tt_address_(\d+),?/', (string) $flexform['mapselection']['settings.MapRecord'],
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
        $viewFactoryData = new ViewFactoryData(
            templateRootPaths: ['EXT:rt_simpleosm/Resources/Private/Templates/Backend'],
            layoutRootPaths: ['EXT:rt_simpleosm/Resources/Private/Layouts'],
        );
        $view = $this->viewFactory->create($viewFactoryData);
        $view->assign('flex', $flex);

        // Render final content
        $event->setPreviewContent(
            $view->render('Rtsimpleosm-Plugin-BackendTemplate.html')
        );
    }

    /**
     * @param array $cleanUpArray
     * @param array $notAllowed
     *
     * @return array|mixed
     */
    protected function cleanUpArray(array $cleanUpArray, array $notAllowed)
    {
        $cleanArray = [];
        foreach ($cleanUpArray as $key => $value) {
            if (in_array($key, $notAllowed)) {
                return is_array($value) ? $this->cleanUpArray($value, $notAllowed) : $value;
            } elseif (is_array($value)) {
                $cleanArray[$key] = $this->cleanUpArray($value, $notAllowed);
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
