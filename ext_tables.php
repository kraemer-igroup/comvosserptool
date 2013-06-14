<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}



t3lib_div::loadTCA('pages');
$GLOBALS['TCA']['pages']['columns']['description']['config']['wizards'] = array(
    'SERPWizard' => array(
        'type' => 'userFunc',
        'userFunc' => 'EXT:comvosserptool/Classes/tx_comvosserptool_SERPWizard.php:tx_comvosserptool_SERPWizard->SERPWizard',
        'params' => array(
            'emptyValue' => 'No description!'
        )
        ));
$GLOBALS['TCA']['pages']['columns']['title']['config']['wizards'] = array(
    'SERPWizard' => array(
        'type' => 'userFunc',
        'userFunc' => 'EXT:comvosserptool/Classes/tx_comvosserptool_SERPWizard.php:tx_comvosserptool_SERPWizard->SERPWizard',
        'params' => array(
            'previewSelector' => '.srs-title',
            'overrideWithSelector' => 'input[name*=\\\[tx_comvosserptool_metatitle\\\]]',
            'maxLength' => 70,
            'emptyValue' => 'No title!'
        )
        ));


$tempColumns = array(
    'tx_comvosserptool_metatitle' => array(
        'exclude' => 1,
        'label' => 'LLL:EXT:comvosserptool/locallang_db.xml:pages.tx_comvosserptool_metatitle',
        'config' => array(
            'type' => 'input',
            'size' => '30',
            'wizards' => array(
                'SERPWizard' => array(
                    'type' => 'userFunc',
                    'userFunc' => 'EXT:comvosserptool/Classes/tx_comvosserptool_SERPWizard.php:tx_comvosserptool_SERPWizard->SERPWizard',
                    'params' => array(
                        'fallbackFieldSelector' =>'input[name*=\\\[title\\\]]',
                        'previewSelector' => '.srs-title',
                        'maxLength' => 70,
                        'emptyValue' => 'No title!'
                    )
                )
            )
        )
    )
);
t3lib_extMgm::addTCAcolumns("pages", $tempColumns, 1);
t3lib_extMgm::addToAllTCAtypes("pages", "tx_comvosserptool_metatitle","","before:description");
?>