<?php
class SoundCloud{
	private $mLink = "" ;
	private $mLastError = 0 ;
	private $mClientId = "q241TWlfmzYAESNz2Y7GNCbSGeQ6mKL2";
	private $mAppVersion = "1543583184";
	private $mResolverApi = "https://api.soundcloud.com/resolve?";
	function __construct($link){
		$host = parse_url($link,PHP_URL_HOST);
		if($host == "soundcloud.com"){
	 		$this->mLink = $link;
		}else{
			$this->mLastError = -1;
		}
	}
	public function getLink(){
		try{
			$encoded_url = urlencode($this->mLink);
			$resolveFields = array(
				"url" => $encoded_url,
				"_status_code_map%5B302%5D" =>"200",
				"_status_format"=>"json",
				"client_id"=> $this->mClientId,
				"app_version"=> $this->mAppVersion);
			$resolveRes = $this->makeRequest($this->mResolverApi,$resolveFields);
			$resolveJson = json_decode($resolveRes,true);
			if (isset($resolveJson['status']) && $resolveJson['status'] == '302 - Found'){
				$info = $this->makeRequest($resolveJson['location']);
				$infoJson = json_decode($info,true) ;
				$id = $infoJson['id'];
				$title = $infoJson['title'];
				$cdnUrl = "https://api.soundcloud.com/i1/tracks/" . $id .
				"/streams?client_id=$this->mClientId&app_version=$this->mAppVersion";
				$cdnRequest = $this->makeRequest($cdnUrl);
				$cdnJson = json_decode($cdnRequest,true);
				if(isset($finalJson['rtmp_mp3_128_url'])){
					$return = array(
						"title" => (string) $title,
						"mp3link" => $cdnJson['http_mp3_128_url'],
						"rtmplink" => $cdnJson['rtmp_mp3_128_url']
					);
				}else{
					$return = array(
						"title" => (string) $title,
						"mp3link" => $cdnJson['http_mp3_128_url']
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
	private function makeRequest($url,$fields = ''){
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
	public function getLastError(){
	    return $this->mLastError;
    }
}
