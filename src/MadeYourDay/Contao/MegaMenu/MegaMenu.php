<?php
/*
 * Copyright MADE/YOUR/DAY OG <mail@madeyourday.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MadeYourDay\Contao\MegaMenu;

/**
 * RockSolid MegaMenu DCA
 *
 * Provide miscellaneous methods that are used by the data configuration arrays.
 *
 * @author Martin Auswöger <martin@madeyourday.net>
 */
class MegaMenu extends \Backend
{
	/**
	 * Return the "toggle visibility" button
	 *
	 * @return string
	 */
	public function toggleColumnIcon($row, $href, $label, $title, $icon, $attributes)
	{
		if (strlen(\Input::get('tid'))) {
			$this->toggleVisibility(\Input::get('tid'), (\Input::get('state') == 1));
			if (\Environment::get('isAjaxRequest')) {
				exit;
			}
			$this->redirect($this->getReferer());
		}

		$href .= '&amp;id=' . \Input::get('id') . '&amp;tid=' . $row['id'] . '&amp;state=' . ($row['published'] ? '' : 1);

		if (! $row['published']) {
			$icon = 'invisible.gif';
		}

		return '<a href="' . $this->addToUrl($href) . '" title="' . specialchars($title) . '"' . $attributes . '>' . $this->generateImage($icon, $label) . '</a> ';
	}

	/**
	 * Disable/enable a column
	 */
	public function toggleVisibility($intId, $blnVisible)
	{
		$this->createInitialVersion('tl_rocksolid_mega_menu_column', $intId);

		// Trigger the save_callback
		if (is_array($GLOBALS['TL_DCA']['tl_rocksolid_mega_menu_column']['fields']['published']['save_callback'])) {
			foreach ($GLOBALS['TL_DCA']['tl_rocksolid_mega_menu_column']['fields']['published']['save_callback'] as $callback) {
				$this->import($callback[0]);
				$blnVisible = $this->$callback[0]->$callback[1]($blnVisible, $this);
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
	 * @param  \DataContainer $dc           data container
	 * @return array
	 */
	public function headerCallback($headerFields, $dc)
	{
		$menusResultSet = $this->Database
			->prepare('SELECT * FROM ' . $GLOBALS['TL_DCA'][$dc->table]['config']['ptable'] . ' WHERE id = ?')
			->limit(1)
			->execute(CURRENT_ID);

		if ($menusResultSet->numRows < 1) {
			return $headerFields;
		}

		if ($menusResultSet->type !== 'manual') {

			$columnsCount = $this->Database
				->prepare('SELECT count(*) as count FROM ' . $dc->table . ' WHERE pid = ?')
				->execute(CURRENT_ID);
			if (!$columnsCount->count) {
				$this->redirect('contao/main.php?do=rocksolid_mega_menu&act=edit&id=' . CURRENT_ID . '&ref=' . \Input::get('ref') . '&rt=' . REQUEST_TOKEN);
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
}
