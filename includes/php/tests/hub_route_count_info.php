<?php

echo "<link rel='stylesheet' type='text/css' href='https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css'>";
echo "<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js'></script>";
echo "<script type='text/javascript' charset='utf8' src='https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js'></script>";

?>

<script>
    jQuery(document).ready(function() {
        jQuery('table').DataTable({
            paging: false
        });
    });
</script>

<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');

$rootPath   =   $_SERVER['DOCUMENT_ROOT'];
$config     =   require $rootPath . '/../hub_db.php';

$date_format =  "d M, H:i";

$host       =   $config['db_host'];
$db         =   $config['db_name'];
$user       =   $config['db_user'];
$pass       =   $config['db_pass'];
$charset    =   $config['db_charset'];

$route_array = [];

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset($charset);

// Fetch all entries from the table
$stmt = $conn->prepare("SELECT * FROM wp_hub_route_visitor_count");
$stmt->execute();
$result = $stmt->get_result();

$visits = [];
$total_visits = 0;

while ($row = $result->fetch_assoc()) {
    $route_array[] = $row;
    $visits[] = $row['Visits'];
    $total_visits += $row['Visits'];
}

$max_views = max($visits);
sort($visits);
$middle = floor(count($visits) / 2);

if (count($visits) % 2 === 0) {
    $median = ($visits[$middle - 1] + $visits[$middle]) / 2;
} else {
    $median = $visits[$middle];
}

$stmt->close();

echo '<link rel="stylesheet" type="text/css" href="hub_route_count_info.css">';

// Define custom header mapping
$custom_headers = [
    "PageID"        =>  "Side ID",
    "RouteName"     =>  "Rutenavn",
    "Fylke"         =>  "Fylke",
    "Type"          =>  "Kjøretøy",
    "Visits"        =>  "Antall besøkende",
    "LastVisit"     =>  "Siste besøk"
];

echo "<table>";
echo "<thead>";
echo "<tr>";
foreach ($route_array[0] as $key => $value) {
    $header = isset($custom_headers[$key]) ? $custom_headers[$key] : $key;
    echo "<th>" . $header . "</th>";
}
echo "<th>Besøk %</th>";
echo "<th>Besøk % (Relativt)</th>";
echo "<th>Rating</th>";
echo "</tr>";
echo "</thead>";
echo "<tbody>";
foreach ($route_array as $row) {

    echo "<tr>";

    foreach ($row as $key => $value) {
        if ($key == 'RouteName') {

            $rute_nummer    =   get_field('rute_nummer', $value);
            $rute_navn      =   get_field('rute_navn', $value);
            $routeName      =   "<a href='" . get_permalink($value) . "?comingFromDebug=true' target='_blank'>Rute " . $rute_nummer . ' - ' . $rute_navn . "</a>";

            echo "<td>" . $routeName . "</td>";
        } elseif ($key == 'Fylke' || $key == 'Type') {
            echo "<td>" . get_taxonomy_title($value, $conn) . "</td>";
        } elseif ($key == 'LastVisit') {
            echo "<td>" . date($date_format, $value) . "</td>";
        } else {
            echo "<td>" . $value . "</td>";
        }
    }
    $percentage = ($row['Visits'] / $total_visits) * 100;
    echo "<td>" . number_format($percentage, 2) . "%</td>";

    $relative_percentage = ($row['Visits'] / $max_views) * 100;
    echo "<td>" . number_format($relative_percentage, 2) . "%</td>";

    $rating = logarithmicRating($row['Visits'], $max_views);
    echo "<td>" . $rating . " av 10</td>";

    echo "</tr>";
}

echo "</tbody>";
echo "</table>";

$conn->close();

function get_taxonomy_title($taxonomy_id, $conn)
{
    if (empty($taxonomy_id)) {
        return '';
    }
    $stmt = $conn->prepare("SELECT wp_terms.name FROM wp_terms INNER JOIN wp_term_taxonomy ON wp_terms.term_id = wp_term_taxonomy.term_id WHERE wp_term_taxonomy.term_taxonomy_id = ?");
    $stmt->bind_param("i", $taxonomy_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    return $row['name'] ?? '';
}

// Function to calculate rating on a logarithmic scale
function logarithmicRating($visits, $maxVisits)
{
    return round(1 + 9 * (log($visits + 1) / log($maxVisits + 1)));
}
