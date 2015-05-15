<?php
#URL requirements - local network only
require_once('settings.php');
$TRANSCODER=$PLEX_URL."/photo/:/transcode?width=900&height=600&url=";
$LOCATION = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$content = file_get_contents( $PLEX_URL . "/library/sections/2/all?X-Plex-Token=".$PLEX_TOKEN);

#echo $PLEX_URL . "/library/sections/2/all?X-Plex-Token=".$PLEX_TOKEN;

$x=0.15; # multiply value for positions
$y=0.15; # margin
$count=0; #element counter
$faces=30; # thumbnails per side
$rowlength= 10;
$rowcounter=1;

$axisposition = array
  (
  array(13,1), #front at position 1
  array(0,1),  #right at position 1
  );


function getrotation($loopposition,$rowcounter){
	$frontface = array("17 1 0 "," 13 1 0 "," 9 1 0 "," 5 1 0 "," 1 1 0 "," -3 1 0 "," -7 1 0 "," -11 1 0 "," -15 1 0 "," -19 1 0 "," 17 5.6 0 "," 13 5.6 0 "," 9 5.6 0 "," 5 5.6 0 "," 1 5.6 0 "," -3 5.6 0 "," -7 5.6 0 "," -11 5.6 0 "," -15 5.6 0 "," -19 5.6 0 "," 17 10.2 0 "," 13 10.2 0 "," 9 10.2 0 "," 5 10.2 0 "," 1 10.2 0 "," -3 10.2 0 "," -7 10.2 0 "," -11 10.2 0 "," -15 10.2 0 "," -19 10.2 0 ");
	$rightface = array( " -22 1 -2.5 "," -22 1 -6.5 "," -22 1 -10.5 "," -22 1 -14.5 "," -22 1 -18.5 "," -22 1 -22.5 "," -22 1 -26.5 "," -22 1 -30.5 "," -22 1 -34.5 "," -22 1 -38.5 "," -22 5.6 -2.5 "," -22 5.6 -6.5 "," -22 5.6 -10.5 "," -22 5.6 -14.5 "," -22 5.6 -18.5 "," -22 5.6 -22.5 "," -22 5.6 -26.5 "," -22 5.6 -30.5 "," -22 5.6 -34.5 "," -22 5.6 -38.5 "," -22 10.2 -2.5 "," -22 10.2 -6.5 "," -22 10.2 -10.5 "," -22 10.2 -14.5 "," -22 10.2 -18.5 "," -22 10.2 -22.5 "," -22 10.2 -26.5 "," -22 10.2 -30.5 "," -22 10.2 -34.5 "," -22 10.2 -38.5 ");
	
	#cube vector array
	$axisrotation = array
	  (
	  array(-1,0,0,0,1,0,0,0,-1), #front
	  array(0,0,-1,0,1,0,1,0,0),  #right
	  );

	
	if($loopposition>=0 && $loopposition<=29 ){ # front
		$horizontalposition = "pos='". $frontface[$loopposition] . "'";
	 	$newposition = $horizontalposition . " xdir='". $axisrotation[0][0] . " " . $axisrotation[0][1] . " " . $axisrotation[0][2] ."' ydir='". $axisrotation[0][3] . " " . $axisrotation[0][4] . " " . $axisrotation[0][5] . "' zdir='". $axisrotation[0][6] . " " . $axisrotation[0][7] . " " . $axisrotation[0][8] . "'"; 
	}
	if($loopposition>=30 && $loopposition<60 ){ # right
		$horizontalposition = "pos='". $rightface[$loopposition-30] . "'";
	 	$newposition = $horizontalposition . " xdir='". $axisrotation[1][0] . " " . $axisrotation[1][1] . " " . $axisrotation[1][2] ."' ydir='". $axisrotation[1][3] . " " . $axisrotation[1][4] . " " . $axisrotation[1][5] . "' zdir='". $axisrotation[1][6] . " " . $axisrotation[1][7] . " " . $axisrotation[1][8] . "'"; 
	}

	return $newposition;


}

#xdir='0 0 -1' ydir='0 1 0' zdir='1 0 0
#http://ip:32400/photo/:/transcode?width=1280&height=720&url=http%3a%2f%2f127%2e0%2e0%2e1%3a32400%2flibrary%2fmetadata%2f267%2fart%3ft%3d1334557392

if (empty($content)) {
    die("XML is empty");
}
$xml = simplexml_load_string($content);

$fwd="xdir='-1 0 0' ydir='0 1 0' zdir='0 0 -1'";
echo "<html><head><title>PLEXVR</title></head><body style='text-align:center'><img src='assets/plexlogo.png'  /><h3 style='font-family:arial;padding:80px;color:#f90;'>Open this url in JanusVR.</h3>";
echo "<FireBoxRoom>";
echo "<Assets>";
echo "<AssetImage id='black' src='assets/black.jpg'></AssetImage>";

