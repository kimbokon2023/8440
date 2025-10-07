<?php

// Check if gridData is set
if (isset($_POST['gridData'])) {
    $data = $_POST['gridData'];

    // Define a file name
    $fileName = "gridData.json";

    // Save data to the file
    if (file_put_contents($fileName, $data)) {
        echo "Data saved successfully!";
    } else {
        echo "Failed to save data!";
    }
} else {
    echo "No data received!";
}

?>
