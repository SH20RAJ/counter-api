<?php
$database_file = "counter.db"; // SQLite database file

try {
    // Create a new SQLite3 database connection
    $db = new SQLite3($database_file);

    if (!$db) {
        throw new Exception("Failed to connect to SQLite database.");
    }

    // Create the views table if it doesn't exist
    $create_table_query = "CREATE TABLE IF NOT EXISTS views (
                                id INTEGER PRIMARY KEY,
                                path TEXT NOT NULL,
                                ip_address TEXT NOT NULL,
                                timestamp DATETIME NOT NULL,
                                UNIQUE(path, ip_address)
                            )";
    $db->exec($create_table_query);

    // Get the path from the URL parameter
    $path = $_GET['path'] ?? '';

    // Get the user's IP address
    $ip_address = $_SERVER['REMOTE_ADDR'];

    // Get the current timestamp
    $timestamp = date('Y-m-d H:i:s');

    // Insert the view into the views table
    $insert_query = "INSERT OR IGNORE INTO views (path, ip_address, timestamp) 
                     VALUES (:path, :ip_address, :timestamp)";
    $stmt = $db->prepare($insert_query);
    $stmt->bindParam(':path', $path);
    $stmt->bindParam(':ip_address', $ip_address);
    $stmt->bindParam(':timestamp', $timestamp);
    $stmt->execute();

    // Get the total views for the given path
    $total_views_query = "SELECT COUNT(*) as total_views FROM views WHERE path = :path";
    $stmt = $db->prepare($total_views_query);
    $stmt->bindParam(':path', $path);
    $result = $stmt->execute();
    $row = $result->fetchArray(SQLITE3_ASSOC);
    $total_views = $row['total_views'] ?? 0;

    // Display the count
    echo "Total Views for '$path': $total_views";

    // Close the database connection
    $db->close();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
