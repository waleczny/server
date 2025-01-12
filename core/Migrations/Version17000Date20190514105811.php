<?php
declare(strict_types=1);
/**
 * @copyright Copyright (c) 2019 Morris Jobke <hey@morrisjobke.de>
 *
 * @author Morris Jobke <hey@morrisjobke.de>
 *
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */


namespace OC\Core\Migrations;

use Closure;
use Doctrine\DBAL\Types\Type;
use OCP\DB\ISchemaWrapper;
use OCP\Migration\SimpleMigrationStep;
use OCP\Migration\IOutput;

class Version17000Date20190514105811 extends SimpleMigrationStep {

	/**
	 * @param IOutput $output
	 * @param Closure $schemaClosure The `\Closure` returns a `ISchemaWrapper`
	 * @param array $options
	 * @return ISchemaWrapper
	 */
	public function changeSchema(IOutput $output, Closure $schemaClosure, array $options) {
		/** @var ISchemaWrapper $schema */
		$schema = $schemaClosure();
		if(!$schema->hasTable('filecache_extended')) {
			$table = $schema->createTable('filecache_extended');
			$table->addColumn('fileid', Type::BIGINT, [
				'notnull' => true,
				'length' => 4,
				'unsigned' => true,
			]);
			$table->addColumn('metadata_etag', Type::STRING, [
				'notnull' => false,
				'length' => 40,
			]);
			$table->addColumn('creation_time', Type::BIGINT, [
				'notnull' => true,
				'length' => 20,
				'default' => 0,
			]);
			$table->addColumn('upload_time', Type::BIGINT, [
				'notnull' => true,
				'length' => 20,
				'default' => 0,
			]);
			$table->addUniqueIndex(['fileid'], 'fce_fileid_idx');
			$table->addIndex(['creation_time'], 'fce_ctime_idx');
			$table->addIndex(['upload_time'], 'fce_utime_idx');
		}

		return $schema;
	}
}
