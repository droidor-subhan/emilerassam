<?php
$filename = 'DHL_Provided_RAW_Data_file.txt';
$outputCsvFile = 'Generated_CSV_From_DHL_Provided_RAW_Data_file.csv';
$countryCities = [];

$file = fopen($filename, 'r');

if ($file) {
    // Initialize a lookup array to track unique entries based on ISO3, city, and city code
    $uniqueEntries = [];
    
    while (($line = fgets($file)) !== false) {
        $fields = explode('|', $line);

        // Extract Country Code ISO2 from the 0th index
        $countryCodeISO2 = $fields[0];

        // Get ISO 3166-1 alpha-3 code from REST Countries API
        $countryCodeISO3 = ""; // Replace with your logic if needed

        // Extract country name from the 1st index
        $countryName = $fields[1];

        // Extract city from the 4th index
        $city = $fields[4];

        // Extract city code from the 5th index
        $cityCode = $fields[5];

        $country_code_status = 1;
        $city_status = 1;
        
        // Check if the country-city combination already exists
        if (!isset($uniqueEntries[$countryCodeISO3][$city])) {
            $uniqueEntries[$countryCodeISO3][$city] = true;
            echo $cityCode;
            echo "<br />";
            // Store the data in the result array
            $countryCities[] = [
                'Country Code ISO2' => $countryCodeISO2,
                'Country Code ISO3' => $countryCodeISO3,
                'Country Name' => $countryName,
                'City' => $city,
                'City Code' => $cityCode,
                'country_code_status' => $country_code_status,
                'city_status' => $city_status,
            ];
        }
    }
    die;
    fclose($file);

    // Export to CSV file
    $csvFile = fopen($outputCsvFile, 'w');
    if ($csvFile) {
        fputcsv($csvFile, ['Country Code ISO2', 'Country Code ISO3', 'Country Name', 'City', 'City Code','country_code_status','city_status']); // Write header

        // Write country codes, country names, cities, and city codes to the CSV file
        foreach ($countryCities as $data) {
            fputcsv($csvFile, [
                $data['Country Code ISO2'],
                $data['Country Code ISO3'],
                $data['Country Name'],
                $data['City'],
                $data['City Code'],
                $data['country_code_status'],
                $data['city_status']
            ]);
        }

        fclose($csvFile);
        echo "Country codes, names, cities, and city codes exported to $outputCsvFile\n";
    } else {
        echo "Error creating CSV file.";
    }
} else {
    echo "Error opening file.";
}

// Function to get ISO 3166-1 alpha-3 code from REST Countries API
function getIsoAlpha3FromApi($isoAlpha2)
{
    $apiUrl = "https://restcountries.com/v3/alpha/$isoAlpha2";
    $response = file_get_contents($apiUrl);
    $data = json_decode($response, true);

    if (isset($data[0]['cca3'])) {
        return $data[0]['cca3'];
    }

    return '';
}
?>
