<?php

// Assuming you have a MySQL connection already established
$host = 'localhost';
$username = 'emilerassam';
$password = 'emilerassam';
$database = 'emilerassam';

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to get iso_code_3 from API
function getIsoAlpha3FromApi($iso_code_2)
{
    $apiUrl = "https://restcountries.com/v3/alpha/$iso_code_2";
    $response = @file_get_contents($apiUrl);
    $data = json_decode($response, true);

    if (isset($data[0]['cca3'])) {
        return $data[0]['cca3'];
    }

    return '';
}

// Fetch records from the table
$sql = "SELECT country_id, iso_code_2 FROM oc_country where iso_code_3 = '' ";
$result = $conn->query($sql);

// Check if the query was successful
if (!$result) {
    echo "Error fetching records: " . $conn->error;
} elseif ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $id = $row['country_id'];
        $iso_code_2 = $row['iso_code_2'];

        // Get iso_code_3 from API
        $iso_code_3 = getIsoAlpha3FromApi($iso_code_2);

        // Update iso_code_3 in the table
        $updateSql = "UPDATE oc_country SET iso_code_3 = '$iso_code_3' WHERE country_id = $id";
        $updateResult = $conn->query($updateSql);

        if (!$updateResult) {
            echo "Error updating record: " . $conn->error;
        }
    }
} else {
    echo "No records found in the table.";
}

// Close the connection
$conn->close();

?>
