<?php
/*
 * Copyright MADE/YOUR/DAY OG <mail@madeyourday.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MadeYourDay\RockSolidMegaMenu;

use Contao\Backend;
use Contao\Config;
use Contao\DataContainer;
use Contao\Environment;
use Contao\Image;
use Contao\Input;
use Contao\StringUtil;
use Contao\System;
use Symfony\Component\HttpFoundation\Request;

/**
 * RockSolid MegaMenu DCA
 *
 * Provide miscellaneous methods that are used by the data configuration arrays.
 *
 * @author Martin Auswöger <martin@madeyourday.net>
 */
class MegaMenu extends Backend
{
	/**
	 * Return the "toggle visibility" button
	 *
	 * @return string
	 */
	public function toggleColumnIcon($row, $href, $label, $title, $icon, $attributes)
	{
		if (strlen(Input::get('tid'))) {
			$this->toggleVisibility(Input::get('tid'), (Input::get('state') == 1));
			if (Environment::get('isAjaxRequest')) {
				exit;
			}
			$this->redirect($this->getReferer());
		}

		$href .= '&amp;id=' . Input::get('id') . '&amp;tid=' . $row['id'] . '&amp;state=' . ($row['published'] ? '' : 1);

		if (! $row['published']) {
			$icon = 'invisible.gif';
		}

		return '<a href="' . $this->addToUrl($href) . '" title="' . StringUtil::specialchars($title) . '"' . $attributes . '>' . Image::getHtml($icon, $label) . '</a> ';
	}

	/**
	 * Disable/enable a column
	 */
	public function toggleVisibility($intId, $blnVisible)
	{
		$this->createInitialVersion('tl_rocksolid_mega_menu_column', $intId);

		// Trigger the save_callback
		if (is_array($GLOBALS['TL_DCA']['tl_rocksolid_mega_menu_column']['fields']['published']['save_callback'] ?? null)) {
			foreach ($GLOBALS['TL_DCA']['tl_rocksolid_mega_menu_column']['fields']['published']['save_callback'] as $callback) {
				$this->import($callback[0]);
				$blnVisible = $this->{$callback[0]}->{$callback[1]}($blnVisible, $this);
			}
		}

		$this->Database
			->prepare("UPDATE tl_rocksolid_mega_menu_column SET tstamp=". time() .", published='" . ($blnVisible ? 1 : '') . "' WHERE id=?")
			->execute($intId);

		$this->createNewVersion('tl_rocksolid_mega_menu_column', $intId);
	}

	/**
	 * Child record callback
	 *
	 * @return string
	 */
	public function listColumns($arrRow)
	{
		return '<div class="tl_content_left">' . $arrRow['name'] . '</div>';
	}

	/**
	 * DCA Header callback
	 *
	 * Redirects to the parent menu if no columns are found
	 *
	 * @param  array          $headerFields label value pairs of header fields
	 * @param  DataContainer $dc           data container
	 * @return array
	 */
	public function headerCallback($headerFields, $dc)
	{
		$menusResultSet = $this->Database
			->prepare('SELECT * FROM ' . $GLOBALS['TL_DCA'][$dc->table]['config']['ptable'] . ' WHERE id = ?')
			->limit(1)
			->execute($dc->currentPid);

		if ($menusResultSet->numRows < 1) {
			return $headerFields;
		}

		if ($menusResultSet->type !== 'manual') {

			$columnsCount = $this->Database
				->prepare('SELECT count(*) as count FROM ' . $dc->table . ' WHERE pid = ?')
				->execute($dc->currentPid);
			if (!$columnsCount->count) {
				$this->redirect('contao?do=rocksolid_mega_menu&act=edit&id=' . $dc->currentPid . '&ref=' . Input::get('ref') . '&rt=' . System::getContainer()->get('contao.csrf.token_manager')->getDefaultTokenValue());
			}

		}

		return $headerFields;
	}

	/**
	 * Get all menus and return them as array
	 *
	 * @return array
	 */
	public function getMenuIds()
	{
		$menus = array();
		$menusResultSet = $this->Database->execute("SELECT id, name FROM tl_rocksolid_mega_menu ORDER BY name");

		while ($menusResultSet->next()) {
			$menus[$menusResultSet->id] = $menusResultSet->name;
		}

		return $menus;
	}

	/**
	 * Return all Mega Menu templates as array
	 *
	 * @return array
	 */
	public function getTemplates()
	{
		return $this->getTemplateGroup('rsmm_');
	}

	/**
	 * Redirect to license page if license key is missing
	 *
	 * @param  DataContainer $dc data container
	 * @return void
	 */
	public function dcaOnloadCallback($dc)
	{
		if (System::getContainer()->get('contao.routing.scope_matcher')->isBackendRequest(System::getContainer()->get('request_stack')->getCurrentRequest() ?? Request::create('')) && !static::checkLicense()) {
			$this->redirect('contao?do=rocksolid_mega_menu&table=tl_rocksolid_mega_menu_license&ref=' . Input::get('ref'));
		}
	}

