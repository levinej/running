<?php
include('top-cache.php'); 
?>
<html>
<head>
<script>
    window.onload = function() {
    if (parent) {
        var oHead = document.getElementsByTagName("head")[0];
        var arrStyleSheets = parent.document.getElementsByTagName("link");
        for (var i = 0; i < arrStyleSheets.length; i++){    
            oHead.appendChild(arrStyleSheets[i].cloneNode(true));
        }            
    }    
}
</script>
</head>
<body style="background-color:transparent">
<div class="widget widget_recent_entries">
<?php
require_once 'nikeplusphp.4.5.1.php';
require_once 'cindyruns-nike.php';

$n = new NikePlusPHP('$cindyrunsUsername', '$cindyrunsPassword');

$activities = $n->activities();
$mostRecent = $n->mostRecentActivity();

$shouldCache = true;

if (!$activities) {
	$shouldCache = false;
	}

$format = 'Y-m-d*G:i:sP';
date_default_timezone_set("America/New_York");
$currentDate = getdate();

$totalYears = array();

foreach ($activities as $activity) {
  $date = $activity->startTimeUtc;
  $local_date = date_parse_from_format($format, $date);
  $year = $local_date['year'];
  #echo $year;
  $month = $local_date['month'];
  #echo $month;
  $distance = $n->toMiles($activity->metrics->distance);
  #echo $distance;
  $totalYears[$year] = $totalYears[$year]+$distance;
}

$mostRecentDistance = $n->toMiles($mostRecent->activity->distance);
$mostRecentDate = $mostRecent->activity->startTimeUtc;
$mostRecentlocal_date = date_create_from_format($format, $mostRecentDate);
$mostRecentDateFormatted = date_format($mostRecentlocal_date, 'l, F j');
$mostRecentShoeName = $mostRecent->activity->tags->SHOES->name;
$mostRecentShoeMiles = $n->toMiles($mostRecent->activity->tags->SHOES->distance);
$mostRecentNote = $mostRecent->activity->tags->note;
#$mostRecentNote = "$mostRecentNote [test]";
$mostRecentNote = preg_replace('/\[.*?\]/', "", $mostRecentNote);

if ( (stripos($mostRecentShoeName, "nimbus ") !== false) or (stripos($mostRecentShoeName, "nim ") !== false) ) {
	$mostRecentShoeName = "Asics $mostRecentShoeName";
}

echo "<strong>Most recent run:</strong><br/>\n";
echo "$mostRecentDateFormatted: $mostRecentDistance miles<br/>\n";
echo "on $mostRecentShoeName ($mostRecentShoeMiles miles on shoe)<br/>\n";
echo "<em><font size=\"-1\">$mostRecentNote</font></em><br/>\n";

echo "<br/>\n";

if ($mostRecentDistance == 0 || $mostRecentlocal_date == false) {
	$shouldCache = false;
	}

krsort($totalYears);

foreach ($totalYears as $yearKey => $yearValue) {
	if ($yearKey >=2012) {
		echo "<strong>$yearKey";
		if ($yearKey == $currentDate['year']) {
			echo " YTD:</strong>";
		}
		else {
			echo " Total:</strong>";
		}
		echo " $yearValue miles<br/>\n";
  	}
}
/* By month
		ksort($yearValue);
		$totalYear = 0;
		foreach ($yearValue as $monthKey => $monthValue ) {
			$totalYear = $totalYear + $monthValue;
			$time = mktime(0, 0, 0, $monthKey);
			$name = strftime("%B", $time);
			echo "$name: $monthValue miles<br/>\n";
		}
		echo "TOTAL: $totalYear<br/>\n";
		echo "<br/>\n";
*/

?>
<br/>
<a href="http://nikeplusphp.org" target="_top">Powered by Nike+<strong>PHP</strong></a>
</body>
</html>
<?php
include('bottom-cache.php');
?>
