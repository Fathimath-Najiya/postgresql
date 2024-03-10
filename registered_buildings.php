<?php
// buildings.php
$host = "localhost";
$port = "5432";
$dbname = "evacuation";
$user = "postgres";
$password = "RootAdmin";

$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

if (!$conn) {
    die("Connection failed: " . pg_last_error());
}


if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Fetch buildings
    $query = "SELECT id, name FROM buildings WHERE owner_id = :ownerId";
    $statement = $pdo->prepare($query);
    $statement->execute(['ownerId' => $ownerId]); // Change ownerId to the actual owner's ID
    $buildings = $statement->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($buildings);
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Delete building
    $buildingId = $_GET['id']; // Get the building ID from the request

    // Example query to delete the building
    $query = "DELETE FROM buildings WHERE id = :id";
    $statement = $pdo->prepare($query);
    $statement->execute(['id' => $buildingId]);

    $response = ['success' => true];
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    // Invalid request method
    $response = ['success' => false, 'message' => 'Invalid request method'];
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
