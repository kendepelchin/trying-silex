<?php

namespace Vinyl\User\Repository;

use Knp\Repository;

/**
 * Our User model to handle our user database entity
 *
 * @implements Knp\Repository
 * @author  Ken Depelchin <ken.depelchin@gmail.com>
 */
class UserModel extends Repository {
	public function getTableName() {
		return 'users';
	}

	public function findUser($email) {
		return $this->db->fetchArray('SELECT u.* FROM users AS u WHERE u.username = ?', array($email));
	}

	public function getNumLinks($id) {
		return $this->db->fetchColumn('SELECT COUNT(*) FROM links WHERE added_by = ?', array($id));
	}

	public function getLinks($id) {
		return $this->db->fetchAll('SELECT * FROM links WHERE added_by = ?', array($id));
	}
}
