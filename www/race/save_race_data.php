<?php
    $players = $_POST['players'] ?? [];

    $startsign = $_POST['startsign'] ?? '';    

    // Combine player data and additional data into a single array
    $data = [
        'players' => $players,
        'startsign' => $startsign        
    ];

    // Convert the array to JSON format
    $json_data = json_encode($data, JSON_UNESCAPED_UNICODE);

    // Write the JSON data to the file
    file_put_contents('race_data.json', $data);

?>
