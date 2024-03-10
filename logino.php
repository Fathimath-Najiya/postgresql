<?php
session_start();

$host = "localhost";
$port = "5432";
$dbname = "evacuation";
$user = "postgres";
$db_password = "RootAdmin";

$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$db_password");

if (!$conn) {
    die(json_encode(array('success' => false, 'message' => 'Database connection failed')));
}

$ownername = isset($_POST['ownername']) ? $_POST['ownername'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

try {
    // Prepare SQL query with parameterized query
    $sql = "SELECT owner_id, password FROM public.owners WHERE ownername = $1";

    // Execute the query with parameters
    $result = pg_query_params($conn, $sql, array($ownername));

    // Check if the query execution was successful
    if (!$result) {
        throw new Exception('Query execution failed');
    }

    // Check if any rows were returned
    if (pg_num_rows($result) > 0) {
        // Fetch the row
        $row = pg_fetch_assoc($result);
        
        // Verify the password
        if (password_verify($password, $row['password'])) {
            // Password is correct, login successful
            $_SESSION['owner_id'] = $row['owner_id'];
            echo json_encode(array('success' => true, 'message' => 'Login successful'));
        } else {
            // Incorrect password
            echo json_encode(array('success' => false, 'message' => 'Incorrect password'));
        }
    } else {
        // User with the provided ownername not found
        echo json_encode(array('success' => false, 'message' => 'Username not found'));
    }
} catch (Exception $e) {
    // Log error
    error_log("Error: " . $e->getMessage(), 3, "error.log");
    // Return error response
    echo json_encode(array('success' => false, 'message' => 'An error occurred. Please try again later.'));
}

// Close database connection
pg_close($conn);
?>