<?php
declare(strict_types=1);

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') || die();

if(ExtensionManagementUtility::isLoaded('tt_address')) {
	/**
	 * TCA additional column for tt_address
	 */
	$tempColumns = [
		'markericon' => [
			'exclude' => false,
			'label' => 'LLL:EXT:rt_simpleosm/Resources/Private/Language/locallang_db.xlf:tx_rt_simpleosm_sosm.markerIcon',
			'config' => [
				'type' => 'select',
				'renderType' => 'selectSingle',
				'items' => [
                    [
                        'label' => 'LLL:EXT:rt_simpleosm/Resources/Private/Language/locallang_db.xlf:tx_rt_simpleosm_sosm.markerIcon.0',
                        'value' => 0,
                        'icon' => 'EXT:rt_simpleosm/Resources/Public/Icons/marker-icon.png',
                        'group' => 'EXT:rt_simpleosm/Resources/Public/Icons/marker-shadow.svg'
                    ],
                    [
                        'label' => 'LLL:EXT:rt_simpleosm/Resources/Private/Language/locallang_db.xlf:tx_rt_simpleosm_sosm.markerIcon.1',
                        'value' => 1,
                        'icon' => 'EXT:rt_simpleosm/Resources/Public/Icons/map-marker-black.svg',
                        'group' => 'EXT:rt_simpleosm/Resources/Public/Icons/marker-shadow.svg'
                    ],
                    [
                        'label' => 'LLL:EXT:rt_simpleosm/Resources/Private/Language/locallang_db.xlf:tx_rt_simpleosm_sosm.markerIcon.2',
                        'value' => 2,
                        'icon' => 'EXT:rt_simpleosm/Resources/Public/Icons/map-marker-blue.svg',
                        'group' => 'EXT:rt_simpleosm/Resources/Public/Icons/marker-shadow.svg'
                    ],
                    [
                        'label' => 'LLL:EXT:rt_simpleosm/Resources/Private/Language/locallang_db.xlf:tx_rt_simpleosm_sosm.markerIcon.3',
                        'value' => 3,
                        'icon' => 'EXT:rt_simpleosm/Resources/Public/Icons/map-marker-deepblue.svg',
                        'group' => 'EXT:rt_simpleosm/Resources/Public/Icons/marker-shadow.svg'
                    ],
                    [
                        'label' => 'LLL:EXT:rt_simpleosm/Resources/Private/Language/locallang_db.xlf:tx_rt_simpleosm_sosm.markerIcon.4',
                        'value' => 4,
                        'icon' => 'EXT:rt_simpleosm/Resources/Public/Icons/map-marker-green.svg',
                        'group' => 'EXT:rt_simpleosm/Resources/Public/Icons/marker-shadow.svg'
                    ],
                    [
                        'label' => 'LLL:EXT:rt_simpleosm/Resources/Private/Language/locallang_db.xlf:tx_rt_simpleosm_sosm.markerIcon.5',
                        'value' => 5,
                        'icon' => 'EXT:rt_simpleosm/Resources/Public/Icons/map-marker-fuchsia.svg',
                        'group' => 'EXT:rt_simpleosm/Resources/Public/Icons/marker-shadow.svg'
                    ],
                    [
                        'label' => 'LLL:EXT:rt_simpleosm/Resources/Private/Language/locallang_db.xlf:tx_rt_simpleosm_sosm.markerIcon.6',
                        'value' => 6,
                        'icon' => 'EXT:rt_simpleosm/Resources/Public/Icons/map-marker-orange.svg',
                        'group' => 'EXT:rt_simpleosm/Resources/Public/Icons/marker-shadow.svg'
                    ],
                    [
                        'label' => 'LLL:EXT:rt_simpleosm/Resources/Private/Language/locallang_db.xlf:tx_rt_simpleosm_sosm.markerIcon.7',
                        'value' => 7,
                        'icon' => 'EXT:rt_simpleosm/Resources/Public/Icons/map-marker-purple.svg',
                        'group' => 'EXT:rt_simpleosm/Resources/Public/Icons/marker-shadow.svg'
                    ],
                    [
                        'label' => 'LLL:EXT:rt_simpleosm/Resources/Private/Language/locallang_db.xlf:tx_rt_simpleosm_sosm.markerIcon.8',
                        'value' => 8,
                        'icon' => 'EXT:rt_simpleosm/Resources/Public/Icons/map-marker-red.svg',
                        'group' => 'EXT:rt_simpleosm/Resources/Public/Icons/marker-shadow.svg'
                    ],
                    [
                        'label' => 'LLL:EXT:rt_simpleosm/Resources/Private/Language/locallang_db.xlf:tx_rt_simpleosm_sosm.markerIcon.9',
                        'value' => 9,
                        'icon' => 'EXT:rt_simpleosm/Resources/Public/Icons/map-marker-yellow.svg',
                        'group' => 'EXT:rt_simpleosm/Resources/Public/Icons/marker-shadow.svg'
                    ],
                    [
                        'label' => 'LLL:EXT:rt_simpleosm/Resources/Private/Language/locallang_db.xlf:tx_rt_simpleosm_sosm.markerIcon.10',
                        'value' => 10,
                        'icon' => 'EXT:rt_simpleosm/Resources/Public/Icons/map-pin-black.svg',
                        'group' => 'EXT:rt_simpleosm/Resources/Public/Icons/pin-shadow.svg'
                    ],
                    [
                        'label' => 'LLL:EXT:rt_simpleosm/Resources/Private/Language/locallang_db.xlf:tx_rt_simpleosm_sosm.markerIcon.11',
                        'value' => 11,
                        'icon' => 'EXT:rt_simpleosm/Resources/Public/Icons/map-pin-blue.svg',
                        'group' => 'EXT:rt_simpleosm/Resources/Public/Icons/pin-shadow.svg'
                    ],
                    [
                        'label' => 'LLL:EXT:rt_simpleosm/Resources/Private/Language/locallang_db.xlf:tx_rt_simpleosm_sosm.markerIcon.12',
                        'value' => 12,
                        'icon' => 'EXT:rt_simpleosm/Resources/Public/Icons/map-pin-deepblue.svg',
                        'group' => 'EXT:rt_simpleosm/Resources/Public/Icons/pin-shadow.svg'
                    ],
                    [
                        'label' => 'LLL:EXT:rt_simpleosm/Resources/Private/Language/locallang_db.xlf:tx_rt_simpleosm_sosm.markerIcon.13',
                        'value' => 13,
                        'icon' => 'EXT:rt_simpleosm/Resources/Public/Icons/map-pin-green.svg',
                        'group' => 'EXT:rt_simpleosm/Resources/Public/Icons/pin-shadow.svg'
                    ],
                    [
                        'label' => 'LLL:EXT:rt_simpleosm/Resources/Private/Language/locallang_db.xlf:tx_rt_simpleosm_sosm.markerIcon.14',
                        'value' => 14,
                        'icon' => 'EXT:rt_simpleosm/Resources/Public/Icons/map-pin-fuchsia.svg',
                        'group' => 'EXT:rt_simpleosm/Resources/Public/Icons/pin-shadow.svg'
                    ],
                    [
                        'label' => 'LLL:EXT:rt_simpleosm/Resources/Private/Language/locallang_db.xlf:tx_rt_simpleosm_sosm.markerIcon.15',
                        'value' => 15,
                        'icon' => 'EXT:rt_simpleosm/Resources/Public/Icons/map-pin-orange.svg',
                        'group' => 'EXT:rt_simpleosm/Resources/Public/Icons/pin-shadow.svg'
                    ],
                    [
                        'label' => 'LLL:EXT:rt_simpleosm/Resources/Private/Language/locallang_db.xlf:tx_rt_simpleosm_sosm.markerIcon.16',
                        'value' => 16,
                        'icon' => 'EXT:rt_simpleosm/Resources/Public/Icons/map-pin-purple.svg',
                        'group' => 'EXT:rt_simpleosm/Resources/Public/Icons/pin-shadow.svg'
                    ],
                    [
                        'label' => 'LLL:EXT:rt_simpleosm/Resources/Private/Language/locallang_db.xlf:tx_rt_simpleosm_sosm.markerIcon.17',
                        'value' => 17,
                        'icon' => 'EXT:rt_simpleosm/Resources/Public/Icons/map-pin-red.svg',
                        'group' => 'EXT:rt_simpleosm/Resources/Public/Icons/pin-shadow.svg'
                    ],
                    [
                        'label' => 'LLL:EXT:rt_simpleosm/Resources/Private/Language/locallang_db.xlf:tx_rt_simpleosm_sosm.markerIcon.18',
                        'value' => 18,
                        'icon' => 'EXT:rt_simpleosm/Resources/Public/Icons/map-pin-yellow.svg',
                        'group' => 'EXT:rt_simpleosm/Resources/Public/Icons/pin-shadow.svg'
                    ]
                ],
				'fieldWizard' => [
					'selectIcons' => [
						'disabled' => 0
					]
				],
				'default' => 0
			]
		]
	];

	ExtensionManagementUtility::addTCAcolumns('tt_address', $tempColumns);
	ExtensionManagementUtility::addToAllTCAtypes('tt_address', 'markericon;;;;1-1-1', '',  'after:longitude');
}
