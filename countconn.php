<?php
// SQLite database file
$database_file = "counter.db";

try {
    // Create a new SQLite3 database connection
    $db = new SQLite3($database_file);

    if (!$db) {
        // If connection fails, handle the error
        throw new Exception("Failed to connect to SQLite database.");
    }
} catch (Exception $e) {
    // Handle any exceptions that occurred during the connection
    echo "Error: " . $e->getMessage();
}
?>
