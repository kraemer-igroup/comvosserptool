<?php
if (! defined('TYPO3_MODE')) {
	die('Access denied.');
}

if (version_compare(TYPO3_branch, '6.1', '<')) {
	\TYPO3\CMS\Core\Utility\GeneralUtility::loadTCA('pages');
}
$GLOBALS['TCA']['pages']['columns']['description']['config']['wizards'] = array(
	'SERPWizard' => array(
		'type' => 'userFunc',
		'userFunc' => 'EXT:' . $_EXTKEY . '/Classes/tx_comvosserptool_SERPWizard.php:tx_comvosserptool_SERPWizard->SERPWizard',
		'params' => array(
			'emptyValue' => 'No description'
		)
	)
);

$GLOBALS['TCA']['pages']['columns']['title']['config']['wizards']['SERPWizard'] = $GLOBALS['TCA']['pages']['columns']['description']['config']['wizards']['SERPWizard'];
$GLOBALS['TCA']['pages']['columns']['title']['config']['wizards']['SERPWizard']['params'] = array(
	'previewSelector' => '.srs-title',
	'maxLength' => 70,
	'emptyValue' => 'No title'
);

$GLOBALS['TCA']['pages']['columns']['tx_seo_titletag']['config']['wizards']['SERPWizard'] = $GLOBALS['TCA']['pages']['columns']['title']['config']['wizards']['SERPWizard'];
$GLOBALS['TCA']['pages']['columns']['tx_seo_titletag']['config']['wizards']['SERPWizard']['params']['fallbackFieldSelector'] = 'input[name*=\\\[title\\\]]';
?>