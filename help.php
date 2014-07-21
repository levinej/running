<?php
// Point to where you downloaded the phar
include('httpful.phar');
include('cindyruns.php');

//https://api.runningahead.com/rest/logs/me/workouts/2FTFry7in2D9zRPz8VA7JQ?

$getRequestUrl = "https://api.runningahead.com/rest/logs/me/workouts/2FTFry7in2D9zRPz8VA7JQ" . "?access_token=$access_token";       
$getRequest = \Httpful\Request::get($getRequestUrl) 
    ->send(); 

       echo "request:<pre>";
	var_dump($getRequest);  
	echo "</pre><br>";

$preferenceUrl = "https://api.runningahead.com/rest/users/me/preference" . "?access_token=$access_token";       
$preference = \Httpful\Request::get($preferenceUrl) 
    ->send(); 

       echo "preferences:<pre>";
	var_dump($preference);  
	echo "</pre><br>";

//PUT https://api.runningahead.com/rest/users/me/preference

$setPreferenceUrl = "https://api.runningahead.com/rest/users/me/preference" . "?access_token=$access_token";       

$setPreferenceData = array(
              'utcOffset'=>'0',
              'distanceUnit'=>'mi',
              'weightUnit'=>'lb',
              'temperatureUnit'=>'F'
              );
              
              $setPreference = \Httpful\Request::put($setPreferenceUrl, json_encode($setPreferenceData)) 
    ->send(); 

       echo "preferences set:<pre>";
	var_dump($setPreference);  
	echo "</pre><br>";

$allActivityUrl = "https://api.runningahead.com/rest/logs/me/activity_types" . "?access_token=$access_token";       
$allActivity = \Httpful\Request::get($allActivityUrl) 
    ->send(); 

       echo "all activity:<pre>";
	var_dump($allActivity);  
	echo "</pre><br>";
	

$allEquipUrl = "https://api.runningahead.com/rest/logs/me/equipment" . "?access_token=$access_token&showRetired=true	";       
$allEquip = \Httpful\Request::get($allEquipUrl) 
    ->send(); 

       echo "all equipment:<pre>";
	var_dump($allEquip);  
	echo "</pre><br>";
	

$allCoursesUrl = "https://api.runningahead.com/rest/logs/me/courses" . "?access_token=$access_token";       
$allCourses = \Httpful\Request::get($allCoursesUrl) 
    ->send(); 

       echo "all courses:<pre>";
	var_dump($allCourses);  
	echo "</pre><br>";

$url = "https://api.runningahead.com/rest/logs/me/workouts?";

$data = array('access_token'=>$access_token,
              'fields'=>'12,13',
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