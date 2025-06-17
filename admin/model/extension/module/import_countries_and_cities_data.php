<?php
class ModelExtensionModuleImportCountriesAndCitiesData extends Model {
    public function saveEntries($entries) {
        // foreach ($entries as $entry) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "your_table_name SET column1 = '" . $this->db->escape($entry[0]) . "', column2 = '" . $this->db->escape($entry[1]) . "'");
        // }
    }
}
