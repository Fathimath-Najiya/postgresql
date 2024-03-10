<?php

// Database connection parameters
$host = "localhost";
$port = "5432";
$dbname = "evacuation";
$user = "postgres";
$db_password = "RootAdmin";

// Establish database connection
$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$db_password");

if (!$conn) {
    die(json_encode(array('success' => false, 'message' => 'Database connection failed')));
}

// Start session
session_start();

// Get username and password from POST request
$username = isset($_POST['username']) ? $_POST['username'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

try {
    // Prepare SQL query with parameterized query
    $sql = "SELECT * FROM public.users WHERE username = $1";

    // Execute the query with parameters
    $result = pg_query_params($conn, $sql, array($username));

    // Check if the query execution was successful
    if (!$result) {
        throw new Exception('Query execution failed');
    }

    // Check if any rows were returned
    if (pg_num_rows($result) > 0) {
        // Fetch the row
        $row = pg_fetch_assoc($result);

        // Verify the username and password
        if (password_verify($password, $row['password'])) {
            // Username and password match, login successful
            $_SESSION['username'] = $username;
            echo json_encode(array('success' => true, 'message' => 'Login successful'));
        } else {
            // Incorrect username or password
            echo json_encode(array('success' => false, 'message' => 'Incorrect username or password'));
        }
    } else {
        // User with the provided username not found
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