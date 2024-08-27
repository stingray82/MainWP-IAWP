<?php
// Enable error reporting for debugging
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

// Include WordPress (if running this as a standalone script)
//require_once('/path/to/wordpress/wp-load.php'); // Replace with the correct path to wp-load.php
require_once($_SERVER['DOCUMENT_ROOT'].'/wp-load.php');
if ( function_exists( 'IAWP_FS' ) ) {
    // Check if the required query parameters 'from' and 'to' are set
if (isset($_GET['from']) && isset($_GET['to'])) {
    // Get the 'from' and 'to' parameters from the query string
    $from_param = $_GET['from'];
    $to_param = $_GET['to'];

    try {
        // Convert the parameters to DateTime objects
        $from_date = new DateTime($from_param);
        $to_date = new DateTime($to_param);

        // Ensure that the 'from' date is before or equal to the 'to' date
        if ($from_date > $to_date) {
            throw new Exception("'from' date cannot be later than 'to' date.");
        }

        // Call the iawp_analytics function with the specified date range
        $analytics = iawp_analytics($from_date, $to_date);

        // Check if analytics data is valid
        if ($analytics && isset($analytics->views) && isset($analytics->visitors) && isset($analytics->sessions)) {
            // Prepare the data as an associative array
            $data = array(
                'views' => $analytics->views,
                'visitors' => $analytics->visitors,
                'sessions' => $analytics->sessions
            );

            // Set the Content-Type header to application/json
            header('Content-Type: application/json');

            // Output the data as a JSON string
            echo json_encode($data);
        } else {
            // If analytics data is invalid, throw an exception
            throw new Exception("Invalid analytics data received.");
        }
    } catch (Exception $e) {
        // Handle errors by outputting a JSON error message
        header('Content-Type: application/json');
        echo json_encode(array('error' => $e->getMessage()));
    }
} else {
    // If 'from' or 'to' is not provided, output an error
    header('Content-Type: application/json');
    echo json_encode(array('error' => 'Missing required parameters: from and to'));
}

} else {
}
?>