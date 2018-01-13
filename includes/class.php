<?php
class SoundCloud{
	private $_link ;
	public $LastErr = 0;
	function __construct($link){
		$host = parse_url($link,PHP_URL_HOST);
		if($host == "soundcloud.com"){
	 		$this->_link = $link;
		}else{
			$this->LastErr = -1;
		}
	}
	public function GetLink(){
		try{
			$client_id = "UytiOw5CoZz7YuKteRrXYZQcGjwGohXl" ;
			$app_version = "1515756093" ;
			$current_encode = urlencode($this->_link);
			$resolveUrl = "https://api.soundcloud.com/resolve?" ;
			$resolveFields = array(
				"url" => $current_encode,
				"_status_code_map%5B302%5D" =>"200",
				"_status_format"=>"json",
				"client_id"=>$client_id,
				"app_version"=>$app_version);	
			$resolveRes = $this->SendGet($resolveUrl,$resolveFields);
			$resolveJson = json_decode($resolveRes,true);
			if (isset($resolveJson['status']) && $resolveJson['status'] == '302 - Found'){
				$xmlString =$this->SendGet($resolveJson['location']);
				$xmljson = json_decode($xmlString) ;				
				$id = $xmljson->id;
				$title = $xmljson->title;
				$finalLink = "https://api.soundcloud.com/i1/tracks/" . $id .
				"/streams?client_id=$client_id&app_version=$app_version";
				$finalRequest = $this->SendGet($finalLink);
				$finalJson = json_decode($finalRequest,true);
				if(isset($finalJson['rtmp_mp3_128_url'])){
					$return = array(
						"title" => (string) $title,
						"mp3link" => $finalJson['http_mp3_128_url'],
						"rtmplink" => $finalJson['rtmp_mp3_128_url']
					);
				}else{
					$return = array(
						"title" => (string) $title,
						"mp3link" => $finalJson['http_mp3_128_url']
					);
				}
				return $return;
			}else{
				$this->LastErr = 1;
				$return = array(
						"title" => "Invalid Music Link",
						"mp3link" => "Invalid Music Link",
						"rtmplink" => "Invalid Music Link"
				);
				return $return;
			}
		}catch(\Exception $e){
			$this->LastErr = 2;
			$return = array(
					"title" => "Internal Error",
					"mp3link" => "Internal Error",
					"rtmplink" => "Internal Error"
			);
			return $return;				
		}
	}
	private function SendGet($url,$fields = ''){
		try{
			$fields_string = '' ;
			if($fields != '' ){
				foreach($fields as $key=>$field){
					$fields_string .= $key . '=' . $field . '&' ;
				}
			}
			return @file_get_contents($finalurl = $url . $fields_string);
		}catch(Exception $ex){
			$this->LastErr = 3;
			return $ex;
		}
	}
}
?>
