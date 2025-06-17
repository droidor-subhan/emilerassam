<?php
class ModelLocalisationZone extends Model {
	public function getZone($zone_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone WHERE zone_id = '" . (int)$zone_id . "' AND status = '1'");

		return $query->row;
	}

	public function getZonesByCountryId($country_id) {
		$zone_data = $this->cache->get('zone.' . (int)$country_id);

		if (!$zone_data) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone WHERE country_id = '" . (int)$country_id . "' AND status = '1' ORDER BY name");

			$zone_data = $query->rows;

			$this->cache->set('zone.' . (int)$country_id, $zone_data);
		}

		return $zone_data;
	}

	public function getZonesByCountryIdAndSearchedKeyWord($country_id, $searched_keyword) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone WHERE country_id = '" . (int)$country_id . "' AND status = '1' AND name like '%".$searched_keyword."%' ORDER BY name");
		$zone_data_country_id_Searched_lkeyword = $query->rows;
		return $zone_data_country_id_Searched_lkeyword;
	}
}