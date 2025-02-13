<?php
defined('TYPO3') || die();

return [
    'ctrl' => [
        'title' => 'LLL:EXT:rt_simpleosm/Resources/Private/Language/locallang_db.xlf:tx_rtsimpleosm_domain_model_osm',
        'label' => 'title',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'versioningWS' => true,
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'searchFields' => 'title,address,latitude,longitude',
        'security' => [
            'ignorePageTypeRestriction' => true
        ],
        'iconfile' => 'EXT:rt_simpleosm/Resources/Public/Icons/tx_rtsimpleosm_domain_model_osm.png'
    ],
    'types' => [
        '1' => ['showitem' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, title, address, helper, latitude, longitude, markericon, --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access, starttime, endtime'],
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.language',
            'config' => ['type' => 'language'],
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'default' => 0,
                'items' => [
                    ['label' => '', 'value' => 0],
                ],
                'foreign_table' => 'tx_rtsimpleosm_domain_model_osm',
                'foreign_table_where' => 'AND tx_rtsimpleosm_domain_model_osm.pid=###CURRENT_PID### AND tx_rtsimpleosm_domain_model_osm.sys_language_uid IN (-1,0)',
            ],
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        't3ver_label' => [
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.versionLabel',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 255,
            ],
        ],
        'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.visible',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        'label' => '',
                        'invertStateDisplay' => true,
                    ],
                ],
            ],
        ],
        'starttime' => [
            'exclude' => true,
            'behaviour' => [
                'allowLanguageSynchronization' => true
            ],
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.starttime',
            'config' => [
                'type' => 'datetime',
                'size' => 13,
                'default' => 0,
            ],
        ],
        'endtime' => [
            'exclude' => true,
            'behaviour' => [
                'allowLanguageSynchronization' => true
            ],
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.endtime',
            'config' => [
                'type' => 'datetime',
                'size' => 13,
                'default' => 0,
                'range' => [
                    'upper' => mktime(0, 0, 0, 1, 1, 2038)
                ],
            ],
        ],

        'title' => [
            'exclude' => false,
            'label' => 'LLL:EXT:rt_simpleosm/Resources/Private/Language/locallang_db.xlf:tx_rtsimpleosm_domain_model_osm.title',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'required' => true
            ],
        ],
        'address' => [
            'exclude' => false,
            'label' => 'LLL:EXT:rt_simpleosm/Resources/Private/Language/locallang_db.xlf:tx_rtsimpleosm_domain_model_osm.address',
            'config' => [
                'type' => 'text',
                'cols' => 20,
                'rows' => 8,
                'eval' => 'trim'
            ]
        ],
        'helper' => [
	        'label' => 'LLL:EXT:rt_simpleosm/Resources/Private/Language/locallang_be.xlf:simpleosm.message.getLatLong.title',
	        'config' => [
		        'type' => 'user',
                'renderType' => 'rtGPSCoordinates',
		        'parameters' => [
			        'color' => '#9ed284'
		        ]
	        ]
        ],
        'latitude' => [
            'exclude' => false,
            'label' => 'LLL:EXT:rt_simpleosm/Resources/Private/Language/locallang_db.xlf:tx_rtsimpleosm_domain_model_osm.latitude',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'required' => true
            ],
        ],
        'longitude' => [
            'exclude' => false,
            'label' => 'LLL:EXT:rt_simpleosm/Resources/Private/Language/locallang_db.xlf:tx_rtsimpleosm_domain_model_osm.longitude',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'required' => true
            ],
        ],
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
			    'showIconTable' => 1,
			    'fieldWizard' => [
					'selectIcons' => [
						'disabled' => 0
					]
			     ],
			     'default' => 0
		    ]
	    ]
    ],
];
