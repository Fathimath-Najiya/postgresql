<?php
// Start session
session_start();

// Establish database connection
$host = "localhost";
$port = "5432";
$dbname = "evacuation";
$user = "postgres";
$password = "RootAdmin";

$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

if (!$conn) {
    die("Database connection failed: " . pg_last_error());
}

// Function to handle file upload
function uploadFile($fileInputName, $index) {
    $targetDirectory = "uploads/";
    
    $targetFile = $targetDirectory . basename($_FILES[$fileInputName]["name"][$index]);

    if (move_uploaded_file($_FILES[$fileInputName]["tmp_name"][$index], $targetFile)) {
        return $targetFile;
    } else {
        return false;
    }
}

// Handle form submission
if(!empty($_POST))  {
    // Check if owner is logged in
    if (isset($_SESSION['owner_id'])) {
        // Get owner ID from session
        $owner_id = $_SESSION['owner_id'];

        // Get form data
        $building_name = $_POST["building_name"];
        $building_address = $_POST["building_address"];

        // Upload floor plan and graph files
        $floorPlanFiles = array();
        $graphFiles = array();
     
        foreach ($_FILES['floorPlan']['name'] as $key => $value) {
            $floorPlanFile = uploadFile("floorPlan", $key);
            $graphFile = uploadFile("graph", $key);

            if ($floorPlanFile && $graphFile) {
                $floorPlanFiles[] = $floorPlanFile;
                $graphFiles[] = $graphFile;
            } else {
                die("Error uploading files.");
            }
        }

        // Insert building data into database
        $sql = "INSERT INTO buildings (owner_id, building_name, building_address, floor_plan, graph) VALUES ($1, $2, $3, $4::bytea[], $5::bytea[])";
        $result = pg_query_params($conn, $sql, array($owner_id, $building_name, $building_address, $floorPlanFiles, $graphFiles));
        if ($result) {
            $response = array('success' => true, 'message' => 'Building registered successfully');
        } else {
            $response = array('success' => false, 'message' => 'Error registering building: ' . pg_last_error($conn));
        }
    } else {
        // Owner not logged in, handle accordingly
        $response = array('success' => false, 'message' => 'Owner not logged in.');
    }
    // Send JSON response back to client
    header('Content-Type: application/json');
    echo json_encode($response);
}

// Close database connection
pg_close($conn);
?>
