<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSV Upload</title>
</head>
<body>
    <h2>Upload CSV</h2>
    <form action="Upload_Processing_Form_Generated_CSV_Into_Database.php" method="post" enctype="multipart/form-data">
        <label for="file">Choose CSV file:</label>
        <input type="file" name="file" id="file" accept=".csv" required>
        <br>
        <input type="submit" value="Upload">
    </form>
</body>
</html>