if( $_GET["video"]=="" ){

	echo "<AssetImage id='plex_logo' src='assets/plexlogo.png' ></AssetImage>";
	echo "<AssetImage id='sky_left' src='assets/nightsky_west.bmp' ></AssetImage>";
	echo "<AssetImage id='sky_right' src='assets/nightsky_east.bmp' ></AssetImage>";
	echo "<AssetImage id='sky_front' src='assets/nightsky_north.bmp' ></AssetImage>";
	echo "<AssetImage id='sky_back' src='assets/nightsky_south.bmp' ></AssetImage>";
	echo "<AssetImage id='sky_up' src='assets/nightsky_up.bmp' ></AssetImage>";
	echo "<AssetImage id='sky_down' src='assets/nightsky_down.bmp' ></AssetImage>";
	echo "<AssetImage id='recent' src='assets/recent.jpg' ></AssetImage>";
	echo "<AssetImage id='exit' src='assets/exit.jpg' ></AssetImage>";



# parse xml
foreach ($xml as $item){

    $title=$item->attributes()->title;
    $art=$item->attributes()->thumb;
    $id=$item->attributes()->ratingKey;
    $video=$item->Media->Part->attributes()->key;

    # create local cache thumbnails if not exist
	if (!file_exists('./cache/img_'.$id.".jpg")) 
	{
		$ch = curl_init( $TRANSCODER . urlencode($PLEX_URL . $art));
		$fp = fopen('./cache/img_'.$id.'.jpg', 'wb');
		curl_setopt($ch, CURLOPT_FILE, $fp);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_exec($ch);
		curl_close($ch);
		fclose($fp);
	}
	# render all thumb and video assets
	echo "<AssetImage id='img_".$id."' src='./cache/img_".$id.".jpg' ></AssetImage>\n";
	echo "<AssetVideo id='vid_".$id."' src='". $PLEX_URL . $video."&amp;X-Plex-Token=".$PLEX_TOKEN."' sbs3d='true' ></AssetVideo>\n";
}

echo "</Assets>";
echo "<Room   use_local_asset='room_plane' walk_speed='5.0' run_speed='10.0' col='0 0 1' pos='5 0 5' fwd='1 0 0'  near_dist='0.0025' far_dist='2000.0' col='.1 .1 .1' skybox_left_id='black' skybox_right_id='black' skybox_front_id='black' skybox_back_id='black' skybox_up_id='black' skybox_down_id='black'>\n";
	
	# parse xml and generate faces/links with their position in the world
	foreach ($xml as $item){

        $title=$item->attributes()->title;
        $id=$item->attributes()->ratingKey;
        $video=$item->Media->Part->attributes()->key;
        $setrotation = getrotation($count,$rowcounter);
		#echo $setrotation . "<br/>";
		echo "<Link draw_glow='false' url='".$LOCATION."?video=".$PLEX_URL.$video."&amp;title=".urlencode($title)."&amp;X-Plex-Token=".$PLEX_TOKEN."' draw_text='false' thumb_id='img_".$id."' pos='".($count+40)." 1 ".($count-20)." ' xdir='-1 0 ".($count-20)."' ydir='0 1 0' zdir='0 0 -1' scale='3.300 3.800 1.000' title='".urlencode($title)."'/>\n";

	$count++;

	if ($rowcounter==$rowlength)
		{
			$rowcounter=1;
		}
		else
		{
			$rowcounter++;
		};

		if($count==60){break;}
	}

	echo "<Image id='plex_logo' pos='46 16 16' otate_deg_per_sec='10'/>";
	echo "<Image id='exit' pos='8.5 3.6 -33.8' xdir='.71 0 .71' ydir='0 1 0' zdir='-.71 0 .71' scale='1 1 1' />";
	}

else # render video room
	{
	echo "<AssetVideo id='vid_id' src='". $_GET["video"] ."&amp;X-Plex-Token=".$PLEX_TOKEN."' />";
	echo "<AssetImage id='play' src='assets/play.gif' />";
	echo "</Assets>";
	echo "<Room  use_local_asset='room_box_medium'  default_sounds='false' col='0.10 0 0' pos='0.00 0.00 -17.4' skybox_left_id='black' skybox_right_id='black' skybox_front_id='black' skybox_back_id='black' skybox_up_id='black' skybox_down_id='black'>\n";
	echo "<Video id='vid_id' pos='0.2 3.8 -0.2'  scale='10.000 10.000 10.000' xdir='-1.00 0.00 -0.00' ydir='0.00 1.00 0.00' zdir='0.00 0.00 -1.00' thumb_id='play' />";
	echo "<Text pos='11 3.4 -17.600' scale='2.500 2.200 1.000' xdir='-0.00 0.00 1.00' ydir='0.00 1.00 0.00' zdir='-1.00 0.00 -0.00'>". $_GET["title"] ."</Text>";
}

	echo "</Room></FireBoxRoom></body></html>";
?>
