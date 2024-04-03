<?php
include "countconn.php";

try {

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

    // Increment the total views for the given path
    $increment_query = "UPDATE views SET timestamp = :timestamp WHERE path = :path";
    $stmt = $db->prepare($increment_query);
    $stmt->bindParam(':path', $path);
    $stmt->bindParam(':timestamp', $timestamp);
    $stmt->execute();

    // Get the total views for the given path
    $total_views_query = "SELECT COUNT(*) as total_views FROM views WHERE path = :path";
    $stmt = $db->prepare($total_views_query);
    $stmt->bindParam(':path', $path);
    $result = $stmt->execute();
    $row = $result->fetchArray(SQLITE3_ASSOC);
    $total_views = $row['total_views'] ?? 0;

    // Close the database connection
    $db->close();

    // Return the total views as JSON
    header('Content-Type: application/json');
    echo json_encode(array('total_views' => $total_views));
} catch (Exception $e) {
    // If an error occurs, return an error message as JSON
    header('Content-Type: application/json');
    echo json_encode(array('error' => $e->getMessage()));
}
?>
