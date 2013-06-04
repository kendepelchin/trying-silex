<?php

namespace Vinyl\User\Repository;

class Model extends \Knp\Repository {
	public function getTableName() {
		return 'users';
	}

	public function findUser($email) {
		return $this->db->fetchColumn('SELECT u.* FROM users AS u WHERE u.username = ?', array($email));
	}

	public function getNumLinks($id) {
		return $this->db->fetchColumn('SELECT COUNT(*) FROM links WHERE added_by = ?', array($id));
	}

	public function getLinks($id) {
		return $this->db->fetchAll('SELECT * FROM links WHERE added_by = ?', array($id));
	}

}
