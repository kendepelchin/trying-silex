<?php
namespace Vinyl\Services\Repository;

use Knp\Repository;

/**
 * Our Service model to handle our service database entity
 *
 * @implements Knp\Repository
 * @author  Ken Depelchin <ken.depelchin@gmail.com>
 */
class ServiceModel extends Repository {
	public function getTableName() {
		return 'services';
	}
}
