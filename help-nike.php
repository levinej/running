<?php
#phpinfo();

  require_once 'nikeplusphp.4.5.1.php';
  require 'cindyruns-nike.php';
  $n = new NikePlusPHP($cindyrunsUsername, $cindyrunsPassword);

  $activities = $n->activities();
echo "Activities:<pre>";
var_dump($activities);  
echo "</pre><br>";

$alltime = $n->alltime();
echo "All Time:<pre>";
var_dump($alltime);
echo "</pre><br>";

$mostrecent = $n->mostRecentActivity();
echo "Most Recent:<pre>";
var_dump($mostrecent);
echo "</pre><br>";

  $totalMiles = $n->toMiles($alltime->lifetimeTotals->distance);
echo $totalMiles;
?>
