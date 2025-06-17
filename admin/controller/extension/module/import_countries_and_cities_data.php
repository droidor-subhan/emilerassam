<?php
class ControllerExtensionModuleImportCountriesAndCitiesData extends Controller
{
    public function install(){
        $this->load->model('user/user_group');
        $user_group_id = 1;
        $permissions = array(
            'access' => 1,
            'modify' => 1
        );
        $this->model_user_user_group->addPermission($user_group_id, 'access', $permissions);
        $this->model_user_user_group->addPermission($user_group_id, 'modify', $permissions);
    }

    public function index() {
        $this->load->language('extension/module/import_countries_and_cities_data');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('setting/setting');
        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            $this->model_setting_setting->editSetting('importCountriesAndCitiesData', $this->request->post);
            $this->session->data['success'] = $this->language->get('text_success');
            $this->response->redirect($this->url->link('extension/module/import_countries_and_cities_data', 'user_token=' . $this->session->data['user_token'], true));
        }
        $data['heading_title'] = $this->language->get('heading_title');
        $data['breadcrumbs'] = array();
        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true),
        );
        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_extension'),
            'href'      => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true),
        );
        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('extension/module/import_countries_and_cities_data', 'user_token=' . $this->session->data['user_token'], true),
        );
        $data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], true);
        $data['action'] = $this->url->link('extension/module/import_countries_and_cities_data/uploadCSV', 'user_token=' . $this->session->data['user_token'], true);
        
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );
        $this->response->setOutput($this->load->view('extension/module/import_countries_and_cities_data', $data));
    }

    public function uploadCSV() {
        if ($this->request->files['file']['error'] == UPLOAD_ERR_OK) {
            $file = $this->request->files['file']['tmp_name'];
            $this->parseCSV($file);
        }
    }

    protected function parseCSV($file) {
        if (($handle = fopen($file, 'r')) !== FALSE) {
            fgetcsv($handle);

            $existingCountries = array();
            
            $this->db->query('TRUNCATE oc_country');
            $this->db->query('TRUNCATE oc_zone');
            
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $countryName = $data[2];
                $isoCode2 = $data[0];
                $isoCode3 = $data[1];
                $city = $data[3];
                $cityCode = $data[4];
                $country_code_status = $data[5];
                $city_status = $data[6];

                if (array_key_exists($isoCode2, $existingCountries)) {
                    $countryId = $existingCountries[$isoCode2];
                } else {
                    $countryInsertQuery = "INSERT INTO oc_country (name, iso_code_2, iso_code_3, status) VALUES ('".$this->db->escape($countryName)."', '".$this->db->escape($isoCode2)."', '".$this->db->escape($isoCode3)."', '".$this->db->escape($country_code_status)."')";
                    $this->db->query($countryInsertQuery);
                    
                    $countryId = $this->db->getLastId();
                    $existingCountries[$isoCode2] = $countryId;
                }

                $cityInsertQuery = "INSERT INTO oc_zone (country_id, name, code, status) VALUES ('".$this->db->escape($countryId)."', '".$this->db->escape($city)."', '".$this->db->escape($cityCode)."', '".$this->db->escape($city_status)."')";
                $this->db->query($cityInsertQuery);
            }
            fclose($handle);
        }

        $sql = "SELECT country_id, iso_code_2 FROM " . DB_PREFIX . "country WHERE iso_code_3 = ''";
        $query = $this->db->query($sql);

        $country_ios_3 = array(
            "AD" => 'AND', "AE" => "ARE", "AG" => "ATG", "AI" => "AIA", "AL" => "ALB", "AM" => "ARM", "AO" => "AGO", "AR" => "ARG", "AS" => "ASM", "AT" => "AUT", "AU" => "AUS", "AW" => "ABW", "AZ" => "AZE", "BA" => "BIH", "BB" => "BRB", "BD" => "BGD", "BE" => "BEL", "BF" => "BFA", "BG" => "BGR", "BH" => "BHR", "BI" => "BDI", "BJ" => "BEN", "BM" => "BMU", "BN" => "BRN", "BO" => "BOL", "BR" => "BRA", "BS" => "BHS", "BT" => "BTN", "BW" => "BWA", "BY" => "BLR", "BZ" => "BLZ", "CA" => "CAN", "CD" => "COD", "CF" => "CAF", "CG" => "COG", "CH" => "CHE", "CI" => "CIV", "CK" => "COK", "CL" => "CHL", "CM" => "CMR", "CN" => "CHN", "CO" => "COL", "CR" => "CRI", "CU" => "CUB", "CV" => "CPV", "CY" => "CYP", "CZ" => "CZE", "DE" => "DEU", "DJ" => "DJI", "DK" => "DNK", "KE" => "KEN", "KG" => "KGZ", "KH" => "KHM", "KI" => "KIR", "KM" => "COM", "KN" => "KNA", "KP" => "PRK", "KR" => "KOR", "KV" => "KV", "KW" => "KWT", "KY" => "CYM", "KZ" => "KAZ", "LA" => "LAO", "LB" => "LBN", "LC" => "LCA", "LI" => "LIE", "LK" => "LKA", "LR" => "LBR", "LS" => "LSO", "LT" => "LTU", "LU" => "LUX", "LV" => "LVA", "LY" => "LBY", "MA" => "MAR", "MD" => "MDA", "ME" => "MNE", "MG" => "MDG", "MH" => "MHL", "MK" => "MKD", "ML" => "MLI", "MM" => "MMR", "MN" => "MNG", "MO" => "MAC", "MP" => "MNP", "MQ" => "MTQ", "MR" => "MRT", "MS" => "MSR", "MT" => "MLT", "MU" => "MUS", "MV" => "MDV", "MW" => "MWI", "MX" => "MEX", "MY" => "MYS", "MZ" => "MOZ", "NA" => "NAM", "NC" => "NCL", "NE" => "NER", "NG" => "NGA", "NI" => "NIC", "NL" => "NLD", "NO" => "NOR", "NP" => "NPL", "NR" => "NRU", "NU" => "NIU", "NZ" => "NZL", "OM" => "OMN", "PA" => "PAN", "PE" => "PER", "PF" => "PYF", "PG" => "PNG", "PH" => "PHL", "PK" => "PAK", "PL" => "POL", "PR" => "PRI", "PT" => "PRT", "PW" => "PLW", "PY" => "PRY", "QA" => "QAT", "RE" => "REU", "RO" => "ROU", "RS" => "SRB", "RU" => "RUS", "RW" => "RWA", "SA" => "SAU", "SB" => "SLB", "SC" => "SYC", "SD" => "SDN", "SE" => "SWE", "SG" => "SGP", "SI" => "SVN", "SK" => "SVK", "SL" => "SLE", "SM" => "SMR", "SN" => "SEN", "SO" => "SOM", "SR" => "SUR", "SS" => "SSD", "SV" => "SLV", "SY" => "SYR", "SZ" => "SWZ", "TC" => "TCA", "TD" => "TCD", "TG" => "TGO", "TH" => "THA", "TJ" => "TJK", "TL" => "TLS", "TM" => "TKM", "TN" => "TUN", "TO" => "TON", "TR" => "TUR", "DM" => "DMA", "DO" => "DOM", "DZ" => "DZA", "EC" => "ECU", "EE" => "EST", "EG" => "EGY", "ER" => "ERI", "ES" => "ESP", "ET" => "ETH", "FI" => "FIN", "FJ" => "FJI", "FK" => "FLK", "FM" => "FSM", "FO" => "FRO", "FR" => "FRA", "GA" => "GAB", "GB" => "GBR", "GD" => "GRD", "GE" => "GEO", "GF" => "GUF", "GG" => "GGY", "GH" => "GHA", "GL" => "GRL", "GM" => "GMB", "GN" => "GIN", "GP" => "GLP", "GQ" => "GNQ", "GR" => "GRC", "GT" => "GTM", "GU" => "GUM", "GW" => "GNB", "GY" => "GUY", "HK" => "HKG", "HN" => "HND", "HR" => "HRV", "HT" => "HTI", "HU" => "HUN", "IC" => "IC", "ID" => "IDN", "IE" => "IRL", "IL" => "ISR", "IN" => "IND", "IQ" => "IRQ", "IR" => "IRN", "IS" => "ISL", "IT" => "ITA", "JE" => "JEY", "JM" => "JAM", "JO" => "JOR", "JP" => "JPN", "IC" => "IC", "KV" => "KV", "TT" => "TTO", "TV" => "TUV", "TW" => "TWN", "TZ" => "TZA", "UA" => "UKR", "UG" => "UGA", "US" => "USA", "UY" => "URY", "UZ" => "UZB", "VC" => "VCT", "VE" => "VEN", "VG" => "VGB", "VI" => "VIR", "VN" => "VNM", "VU" => "VUT", "WS" => "WSM", "XB" => "XB", "XC" => "XC", "XM" => "XM", "XS" => "XS", "XY" => "XY", "YE" => "YEM", "YT" => "MYT", "ZA" => "ZAF", "ZM" => "ZMB",
        );

        foreach ($query->rows as $row) {
            $id = $row['country_id'];
            $iso_code_2 = $row['iso_code_2'];
            
            // $iso_code_3 = $this->getIsoAlpha3FromApi($iso_code_2);
            // $updateSql = "UPDATE oc_country SET iso_code_3 = '$iso_code_3' WHERE country_id = $id";
            // $this->db->query($updateSql);

            if (array_key_exists($iso_code_2, $country_ios_3)) {
                $iso_code_3 = $country_ios_3[$iso_code_2];
                $updateSql = "UPDATE oc_country SET iso_code_3 = '$iso_code_3' WHERE country_id = $id";
                $this->db->query($updateSql);
            }
        }

        $this->session->data['success'] = "Countries and Cities/Zones data have been uploaded successfully";
        $this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], true));
    }

    protected function getIsoAlpha3FromApi($iso_code_2) {
        $apiUrl = "https://restcountries.com/v3/alpha/$iso_code_2";
        $response = @file_get_contents($apiUrl);
        $data = @json_decode($response, true);

        if (isset($data[0]['cca3'])) {
            return $data[0]['cca3'];
        }
        return '';
    }
}