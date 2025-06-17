<?php
// Database connection parameters
$host = 'localhost';
$username = 'emilerassam';
$password = 'emilerassam';
$database = 'emilerassam';

// Create database connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process CSV file upload
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["file"])) {
    $file = $_FILES["file"]["tmp_name"];

    // Read CSV file
    if (($handle = fopen($file, "r")) !== FALSE) {
        // Skip header row
        fgetcsv($handle);

        // Array to store existing countries
        $existingCountries = array();

        // Loop through each row in the CSV file
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $countryName = $data[2];
            $isoCode2 = $data[0];
            $isoCode3 = $data[1];
            $city = $data[3];
            $cityCode = $data[4];
            $country_code_status = $data[5];
            $city_status = $data[6];

            // Check if country already exists in the array
            if (array_key_exists($isoCode2, $existingCountries)) {
                // Country already exists, get the existing country ID from the array
                $countryId = $existingCountries[$isoCode2];
            } else {
                // Insert country into oc_country table
                $countryInsertQuery = "INSERT INTO oc_country (name, iso_code_2, iso_code_3, status) VALUES ('$countryName', '$isoCode2', '$isoCode3','$country_code_status')";
                $conn->query($countryInsertQuery);

                // Get the newly inserted country ID
                $countryId = $conn->insert_id;

                // Add the country to the array
                $existingCountries[$isoCode2] = $countryId;
            }

            if( $country_code_status == 0 ) {
                $city_status = 0;
            }

            // Insert city into oc_zone table
            $cityInsertQuery = "INSERT INTO oc_zone (country_id, name, code, status) VALUES ('$countryId', '$city', '$cityCode', '$city_status')";
            $conn->query($cityInsertQuery);
        }

        fclose($handle);
    }

    echo "Upload successful!";
} else {
    echo "Invalid request!";
}

// Close the database connection
$conn->close();
?>
