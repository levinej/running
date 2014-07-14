<?php
require('top-cache.php'); 
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
require 'httpful.phar';
require 'cindyruns.php';

$format = 'Y-m-d';
date_default_timezone_set("America/New_York");
$currentDate = getdate();

//Get most recent run id
$url = "https://api.runningahead.com/rest/logs/me/workouts?";
$data = array('access_token'=>$access_token,
              'limit'=>'1');
$url = $url . http_build_query($data);
$recentRun = \Httpful\Request::get($url) 
    ->send();  

//Get most recent run details
$recentRunId = $recentRun->body->data->entries[0]->id;
$recentRunUrl = "https://api.runningahead.com/rest/logs/me/workouts/" . $recentRunId . "?access_token=$access_token";       
$recentRunDetails = \Httpful\Request::get($recentRunUrl) 
    ->send(); 
  
//Get details of equipment used in most recent run (need miles)
$recentRunEquipId = $recentRunDetails->body->data->workout->equipment[0]->id;
$recentRunEquipUrl = "https://api.runningahead.com/rest/logs/me/equipment/" . $recentRunEquipId . "?access_token=$access_token";       
$recentRunEquipDetails = \Httpful\Request::get($recentRunEquipUrl) 
    ->send(); 
    
//Get totals per year
$urlTotals = "https://api.runningahead.com/rest/logs/me/workouts?";
$dataTotals = array('access_token'=>$access_token,
		      'fields'=>'12,20',
			  'dateMode'=>'year');
$urlTotals = $urlTotals . http_build_query($dataTotals);
$totals = \Httpful\Request::get($urlTotals) 
    ->send();  
 
foreach ($totals->body->data->entries as $total) {
  $date = $total->date;
  $local_date = date_parse_from_format($format, $date);
  $year = $local_date['year'];
  //echo $year;
  $month = $local_date['month'];
  //echo $month;
  $distance = $total->details->distance->value*0.621371;
  $distance = round($distance);
  //echo $distance;
  $totalYears[$year] = $totalYears[$year]+$distance;
}

$shouldCache = true;

if (!$recentRunDetails) {
	$shouldCache = false;
	}

$mostRecentDistance = $recentRunDetails->body->data->workout->details->distance->value;
$mostRecentDate = $recentRunDetails->body->data->workout->date;
$mostRecentlocal_date = date_create_from_format($format, $mostRecentDate);
$mostRecentDateFormatted = date_format($mostRecentlocal_date, 'l, F j');
$mostRecentNote = $recentRunDetails->body->data->workout->notes;
#$mostRecentNote = "$mostRecentNote [test]";
$mostRecentNote = preg_replace('/\[.*?\]/', "", $mostRecentNote);

$mostRecentShoeName = $recentRunDetails->body->data->workout->equipment[0]->name;
$mostRecentShoeMiles = $recentRunEquipDetails->body->data->distance->value;


if ( (stripos($mostRecentShoeName, "nimbus ") !== false) or (stripos($mostRecentShoeName, "nim ") !== false) ) {
	$mostRecentShoeName = "Asics $mostRecentShoeName";
}

echo "<strong>Most recent run:</strong><br/>\n";
echo "$mostRecentDateFormatted: $mostRecentDistance miles<br/>\n";
echo "on $mostRecentShoeName<br/>\n";
//echo "on $mostRecentShoeName ($mostRecentShoeMiles miles on shoe)<br/>\n";
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

?>
<br/>
<a href="https://www.runningahead.com/" target="_top">Powered by Running<strong>AHEAD</strong></a>
</body>
</html>
<?php
include('bottom-cache.php');
?>