	/**
	 * Check if the license key is valid
	 *
	 * @param  string         $value
	 * @param  DataContainer $dc
	 * @return string         value
	 */
	public function licenseSaveCallback($value, $dc)
	{
		if (!static::checkLicense($value)) {
			throw new \Exception($GLOBALS['TL_LANG']['tl_rocksolid_mega_menu_license']['invalidLicense']);
		}

		return $value;
	}

	/**
	 * Checksums of valid license keys
	 */
	static private $validLicenseChecksums = array(
		'85380d820cc50b8542cec3e51d4eac9f', '4732b9a9dd3aae401c98f63294d170a1', '22d538b507a6a0bc095e1a811f4e6f7d', '60f659e658b30dd06902953309f0fb34', '0176edfaa880a282190fd3e165317d10', 'f3d8f0ebfab6534a5a27a62ef95a6529', 'ad82206facc36748c8bc8d91c0586ca3', '8384d476c3a9d6382543d304cfd6c21d', 'f8e8f2da06997ef4c9377563789cc35c', '8ef64e085fa33b4d854b2946def8c869', '5174f9cbb4bac399c12d40660a5e3113', '41b73c9596d5eac961f2d2e96645e004', '1e51b48b566c82563da966f2ee6a7dbc', '822d2eb467c9729ed9c0541fdeae590e', '454e2484c52f396499670928667f43b2', 'e71564d3ebff2ec8636085c379b7e29d', '25bbedcf242e3a0380f59880b6d46d81', '2c1eb7b8a750988d2ec66668ec26d253', '4f2b3524fc8ec25a883cc6329081dccf', 'b9e92376c5e0fae6d335bc2ac699c5e9', 'fe7f4692ee9a6a17b902d2afef1fc384', 'cc067cd4ccd6d4b5af5a665ab59bfd43', '4797c57225b8a98ef2b8c8c97ba6e76f', 'd5ed13cf6bcca1577a7f67207b02cd97', '0b045681ff3eca3a592a7d77961c2fe8', 'dfe6840b260c9b9316febfa7c406da65', '0bf69b1dc131a8bbba1bf5807fb9cfa2', '63d833a0af1ef63d8888125a7639433e', '466331268d08c68e14f3616f8566d099', '6775eb75f3d4b3e6b2450ed15f13aa32', '5657190a80d001785e89a0ca0a40a27c', 'e77567456d818ee70771a7df84612aee', 'ce935bcbf538c212179f53bac0ab507b', '0cd0860142ebc0053337baea065bfb22', '5276e0da003cd2dfed0466088c6538fc', 'c0d482aa84f849dd4d28e0520033319b', 'ba6da4b992141b83865c34fbf6214c68', '482f1d5372ee8567029fea14a09496f7', 'af56c615f0ebc15bd32053a34783f1c9', 'cc1c33a8d5bfbd86f889e0ca3c26de6b', 'e2d95da64963873b96465bcf89f620b2', 'd2319086afa76cc40e79dd4f78bf7178', 'ce5c1dbf30c9e04de201cd5ac8ffd5f3', '2d9d84c1fd29688b898569107bf96472', '2122d3eafd546a065208d9aaa22dc4e0', '239081bf138b3266f20adf9e7b290dec', 'c8e627a9d37345cf069b7a7932c5e806', '66602424a1de4f52949fab44a995d56a', '8c2efdf01db429ebaa9556151099ba33', '4c3e8df2beee8b9a141f582a3cea4dd1', '9fda8bb2036bfbac9b218dec79eeb541', '195cab27a2e0aaeade6fdb25213e06a8', 'c7a25f2a4db2090aba98820f959ab935', '5dbeead3204e47d03a7566ab778dfbf3', '84ec8527df930916bd33c8e11a277e5f', 'da4b029288c2ca045534be801201f69f', '2c77196a51f422fe5a08fbf7b8ec8785', '603a63463eb4b6ef49481dd649d68141', '02cf5192d6b1c89a7433fee4c3d7562d', '0fe2abdb479f42e79f66b3b2eb681cdf', 'a939ca53f988ed92aca3c9406b1e6bdf', '877ff77b1fbd3f88dd4c4544d28382cd', '60362edf52603ad5913c2f83d2dda53d', '846d3be076206ae4f7a9cb96c8473fbf', '95f63730fc688b71551cfe9971bdc60d',
	);

	/**
	 * Check if the license key is valid
	 *
	 * @param  string $license license key or null to get the it from the config
	 * @return bool            true if the license key is valid
	 */
	public static function checkLicense($license = null)
	{
		if ($license === null) {
			$license = Config::get('rocksolid_mega_menu_license');
		}

		if (!$license) {
			return false;
		}

		if (in_array(md5($license), static::$validLicenseChecksums, true)) {
			return true;
		}

		return false;
	}
}
