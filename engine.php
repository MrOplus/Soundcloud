<?php 
require "includes/class.php";
if(isset($_GET['musicLink']) && isset($_GET['desktop'])){
	$s = new SoundCloud($_GET['musicLink']);
	if($s->LastErr == -1){
		echo("error");
	}else{
	$details = $s->GetLink();
		if($s->LastErr == 0){
			$title = $details['title'];
			$title = str_replace("|","-",$title);
			$mp3link = $details['mp3link'];
			$enc = "" ;
			if(strlen($mp3link) < 10){
				exit(0);
			}
			for($i = 0 ; $i < strlen($mp3link) ; $i++){
				$enc .= substr($mp3link,$i,1) . "*" ;
			}
			echo($title . "|" . $enc);
		}
	}
}else if(isset($_GET['musicLink'])){
$s = new SoundCloud($_GET['musicLink']);
	if($s->LastErr == -1){
		$output = array("status" => "404 - Not Found");
		echo(json_encode($output));	
	}else{
	$details = $s->GetLink();
		if($s->LastErr == 0){
			$title = $details['title'];
			$mp3link = $details['mp3link'];
			if(isset($details['rtmplink'])){
				$output = array(
					"status" => "302 - Found",
					"mp3link" => $mp3link ,
					"title" => $title,
					"rtmplink" => $details['rtmplink']
				);
			}else{
				$output = array(
					"status" => "302 - Found",
					"mp3link" => $mp3link ,
					"title" => $title,
				);				
			}
			echo(json_encode($output));
		}
	}
}
?>