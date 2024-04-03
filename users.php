<?php
// Include the connection file
include 'conn.php';

// Now you can use $db to execute queries
$query = "SELECT * FROM users";
$result = $db->query($query);

// Example: Fetching results
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    echo "ID: " . $row['id'] . "<br>";
    echo "Name: " . $row['name'] . "<br>";
    echo "Email: " . $row['email'] . "<br>";
    echo "<br>";
}

// Close the database connection
$db->close();
?>
