<?php
function hub_get_rout_visits()
{
    session_start(); // Start the session

    if (is_user_logged_in() && current_user_can('administrator')) {
        return; // Exit the function if the user is an admin or logged in
    }

    if (isset($_GET['comingFromDebug']) && $_GET['comingFromDebug'] == 'true') {
        return; // Exit the function if the "comingFromDebug" GET variable is set to "true"
    }

    $rootPath = $_SERVER['DOCUMENT_ROOT'];
    $config = require $rootPath . '/../hub_db.php';

    $host       =   $config['db_host'];
    $db         =   $config['db_name'];
    $user       =   $config['db_user'];
    $pass       =   $config['db_pass'];
    $charset    =   $config['db_charset'];

    // Create connection
    $conn = new mysqli($host, $user, $pass, $db);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $conn->set_charset($charset);

    $pageID = get_the_ID();
    $routeTitle = get_the_ID();
    $fylke = get_field('rute_fylke', $pageID);
    $type = get_field('rute_kjoretoy', $pageID);

    // Check if the user has already visited the page in the last 60 seconds
    if (isset($_SESSION['visited_' . $pageID]) && $_SESSION['visited_' . $pageID] === true) {
        if (time() - $_SESSION['timestamp_' . $pageID] < 60) {
            return; // Exit the function if the user has already visited the page in the last 60 seconds
        } else {
            unset($_SESSION['visited_' . $pageID]);
            unset($_SESSION['timestamp_' . $pageID]);
        }
    }

    // Get the current Unix timestamp
    $timestamp = time();

    // Check if the entry with the given ID exists
    $stmt = $conn->prepare("SELECT 1 FROM wp_hub_route_visitor_count WHERE PageID = ?");
    $stmt->bind_param("i", $pageID);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        // Entry exists, update the count and LastVisit
        $stmt = $conn->prepare("UPDATE wp_hub_route_visitor_count SET Visits = Visits + 1, LastVisit = ? WHERE PageID = ?");
        $stmt->bind_param("ii", $timestamp, $pageID);
        $stmt->execute();
    } else {
        // Entry doesn't exist, insert it
        $stmt = $conn->prepare("INSERT INTO wp_hub_route_visitor_count (`PageID`, `RouteName`, `Fylke`, `Type`, `Visits`, `LastVisit`) VALUES (?, ?, ?, ?, 1, ?)");
        $stmt->bind_param("iisii", $pageID, $routeTitle, $fylke, $type, $timestamp);
        $stmt->execute();
    }

    $stmt->close();
    $conn->close();

    // Set a session variable to indicate that the user has visited the page and store the timestamp
    $_SESSION['visited_' . $pageID] = true;
    $_SESSION['timestamp_' . $pageID] = $timestamp;
}
add_shortcode('hub_get_rout_visits', 'hub_get_rout_visits');
