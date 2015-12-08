<?php
/*
 * *************************************************************
 * Copyright notice
 *
 * (c) 2012 comvos online medien GmbH, Nabil Saleh <saleh@comvos.de>
 * All rights reserved
 *
 * This script is part of the TYPO3 project. The TYPO3 project is
 * free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * This copyright notice MUST APPEAR in all copies of the script!
 * *************************************************************
 */
use \TYPO3\CMS\Core\Utility\GeneralUtility as GU;

/**
 *
 * @author nsaleh
 */
class tx_comvosserptool_SERPWizard {

	protected function getPostfix($PA) {
		// just for "title"-fields
		if(strpos($PA['field'], 'title') === false) {
			return array();
		}
		$pageId = intval(GU::_GP('id'));
		if($PA['table'] == 'pages') {
			$pageId = $PA['uid'];
		}
		if(!$pageId && $PA['table'] != 'pages') {
			$pageId = $PA['pid'];
		}
		$template = GU::makeInstance('TYPO3\CMS\Core\TypoScript\TemplateService');
		// do not log time-performance information
		$template->tt_track = 0;
		$template->forceTemplateParsing = 1;
		$template->init();
		// Get the root line
		$sys_page = GU::makeInstance('TYPO3\CMS\Frontend\Page\PageRepository');
		// the selected page in the BE is found
		$rootline = $sys_page->getRootLine($pageId);
		// This generates the constants/config + hierarchy info for the template.
		$template->runThroughTemplates($rootline, 0);
		$template->generateConfig();
		
		return array('postfix' => '[SPACE]- ' . $template->setup['sitetitle']);
	}


	public function SERPWizard($PA, $fObj) {
		$backgroundColor = 'white';
		if(!empty($PA['params']['color'])) {
			$backgroundColor = $PA['params']['color'];
		}
		
		$matches = array();
		preg_match('/id=\"[^"]+\"/', $PA['item'], $matches);
		$fid = str_replace(array(
			'id="',
			'"'
		), '', array_pop($matches));
		$params = array_merge($PA['params'], $this->getPostfix($PA));
		
		$counterJS = '
            jQuery(function(){
            	jQuery(\"#' . $fid . '\").searchResultSimulator(' . str_replace('[SPACE]', ' ', str_replace('"', '\\"', json_encode($params))) . ');
            });
        ';
		$js = '<script type="text/javascript">
            if(typeof jQuery == "undefined"){
                document.write("<sc"+"ript type=\"text/javascript\" src=\"/typo3conf/ext/comvosserptool/Resources/js/jquery-1.9.1.min.js\"></sc"+"ript>"
                +"<sc"+"ript type=\"text/javascript\" src=\"/typo3conf/ext/comvosserptool/Resources/js/jquery.searchresultsimulator.js\"></sc"+"ript>"
                +"<sc"+"ript type=\"text/javascript\">"
                +"jQuery.noConflict();"
                +"</sc"+"ript> ");
            }
            document.write("<sc"+"ript type=\"text/javascript\">"
            +"' . str_replace("\n", '', $counterJS) . '"
            +"</sc"+"ript> ");
</script>';
		return $js . '<div class="wizpos" data-input-id="' . $fid . '"/>';
	}
}
