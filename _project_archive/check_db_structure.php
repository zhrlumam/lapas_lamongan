<?php
$conn = new mysqli('127.0.0.1', 'root', '', 'lapas_lamongan');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "=== TABLES IN DATABASE ===\n";
$result = $conn->query("SHOW TABLES");
while ($row = $result->fetch_array()) {
    $table = $row[0];
    echo "\n[$table]\n";
    
    $cols = $conn->query("DESCRIBE $table");
    while ($col = $cols->fetch_assoc()) {
        echo "  - {$col['Field']} ({$col['Type']})\n";
    }
}
$conn->close();
