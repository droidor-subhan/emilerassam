<?php
class ModelSettingExtension extends Model {
	public function getExtensions($type) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension WHERE `type` = '" . $this->db->escape($type) . "'");
		return $query->rows;
	}
	public function getLocationByIp(){  
		$client_ip  = @$_SERVER['HTTP_CLIENT_IP'];
		$forward_ip = @$_SERVER['HTTP_X_FORWARDED_FOR'];
		$remote_ip  = @$_SERVER['REMOTE_ADDR'];
		$result  = array('countryCode'=>'', 'countryName'=>'', 'region'=>'', 'city'=>'', 'geoplugin_countryCode'=>'');
		if(filter_var($client_ip, FILTER_VALIDATE_IP)){
			$ip_address = $client_ip;
		}elseif(filter_var($forward_ip, FILTER_VALIDATE_IP)){
			$ip_address = $forward_ip;
		}else{
			$ip_address = $remote_ip;
		}

		// Belgium French IP ADDRESS
		// $ip_address = '103.221.57.0';
		// Angola French IP ADDRESS
		// $ip_address = '41.223.40.0';
		// LEBANON IP ADDRESS
		// $ip_address = '91.151.226.72';
		// PAKISTAN IP ADDRESS
		// $ip_address = '139.135.36.62';
		// DENMARK IP ADDRESS
		// $ip_address = '109.238.48.0';
		// KUWAIT IP ADDRESS
		// $ip_address = '102.217.239.0';
		// Behrain IP ADDRESS
		// $ip_address = '81.22.16.0';
		// France IP ADDRESS
		// $ip_address = '91.160.93.4';
		
		// 156.146.59.47
		// https://ipinfo.io/156.146.59.47/json?token=f8289028068366

		// $loc_data = @json_decode(file_get_contents("http://ip-api.com/json/".$ip_address));    
		// echo "<pre>";
		// 	print_r($loc_data);
		// echo "</pre>";
		// $loc_data = @json_decode(file_get_contents("https://ipinfo.io/".$ip_address."/json"));
		// echo "<pre>";
		// 	print_r($loc_data);
		// echo "</pre>";

		// echo "https://apiip.net/api/check?ip=".$ip_address."&accessKey=1fa82154-5ede-40aa-9a67-ae44fcce641d";
		
		$loc_data = @json_decode(file_get_contents("https://apiip.net/api/check?ip=".$ip_address."&accessKey=1fa82154-5ede-40aa-9a67-ae44fcce641d"));
		// echo "<pre>";
		// 	print_r($loc_data);
		// echo "</pre>";

		if(isset($loc_data->countryCode) && $loc_data->countryCode != null){
			$result['countryCode'] = $loc_data->countryCode;
			$result['region'] = $loc_data->regionName;
			$result['city'] = $loc_data->city;
			$result['geoplugin_countryCode'] = $loc_data->countryCode;
			$result['countryName'] = $loc_data->countryName;
		}else{
			$result['countryCode'] = 'FR';
			$result['region'] = 'Paris';
			$result['city'] = 'Paris';
			$result['geoplugin_countryCode'] = 'FR';
			$result['countryName'] = "France";
		}



		// stdClass Object
		// (
		//     [country] => United States
		//     [countryCode] => US
		//     [regionName] => New York
		//     [city] => New York
		// )

		
		// {
		//   "ip": "156.146.59.47",
		//   "hostname": "unn-156-146-59-47.cdn77.com",
		//   "city": "New York City",
		//   "region": "New York",
		//   "country": "US",
		//   "loc": "40.7143,-74.0060",
		//   "org": "AS60068 Datacamp Limited",
		//   "postal": "10001",
		//   "timezone": "America/New_York"
		// }

		// if(isset($loc_data->country) && $loc_data->country != null){
			
		// 	$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "country WHERE `iso_code_2` = '" . $loc_data->country . "'");
			
		// 	$result['countryCode'] = $loc_data->country;
		// 	if( isset($query->row['name']) && $query->row['name'] != '' ) {
		// 		$result['countryName'] = $query->row['name'];
		// 	} else {
		// 		$result['countryName'] = $loc_data->country;	
		// 	}
			
		// 	$result['region'] = $loc_data->region;
		// 	$result['city'] = $loc_data->city;
		// 	$result['geoplugin_countryCode'] = $loc_data->country;
		// }else{
		// 	$result['countryCode'] = 'FR';
		// 	$result['region'] = 'Paris';
		// 	$result['city'] = 'Paris';
		// 	$result['geoplugin_countryCode'] = 'FR';
		// }

		// if($loc_data && $loc_data->country != null){
		// 	$result['countryCode'] = $loc_data->countryCode;
		// 	$result['countryName'] = $loc_data->country;
		// 	$result['region'] = $loc_data->regionName;
		// 	$result['city'] = $loc_data->city;
		// 	$result['geoplugin_countryCode'] = $loc_data->countryCode;
		// }else{
		// 	$result['countryCode'] = 'FR';
		// }
		return $result;
	}
}