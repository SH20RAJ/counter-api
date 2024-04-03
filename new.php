<?php
// URL of the remote SQLite database file
$remote_db_url = 'https://cdn.jsdelivr.net/gh/CDNSFree2/cdnjs@54c4850675365a0c5472eb01ea2bc69d5df1db0c/chinook.db';

// Local path where the downloaded SQLite file will be saved
$local_db_file = 'path/to/save/chinook.db';

try {
    // Download the remote file to local path
    $content = file_get_contents($remote_db_url);
    if ($content === false) {
        throw new Exception("Failed to download the remote SQLite database.");
    }

    // Save the downloaded content to a local file
    $saved = file_put_contents($local_db_file, $content);
    if ($saved === false) {
        throw new Exception("Failed to save the SQLite database file locally.");
    }

    // Create a new SQLite3 database connection
    $db = new SQLite3($local_db_file);

    if (!$db) {
        throw new Exception("Failed to connect to SQLite database.");
    }

    // Now you can use $db to execute queries
    $query = "SELECT * FROM tracks LIMIT 10"; // Example query
    $result = $db->query($query);

    // Example: Fetching results
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        echo "Track: " . $row['Name'] . "<br>";
        echo "Album: " . $row['AlbumId'] . "<br>";
        echo "Artist: " . $row['ArtistId'] . "<br>";
        echo "<br>";
    }

    // Close the database connection
    $db->close();

    // Optional: Delete the local SQLite file after use
    // unlink($local_db_file);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
