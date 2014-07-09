<?php
// Cache the contents to a file
if ($shouldCache) {
$cached = fopen($cachefile, 'w');
fwrite($cached, ob_get_contents());
fclose($cached);
} else
{
echo "<strong>DID NOT CACHE!!!</strong>";
} 
ob_end_flush(); // Send the output to the browser
?>
