<?php
#URL requirements - local network only
require_once('settings.php');
$TRANSCODER=$PLEX_URL."/photo/:/transcode?width=900&height=600&url=";
$LOCATION = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$content = file_get_contents( $PLEX_URL . "/library/sections/".$SECTIONID."/all?X-Plex-Token=".$PLEX_TOKEN);

$count=0; #element counter
$rowcounter=1;
$poscount=15;


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
		echo "<Link draw_glow='false' url='".$LOCATION."?video=".$PLEX_URL.$video."&amp;title=".urlencode($title)."&amp;X-Plex-Token=".$PLEX_TOKEN."' draw_text='false' thumb_id='img_".$id."' pos='".$poscount." 0 -4 ' xdir='1 0 0' ydir='0 1 0' zdir='0 0 1' scale='3.300 3.800 1.000' title='".urlencode($title)."'/>\n";

	$count++;
	$poscount=$poscount+4;
	if($count==60){break;}

	}

	echo "<Image id='plex_logo' pos='46 17 -1' xdir='0 0 1' ydir='0 1 0' zdir='-1 0 0' scale='3.6 5.2 3' />";
	echo "<Image id='exit' pos='8.5 3.6 -33.8' xdir='.71 0 .71' ydir='0 1 0' zdir='-.71 0 .71' scale='1 1 1' />";
	}

else # render video room
	{
	echo "<AssetVideo id='vid_id' src='". $_GET["video"] ."&amp;X-Plex-Token=".$PLEX_TOKEN."' />";
	echo "<AssetImage id='play' src='assets/play.gif' />";
	echo "</Assets>";
	echo "<Room  use_local_asset='room_box_medium'  default_sounds='false' col='0.10 0 0' pos='0.00 0.00 -17.4' skybox_left_id='skybox_left' skybox_right_id='black' skybox_front_id='black' skybox_back_id='black' skybox_up_id='black' skybox_down_id='black'>\n";
	echo "<Video id='vid_id' pos='0.2 3.8 -0.2'  scale='10.000 10.000 10.000' xdir='-1.00 0.00 -0.00' ydir='0.00 1.00 0.00' zdir='0.00 0.00 -1.00' thumb_id='play' />";
	echo "<Text pos='11 3.4 -17.600' scale='2.500 2.200 1.000' xdir='-0.00 0.00 1.00' ydir='0.00 1.00 0.00' zdir='-1.00 0.00 -0.00'>". $_GET["title"] ."</Text>";
}

	echo "</Room></FireBoxRoom></body></html>";
?>
