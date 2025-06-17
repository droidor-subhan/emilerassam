<?php
class ModelLocalisationCountry extends Model {
	public function getCountry($country_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "country WHERE country_id = '" . (int)$country_id . "' AND status = '1'");

		return $query->row;
	}

	public function getCountries() {
		// $country_data = $this->cache->get('country.catalog');

		// if (!$country_data) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "country WHERE status = '1' ORDER BY name ASC");

			$country_data = $query->rows;

			$this->cache->set('country.catalog', $country_data);
		// }

		return $country_data;
	}


	public function getCountriesAndMarkets() {
		$sql = $this->db->query("SELECT ".DB_PREFIX."country.*, ".DB_PREFIX."markets.code  FROM ".DB_PREFIX."country  LEFT JOIN ( SELECT * FROM ".DB_PREFIX."markets WHERE ".DB_PREFIX."markets.status = 1 ) AS ".DB_PREFIX."markets  ON ".DB_PREFIX."country.market_id = ".DB_PREFIX."markets.markets_id  WHERE ".DB_PREFIX."country.status = 1 ORDER BY ".DB_PREFIX."country.name ASC")->rows;
		return $sql;
	}
}