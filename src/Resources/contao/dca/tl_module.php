<?php
/*
 * Copyright MADE/YOUR/DAY OG <mail@madeyourday.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Mega Menu Frontend Module DCA
 *
 * @author Martin Auswöger <martin@madeyourday.net>
 */

$GLOBALS['TL_DCA']['tl_module']['palettes']['rocksolid_mega_menu'] = '{title_legend},name,headline,type;{nav_legend},levelOffset,showLevel,hardLimit,showProtected;{reference_legend:hide},defineRoot;{template_legend:hide},navigationTpl,rsmm_template;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID';
$GLOBALS['TL_DCA']['tl_module']['palettes']['rocksolid_mega_menu_custom'] = '{title_legend},name,headline,type;{nav_legend},pages,showProtected;{template_legend:hide},navigationTpl,rsmm_template;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID';

$GLOBALS['TL_DCA']['tl_module']['fields']['navigationTpl']['eval']['tl_class'] = 'w50';
$GLOBALS['TL_DCA']['tl_module']['fields']['rsmm_template'] = array(
	'label' => &$GLOBALS['TL_LANG']['tl_module']['rsmm_template'],
	'exclude' => true,
	'inputType' => 'select',
	'options_callback' => array('MadeYourDay\\RockSolidMegaMenu\\MegaMenu', 'getTemplates'),
	'eval' => array('tl_class' => 'w50'),
	'sql' => "varchar(64) NOT NULL default ''"
);
