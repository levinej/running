<pre>
<?php
#phpinfo();
  require_once 'httpful.phar';
  require_once 'cindyruns.php';

  require_once 'nikeplusphp.4.5.1.php';
  require_once 'cindyruns-nike.php';
  $n = new NikePlusPHP($cindyrunsUsername, $cindyrunsPassword);

  $activities = $n->activities();
  
  $mapping['terrain']['treadmill'] = 'yhzCj8TSv56cykowks9ey2';
  $mapping['shoe']['Sauc ride new'] = 'PvE1YlzDWoVQpB5nJXAl51';
  $mapping['shoe']['Asics cumulus'] = 'w0F60t407qwy37rmrypO54';
  $mapping['shoe']['New Balance 980'] = 'e8zwx5x9nw7AwSQcTpCPr3';
  $mapping['shoe']['Saucony Ride 6'] = 'o9Np2pJjSK60k28hqoHpK0';
  $mapping['shoe']['Mizuno Wave 17'] = 'dzzFwxWkEbEYRSAi746gl8';
  $mapping['shoe']['Nimbus 15 Lite'] = 'mhfQon9ROKwJWieh85BFz4';
  $mapping['shoe']['Brooks Glyc 11'] = 'w2M7BNW2zwPFLVQBUltJL2';
  $mapping['shoe']['Nimbus 15 Pink'] = 'umXYzP5tDIDArGzFLOzzf6';
  $mapping['shoe']['Brooks Glyc 10'] = 'zKNwoy0weLBazjRdH30rl8';
  $mapping['shoe']['Nimbus 14 Rainb'] = '9XmnBJl4hK6N98gGOOMv10';
  $mapping['shoe']['Nimbus 14 Pink'] = '0jGuGIgvkK625hPmV9sAo2';
  $mapping['shoe']['Nimbus 14 Grey'] = '2yomDUnlLA5TyFeVF3FJI9';
  $mapping['weather']['sunny'] = 'clear';
  $mapping['weather']['rainy'] = 'rain';  
  $mapping['weather']['cloudy'] = 'partlyCloudy';
  $mapping['weather']['partly_sunny'] = 'overcast'; 
  $mapping['weather']['snowy'] = 'snow';
  $mapping['tag']['[easyrun]'] = '1'; 
  $mapping['tag']['[longrun]'] = '4'; 
  $mapping['tag']['[race]'] = '6'; 
  $mapping['tag']['[recoveryrun]'] = '1'; 
  $mapping['tag']['[halfmarathon]'] = '6';     

$format = 'Y-m-d';
// it looks like can't get runningahead to use GMT right now...so we'll need to run twice
date_default_timezone_set('EST5EDT');
//date_default_timezone_set('EST');

$summary = array();

