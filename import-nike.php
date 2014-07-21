<pre>
<?php
#phpinfo();
  require_once 'httpful.phar';
  require_once 'cindyruns.php';

  require_once 'nikeplusphp.4.5.1.php';
  require_once 'cindyruns-nike.php';
  $n = new NikePlusPHP($cindyrunsUsername, $cindyrunsPassword);

  $activities = $n->activities();

$format = 'Y-m-d';
date_default_timezone_set("EST5EDT");

$summary = array();

foreach ($activities as $activity) {
  $date = $activity->startTimeUtc;
  echo "$date|";
  $terrain = $activity->tags->terrain;
  echo "$terrain|";
  $shoe = $activity->tags->SHOES->name;
  echo "$shoe|";
  $weather = $activity->tags->weather;
  echo "$weather|";
  $note = $activity->tags->note;
  $tag = "";
  preg_match('/\[.*?\]/', $note, $tags);
  $tag = $tags[0];
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
  
  //count comption
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
		echo ("id to edit: $idToUpdate");
		/*
		$updateUrl = "https://api.runningahead.com/rest/logs/me/workouts/$idToUpdate?access_token=$access_token";

		$updateData['workout'] = array();
		
		if ($terrain == 

		$updateUrl = $updateUrl . http_build_query($data);

		$updateResponse = \Httpful\Request::put($url) 
    		->send(); 
    		*/          
	}
}
ob_flush();
flush();
//break;
}

	echo "summary:\n";
	var_dump($summary);  
	echo "\n";

?>
</pre>