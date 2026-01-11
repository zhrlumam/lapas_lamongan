<?php
$conn = new mysqli('127.0.0.1', 'root', '', 'test');
if ($conn->connect_error) {
    die("Error: " . $conn->connect_error);
}

echo "=== TABLES IN DATABASE 'test' ===\n\n";
$result = $conn->query("SHOW TABLES");
while ($row = $result->fetch_array()) {
    echo "- " . $row[0] . "\n";
}
$conn->close();