foreach ($activities as $activity) {
  $date = $activity->startTimeUtc;
  echo "$date|";
  $terrain = trim($activity->tags->terrain);
  echo "$terrain|";
  $shoe = trim($activity->tags->SHOES->name);
  echo "$shoe|";
  $weather = trim($activity->tags->weather);
  echo "$weather|";
  $note = $activity->tags->note;
  $tag = "";
  preg_match('/\[.*?\]/', $note, $tags);
  $tag = trim($tags[0]);
  $note = preg_replace('/\[.*?\]/', "", $note);
  $note = trim($note);
  echo "$note|";
  echo "$tag|";
  $emotion = $activity->tags->emotion;
  echo "$emotion\n";
  
  //count terrains
  if (!empty($terrain))
  {
  	if (isset($summary['terrain'][$terrain]))
  	{
  		$summary['terrain'][$terrain]++;
 	 }
 	 else
 	 {
 	 	$summary['terrain'][$terrain] = 1;
 	 }
  }
  
  //count shoe
  if (!empty($shoe))
  {
  	if (isset($summary['shoe'][$shoe]))
  	{
  		$summary['shoe'][$shoe]++;
 	 }
 	 else
 	 {
 	 	$summary['shoe'][$shoe] = 1;
 	 }
  }

  //count weather
  if (!empty($weather))
  {
  	if (isset($summary['weather'][$weather]))
  	{
  		$summary['weather'][$weather]++;
 	 }
 	 else
 	 {
 	 	$summary['weather'][$weather] = 1;
 	 }
  }  
  
  //count tag
  if (!empty($tag))
  {
  	if (isset($summary['tag'][$tag]))
  	{
  		$summary['tag'][$tag]++;
 	 }
 	 else
 	 {
 	 	$summary['tag'][$tag] = 1;
 	 }
  } 
  
  //count emotion
  if (!empty($emotion))
  {
  	if (isset($summary['emotion'][$temotion]))
  	{
  		$summary['emotion'][$emotion]++;
 	 }
 	 else
 	 {
 	 	$summary['emotion'][$emption] = 1;
 	 }
  }   
  
// update runningahead

//get runs for this date
//2012-08-04T07:51:12-05:00
$parsedDate = strtotime($date);
$shortLocalDate = date('Y-m-d',$parsedDate);
$shortLocalTime = date('H:i:s',$parsedDate);
echo "shortdate: $shortLocalDate\n";
echo "local time: $shortLocalTime\n";

$url = "https://api.runningahead.com/rest/logs/me/workouts?";

$data = array('access_token'=>$access_token,
              'fields'=>'12,13,20',
              'filters'=>"[[\"date\", \"eq\", \"$shortLocalDate\"],[\"time\", \"eq\", \"$shortLocalTime\"]]");

$url = $url . http_build_query($data);

$response = \Httpful\Request::get($url) 
    ->send();           

$numEntries = $response->body->data->numEntries;

echo ("numEntries: $numEntries\n");

if ($numEntries > 1)
{
	echo ("$shortLocalDate at $shortLocalTime has too many entries - skipping\n");
}
else 
{
	$idToUpdate = $response->body->data->entries[0]->id;
	if ($idToUpdate === NULL)
	{
		echo ("$shortLocalDate at $shortLocalTime does not have a matching id - skipping\n");
	}
	else
	{
	if ($note === NULL || $note == "amped")
	{
		echo ("$shortLocatDate at $shortLocalTime does not have a note - skipping\n");
	}
	else
	{
		echo ("id to edit: $idToUpdate");

		$updateUrl = "https://api.runningahead.com/rest/logs/me/workouts/$idToUpdate?access_token=$access_token";
		
		// map terrain to course
		if (isset($mapping['terrain'][$terrain]))
		{
			//$updateData['workout']['course']['id'] = $mapping['terrain'][$terrain];
		}
		
		//  map shoe to equipment
		if (isset($mapping['shoe'][$shoe]))
		{
			//$updateData['workout']['equipment']['id'] = $mapping['shoe'][$shoe];
		}

		//  map weather to conditions
		if (isset($mapping['weather'][$weather]))
		{
			//$updateData['workout']['weather']['conditions'][$weather] = true;
		}
		
		//  map tag to type
		if (isset($mapping['tag'][$tag]))
		{
			//$updateData['workout']['workOutID'] = $mapping['tag'][$tag];
		}
		
		//$updateData['workout']['notes'] = $note;
		
		// for testing
		$updateData['workout']['date'] = '2014-06-22';
		$updateData['workout']['activityID'] = 10;
		
		//echo "\nupdate:\n";
		//var_dump($updateData);  
		//echo "\n";
        
        $update = \Httpful\Request::put($updateUrl, json_encode($updateData)) 
    	->send(); 
    	
    	echo "\\nUpdate Summary:\n";
		var_dump($update);  
		echo "\n";

    
	}
	}
}
ob_flush();
flush();
break;
}

	echo "summary:\n";
	var_dump($summary);  
	echo "\n";

?>
</pre>