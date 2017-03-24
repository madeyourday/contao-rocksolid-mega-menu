<?php
/*
 * Copyright MADE/YOUR/DAY OG <mail@madeyourday.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MadeYourDay\RockSolidMegaMenu;

/**
 * RockSolid Mega Menu Runonce
 *
 * @author Martin Auswöger <martin@madeyourday.net>
 */
abstract class Runonce
{
	/**
	 * Run database migrations
	 *
	 * @return void
	 */
	public static function run()
	{
		$database = \Database::getInstance();

		// Copy license key from extension repository
		if (
			!\Config::get('rocksolid_mega_menu_license')
			&& $database->tableExists('tl_repository_installs')
			&& $database->fieldExists('lickey', 'tl_repository_installs')
			&& $database->fieldExists('extension', 'tl_repository_installs')
		) {
			$result = $database->prepare('SELECT lickey FROM tl_repository_installs WHERE extension = \'rocksolid-mega-menu\'')->execute();
			if ($result && MegaMenu::checkLicense((string)$result->lickey)) {
				\Config::getInstance()->add(
					'$GLOBALS[\'TL_CONFIG\'][\'rocksolid_mega_menu_license\']',
					(string)$result->lickey
				);
			}
		}
	}
}
