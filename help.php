<?php
// Point to where you downloaded the phar
include('httpful.phar');
include('cindyruns.php');

$url = "https://api.runningahead.com/rest/logs/me/workouts?";

$data = array('access_token'=>$access_token,
              //'fields'=>'12,20',
              //'activityID'=>'10',
              //'filters'=>'[["date", "lt", "2013-01-01"], ["date", "gt", "2011-12-31"]]',
              //'filters'=>'[["activityID","eq",10], ["date", "gt", "2013-12-31"]]',
              //'filters'=>'[["activityID","eq",10]]',
              'limit'=>'1');

$url = $url . http_build_query($data);

$response = \Httpful\Request::get($url) 
    ->send();         

$recentrunid = $response->body->data->entries[0]->id;
echo "recentrunid: $recentrunid";

$recentrunurl = "https://api.runningahead.com/rest/logs/me/workouts/" . $recentrunid . "?access_token=$access_token";

$recentrundetails = \Httpful\Request::get($recentrunurl) 
    ->send(); 
    
        echo "recentrun:<pre>";
	var_dump($recentrundetails);  
	echo "</pre><br>";
	
	$recentRunEquipId = $recentrundetails->body->data->workout->equipment[0]->id;
	echo $recentRunEquipId;
$recentRunEquipUrl = "https://api.runningahead.com/rest/logs/me/equipment/" . $recentRunEquipId . "?access_token=$access_token";       
$recentRunEquipDetails = \Httpful\Request::get($recentRunEquipUrl) 
    ->send(); 

       echo "equipment :<pre>";
	var_dump($recentRunEquipDetails);  
	echo "</pre><br>";

/*
foreach ($response->body->data->entries as $activity) {
    echo $activity->id;
    //This is how we would delete the results above
    //$deleteurl = "https://api.runningahead.com/rest/logs/me/workouts/" . $activity->id . "?access_token=$access_token";
    $deleted = \Httpful\Request::delete($deleteurl) 
    ->send();        
    echo "Response deleted:<pre>";
	var_dump($deleted);  
	echo "</pre><br>";
}
*/

echo "Response:<pre>";
var_dump($response);  
echo "</pre><br>";


?>