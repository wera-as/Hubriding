<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ruteinformasjon</title>
    <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js'></script>
    <script type='text/javascript' charset='utf8' src='https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js'></script>
    <link rel="stylesheet" type="text/css" href="css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="css/hub_route_count_info.css">
    <script type='text/javascript' charset='utf8' src='js/hub_route_count_info.js'></script>

    <script>
        jQuery(document).ready(function() {
            jQuery.fn.dataTable.ext.type.order['customNumeric-pre'] = function(data) {
                var matches = data.match(/\d+/);
                return matches ? parseInt(matches[0], 10) : 0;
            };
            jQuery('table').DataTable({
                paging: true,
                "pagingType": "numbers",
                "lengthMenu": [
                    [20, 50, 100, -1],
                    [20, 50, 100, "All"]
                ],
                order: [
                    [4, 'desc']
                ],
                columnDefs: [{
                    type: 'customNumeric',
                    targets: 7
                }],
                language: {
                    processing: "Behandler...",
                    search: "Søk:",
                    lengthMenu: "Vis _MENU_ elementer",
                    info: "Viser element _START_ til _END_ av _TOTAL_ elementer",
                    infoEmpty: "Viser 0 til 0 av 0 elementer",
                    infoFiltered: "(filtrert fra _MAX_ totalt elementer)",
                    infoPostFix: "",
                    loadingRecords: "Laster inn...",
                    zeroRecords: "Ingen elementer funnet",
                    emptyTable: "Ingen data tilgjengelig i tabellen",
                    paginate: {
                        first: "Første",
                        previous: "Forrige",
                        next: "Neste",
                        last: "Siste"
                    },
                    aria: {
                        sortAscending: ": aktiver for å sortere kolonnen stigende",
                        sortDescending: ": aktiver for å sortere kolonnen synkende"
                    }
                }
            });
        });
    </script>

    <?php

    require_once($_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');

    $rootPath   =   $_SERVER['DOCUMENT_ROOT'];
    $config     =   require $rootPath . '/../hub_db.php';

    $date_format =  "d. M Y, H:i";

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
    $stmt = $conn->prepare("
    SELECT v.* FROM wp_hub_route_visitor_count v
    JOIN wp_posts p ON v.PageID = p.ID
    WHERE p.post_status = 'publish'
    ");
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

    $stmt->close();

    $total = 0;

    ?>
</head>

<body>
    <?php


    // Define custom header mapping
    $custom_headers = [
        "PageID"        =>  "Side ID",
        "RouteName"     =>  "Rutenavn",
        "Fylke"         =>  "Fylke",
        "Type"          =>  "Kjøretøy",
        "Visits"        =>  "Antall besøk",
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
    echo "<th>Popularitet</th>";
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
                echo "<td>" . date($date_format, $value + 7200) . "</td>";
            } elseif ($key == 'Visits') {
                echo "<td class='visits'>" . $value . "<div class='bar'></div></td>";
            } else {
                echo "<td>" . $value . "</td>";
            }
        }
        $percentage = ($row['Visits'] / $total_visits) * 100;
        echo "<td>" . number_format($percentage, 2) . "%</td>";

        $rating = calculatePercentile($visits, $row['Visits']);
        echo "<td>" . $rating . " av 10</td>";

        echo "</tr>";

        $total += $row['Visits'];
    }

    echo "</tbody>";
    echo "</table>";

    echo "<div id='footer'>";
    echo "<span class='total_visits'><strong>Totalt $total</strong>&nbsp;besøk</span>";
    echo "<span class='version'>Versjon: 0.3</span>";
    echo "<span class='start_date'>Loggføringen begynte 15.08.2023.</span>";
    echo "</div>";

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
    //function logarithmicRating($visits, $maxVisits)
    //{
    //    return round(1 + 9 * (log($visits + 1) / log($maxVisits + 1)));

    function calculatePercentile($visitorCounts, $currentVisitorCount)
    {
        sort($visitorCounts);
        $totalRoutes = count($visitorCounts);
        $countLessThanCurrent = 0;

        foreach ($visitorCounts as $count) {
            if ($count < $currentVisitorCount) {
                $countLessThanCurrent++;
            }
        }

        // Calculate percentile rank (not in percentage form)
        $percentileRank = $countLessThanCurrent / $totalRoutes;

        return (1 + floor($percentileRank * 10)); // This maps the lowest percentiles to the lowest ratings
    }

    ?>
</body>