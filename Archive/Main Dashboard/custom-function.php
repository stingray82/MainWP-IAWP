<?php
function mycustom_generate_analytics_tokens($tokensValues, $report, $website) {
    // Log for debugging
    error_log('Custom token filter applied');

    // Get the site URL and report date range
    //$site_url = $website->url; // Accessing as an object, not an array
    //$date_range = $report->daterange; // Accessing as an object, not an array

    // Split the date range into from and to dates
    ///list($from_date, $to_date) = explode(' - ', $date_range);

    // Convert the dates into the format needed for the API (Y-m-d)
    //$from_date_obj = DateTime::createFromFormat('d-m-Y', trim($from_date));
    //$to_date_obj = DateTime::createFromFormat('d-m-Y', trim($to_date));
    
    /* As I can't process the "token" need supports help for this one will temp add them in to check everything else works */
    // Variables
    $from_date_obj = "2024-08-01";
    $to_date_obj = "2024-08-27";
    $site_url = "yoursitegoeshere.co.uk";
    
    // Check if the DateTime objects were created successfully
    if (!$from_date_obj || !$to_date_obj) {
        error_log('Error: Invalid date format in report.daterange.');
        return $tokensValues; // Return early if the dates are invalid
    }

   // $from_date = $from_date_obj->format('Y-m-d');
    //$to_date = $to_date_obj->format('Y-m-d');
    $from_date = $from_date_obj;
    $to_date = $to_date_obj;

    // Build the API URL
    $api_url = "{$site_url}/test.php?from={$from_date}&to={$to_date}";

    // Fetch the data from the API
    $response = wp_remote_get($api_url);

    // Check if the request was successful
    if (is_wp_error($response)) {
        error_log('Error: Failed to retrieve data from API. ' . $response->get_error_message());
        return $tokensValues;
    }

    // Decode the JSON response
    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body);

    // Check if the data is valid
    if (isset($data->views) && isset($data->visitors) && isset($data->sessions)) {
        // Assign the values to variables
        $ipwa_views = $data->views;
        $ipwa_visitors = $data->visitors;
        $ipwa_sessions = $data->sessions;
        error_log('Views:'.$ipwa_views);
        error_log('Visitors:'.$ipwa_visitors);
        error_log('Sessions:'.$ipwa_sessions);

        // Add these values as tokens
        $tokensValues['[ipwa-views]'] = $ipwa_views;
        $tokensValues['[ipwa-visitors]'] = $ipwa_visitors;
        $tokensValues['[ipwa-sessions]'] = $ipwa_sessions;

        // Log success
        error_log('Successfully retrieved and processed data from API.');
    } else {
        // Log if data is invalid
        error_log('Error: Invalid data received from API.');
    }

    return $tokensValues;
}
