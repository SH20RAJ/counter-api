<?php
$database_file = "counter.db"; // SQLite database file

try {
    // Create a new SQLite3 database connection
    $db = new SQLite3($database_file);

    if (!$db) {
        throw new Exception("Failed to connect to SQLite database.");
    }

    // Get the path from the URL parameter
    $path = $_GET['path'] ?? '';

    // Check if the path is provided
    if (empty($path)) {
        throw new Exception("Path parameter is required.");
    }

    // Update the views count for the given path
    $update_query = "UPDATE views SET views = views + 1 WHERE path = :path";
    $stmt = $db->prepare($update_query);
    $stmt->bindParam(':path', $path);
    $stmt->execute();

    // Get the updated total views for the given path
    $total_views_query = "SELECT views FROM views WHERE path = :path";
    $stmt = $db->prepare($total_views_query);
    $stmt->bindParam(':path', $path);
    $result = $stmt->execute();
    $row = $result->fetchArray(SQLITE3_ASSOC);
    $total_views = $row['views'] ?? 0;

    // Display the updated count
    echo "Total Views for '$path' updated to: $total_views";

    // Close the database connection
    $db->close();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
