<?php
defined('TYPO3') || die('Access denied.');

call_user_func(
	function() {
		if (TYPO3_MODE === 'BE') {
			\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr( 'tx_rtsimpleosm_domain_model_osm', 'EXT:rt_simpleosm/Resources/Private/Language/locallang_csh_tx_rtsimpleosm_domain_model_osm.xlf' );
		}
	}
);
