<?php

declare(strict_types=1);

/*
 * This file is part of Contao.
 *
 * (c) Leo Feyer
 *
 * @license LGPL-3.0-or-later
 */

namespace MadeYourDay\RockSolidMegaMenu\Migration;

use Contao\CoreBundle\Migration\AbstractMigration;
use Contao\CoreBundle\Migration\MigrationResult;
use Contao\CoreBundle\Migration\Version500\AbstractBasicEntitiesMigration;

if (class_exists(AbstractBasicEntitiesMigration::class)) {
	class BasicEntitiesMigration extends AbstractBasicEntitiesMigration
	{
		protected function getDatabaseColumns(): array
		{
			return [
				['tl_page', 'rsmm_subtitle'],
				['tl_rocksolid_mega_menu', 'name'],
				['tl_rocksolid_mega_menu', 'html'],
				['tl_rocksolid_mega_menu_column', 'name'],
				['tl_rocksolid_mega_menu_column', 'displayName'],
				['tl_rocksolid_mega_menu_column', 'text'],
				['tl_rocksolid_mega_menu_column', 'html'],
			];
		}
	}
} else {
	class BasicEntitiesMigration extends AbstractMigration
	{
		public function shouldRun(): bool
		{
			return false;
		}

		public function run(): MigrationResult
		{
			throw new \LogicException();
		}
	}
}
