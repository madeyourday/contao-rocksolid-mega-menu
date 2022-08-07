<?php
/*
 * Copyright MADE/YOUR/DAY OG <mail@madeyourday.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MadeYourDay\RockSolidMegaMenu\Module;

use Contao\Environment;
use Contao\FrontendTemplate;
use Contao\PageModel;
use Contao\StringUtil;
use Contao\System;

/**
 * Custom Menu Frontend Module
 *
 * @author Martin Auswöger <martin@madeyourday.net>
 */
class MenuCustom extends Menu
{
	protected function compile()
	{
		global $objPage;

		$items = array();
		$groups = array('-1');

		if (System::getContainer()->get('contao.security.token_checker')->hasFrontendUser()) {
			$this->import('FrontendUser', 'User');
			$groups = $this->User->groups;
		}

		$objPages = PageModel::findPublishedRegularWithoutGuestsByIds(StringUtil::deserialize($this->pages, true));

		if ($objPages === null) {
			return;
		}

		$arrPages = array();

		if ($this->navigationTpl == '') {
			$this->navigationTpl = 'nav_default';
		}

		$objTemplate = new FrontendTemplate($this->navigationTpl);

		$objTemplate->type = get_class($this);
		$objTemplate->cssID = $this->cssID;
		$objTemplate->level = 'level_1';

		while ($objPages->next()) {
			$_groups = StringUtil::deserialize($arrPage['groups']);

			if (!$arrPage['protected'] || System::getContainer()->get('contao.security.token_checker')->isPreviewMode() || (is_array($_groups) && count(array_intersect($_groups, $groups))) || $this->showProtected) {
				$arrPages[$objPages->id] = $this->getPageData($objPages);
				if ($objPages->rsmm_enabled) {
					$arrPages[$objPages->id]['subitems'] = $this->renderNavigation($objPages->id);
				}
			}

		}

		$items = array_values($arrPages);

		// Add classes first and last
		$items[0]['class'] = trim($items[0]['class'] . ' first');
		$last = count($items) - 1;
		$items[$last]['class'] = trim($items[$last]['class'] . ' last');

		$objTemplate->items = $items;

		$this->Template->request = Environment::get('indexFreeRequest');
		$this->Template->skipId = 'skipNavigation' . $this->id;
		$this->Template->skipNavigation = StringUtil::specialchars($GLOBALS['TL_LANG']['MSC']['skipNavigation']);
		$this->Template->items = !empty($items) ? $objTemplate->parse() : '';
	}
}
