<?php
class ModelLocalisationCountry extends Model {
	public function addCountry($data) {

		$this->db->query("INSERT INTO " . DB_PREFIX . "country SET name = '" . $this->db->escape($data['name']) . "', market_id = '" . $this->db->escape($data['markets']) . "', iso_code_2 = '" . $this->db->escape($data['iso_code_2']) . "', iso_code_3 = '" . $this->db->escape($data['iso_code_3']) . "', address_format = '" . $this->db->escape($data['address_format']) . "', postcode_required = '" . (int)$data['postcode_required'] . "', status = '" . (int)$data['status'] . "'");

		$this->cache->delete('country');
		
		return $this->db->getLastId();
	}

	public function editCountry($country_id, $data) {


		$this->db->query("UPDATE " . DB_PREFIX . "country SET
			name = '" . $this->db->escape($data['name']) . "',
			market_id = '" . $this->db->escape($data['markets']) . "',
			language_id = '" . $this->db->escape($data['config_language']) . "',
			currency_id = '" . $this->db->escape($data['config_currency']) . "',
			iso_code_2 = '" . $this->db->escape($data['iso_code_2']) . "',
			iso_code_3 = '" . $this->db->escape($data['iso_code_3']) . "',
			address_format = '" . $this->db->escape($data['address_format']) . "',
			postcode_required = '" . (int)$data['postcode_required'] . "',
			status = '" . (int)$data['status'] . "'
			WHERE country_id = '" . (int)$country_id . "'
		");

		$this->cache->delete('country');
	}

	public function deleteCountry($country_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "country WHERE country_id = '" . (int)$country_id . "'");

		$this->cache->delete('country');
	}

	public function getCountry($country_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "country WHERE country_id = '" . (int)$country_id . "'");

		return $query->row;
	}

	public function getCountries($data = array()) {
		if ($data) {


			$sql = "SELECT ".DB_PREFIX."country.*, ".DB_PREFIX."markets.code  FROM ".DB_PREFIX."country  LEFT JOIN ( SELECT * FROM ".DB_PREFIX."markets WHERE ".DB_PREFIX."markets.status = 1 ) AS ".DB_PREFIX."markets  ON ".DB_PREFIX."country.market_id = ".DB_PREFIX."markets.markets_id  WHERE ".DB_PREFIX."country.status = 1";

			// $sql = "SELECT  ".DB_PREFIX."country.*, ".DB_PREFIX."markets.code  FROM " . DB_PREFIX . "country left JOIN ".DB_PREFIX."markets ON ".DB_PREFIX."country.market_id = ".DB_PREFIX."markets.markets_id AND ".DB_PREFIX."country.status = 1";
			
			$sort_data = array(
				'name',
				'iso_code_2',
				'iso_code_3'
			);

			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];
			} else {
				$sql .= " ORDER BY ".DB_PREFIX."country.name";
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
		} else {
			// $country_data = $this->cache->get('country.admin');

			// if (!$country_data) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "country ORDER BY name ASC");

				$country_data = $query->rows;

				$this->cache->set('country.admin', $country_data);
			// }

			return $country_data;
		}
	}

	public function getTotalCountries() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "country");

		return $query->row['total'];
	}
}