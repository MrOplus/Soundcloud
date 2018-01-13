//Coded By Mr.8Th BiT
function GetLink(musicLink,callback){
	$.ajax({
		method: "get",
		url: "engine.php?musicLink="+musicLink,
		dataType: "json",
		timeout: (60 * 1000),
		success: function(data){
					if(data.status == "302 - Found" ){
						callback (data.title,data.mp3link,data.rtmplink);
					}
		},
		error: function( objAJAXRequest, strError ){
		callback (strError,"#","#");
		}
	});
}