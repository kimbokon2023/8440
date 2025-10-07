<?php

header('Content-Type: application/json');

// Read the JSON data from race_data.json file
$json_data = file_get_contents('race_data.json');

// Convert the JSON data back to an associative array
// $raceData = json_decode($json_data, true);

print_r($json_data);

// Check if raceData is an array and has the 'players' key
    // Extract player data from the 'players' key
    $players = $raceData['players'];

    // Create an array to hold race times
    $raceTimes = [];

    // Loop through each player's data
    foreach ($players as $playerKey => $playerData) {
        $raceTimes[] = [
            $playerKey . '_user_name' => $playerData['user_name'],
            $playerKey . '_raceTime' => $playerData['raceTime'],
            $playerKey . '_Position' => $playerData['position']
        ];
    }

    // Add additional data to the race times array
    $raceTimes[] = [
        'startsign' => $raceData['startsign']        
    ];

    // Output the race times as JSON
   //  echo json_encode($raceTimes);
   //   echo json_encode($raceData, JSON_UNESCAPED_UNICODE);

?>
