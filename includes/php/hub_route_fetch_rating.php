<?php

function hub_Route_Fetch_Rating()
{

    $routeID = get_the_ID();

    $rootPath   =   $_SERVER['DOCUMENT_ROOT'];
    $config     =   require $rootPath . '/../hub_db.php';

    $host       =   $config['db_host'];
    $db         =   $config['db_name'];
    $user       =   $config['db_user'];
    $pass       =   $config['db_pass'];
    $charset    =   $config['db_charset'];

    $conn = new mysqli($host, $user, $pass, $db);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $conn->set_charset($charset);

    // Prepare the query
    $stmt = $conn->prepare("SELECT * FROM wp_hub_route_visitor_count WHERE PageID = ?");
    $stmt->bind_param("i", $routeID);
    $stmt->execute();
    $stmt->store_result();

    // Check if the query was successful
    if ($stmt->num_rows === 0) {
        return "";
    }

    // Bind the result and fetch the rating
    $stmt->bind_result($rating);
    $stmt->fetch();

    // Close the statement and connection
    $stmt->close();
    $conn->close();

    $max_views = max($row['Visits']);
    $rating = logarithmicRating($row['Visits'], $max_views);
    return "<span>" . $rating . " av 10</span>";
}
add_shortcode("hub_Route_Fetch_Rating", "hub_Route_Fetch_Rating");

function logarithmicRating($visits, $maxVisits)
{
    return round(1 + 9 * (log($visits + 1) / log($maxVisits + 1)));
}
