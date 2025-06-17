<?php
class ModelLocalisationMarket extends Model {
	public function addMarket($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "markets SET code = '" . $this->db->escape($data['name']) . "', currency_id = '" . $this->db->escape($data['currency']) . "', status = '" . $this->db->escape($data['status']) . "' ");
		return $this->db->getLastId();
	}

	public function editLocation($markets_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "markets SET code = '" . $this->db->escape($data['name']) . "', currency_id = '" . $this->db->escape($data['currency']) . "', status = '" . $this->db->escape($data['status']) . "'  WHERE markets_id = '" . (int)$markets_id . "'");
	}

    public function deleteMarkets($market_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "markets WHERE markets_id = " . (int)$market_id);
	}

    public function deleteLocation($location_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "location WHERE location_id = " . (int)$location_id);
	}

	public function getLocation($location_id) {
        $sql = "SELECT ".DB_PREFIX."markets.*, ".DB_PREFIX."currency.title FROM ".DB_PREFIX."markets INNER JOIN ".DB_PREFIX."currency ON ".DB_PREFIX."currency.currency_id = ".DB_PREFIX."markets.currency_id  WHERE markets_id = '" . (int)$location_id . "'";
        $query = $this->db->query($sql);

		return $query->row;
	}

	public function getMarkets($data = array()) {
		$sql = "SELECT ".DB_PREFIX."markets.*, ".DB_PREFIX."currency.title FROM ".DB_PREFIX."markets INNER JOIN ".DB_PREFIX."currency ON ".DB_PREFIX."currency.currency_id = ".DB_PREFIX."markets.currency_id";
        
		$sort_data = array(
			'code',
			'address',
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY " . DB_PREFIX . "markets.code";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);
		return $query->rows;
	}

	public function getTotalMarkets() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "markets");

		return $query->row['total'];
	}
}
