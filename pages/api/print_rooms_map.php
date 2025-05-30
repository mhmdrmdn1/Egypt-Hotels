<?php
require_once '../../config/database.php';

header('Content-Type: text/plain; charset=utf-8');

$sql = "SELECT r.id as room_id, r.name as room_name, h.name as hotel_name
        FROM rooms r
        JOIN hotels h ON r.hotel_id = h.id
        ORDER BY h.name, r.name";
$stmt = $pdo->query($sql);

echo "roomsData = {\n";
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $key = addslashes($row['hotel_name']) . "|" . addslashes($row['room_name']);
    $val = $row['room_id'];
    echo "    '$key': $val,\n";
}
echo "};\n"; 