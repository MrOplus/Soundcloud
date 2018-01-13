        $(document).ready(function(e) {
			$("#btn").click(function(){
					 if($("#musiclink").val() != ""){
					 $("#downloadsection").slideUp("slow");
					 $("#btn").val("Please Wait...");
					 $("#btn").unbind("click");
					 GetLink($("#musiclink").val(),callback);
				 }else{
					  return false;   
				 }
			});
            function callback(mp3title,mp3link,rtmplink){
				$("#btn").click(function(){
						 if($("#musiclink").val() != ""){
						 $("#downloadsection").slideUp("slow");
						 $("#btn").val("Please Wait...");
						 $("#btn").unbind("click");
						 GetLink(encodeURIComponent($("#musiclink").val()),callback);
					 }else{
						  return false;   
					 }
				});
				rtmplink = (typeof rtmplink === "undefined") ? "" : rtmplink;
				mp3link = (typeof mp3link === "undefined") ? "Undefined Mp3 Link" : mp3link;
				mp3title = (typeof mp3title === "undefined") ? "Undefined Mp3 Ttitle" : mp3title;
				$("#uploadsection").slideUp("slow",function(e){
					if(mp3link != "Invalid Music Link"|| mp3link != "Undefined Mp3 Link" || mp3link !="Request Timeout"){
						$("#DownloadLink").text("Download " + mp3title );
						$("#DownloadLink").attr("href",mp3link);
						$("#DownloadLink").attr("download",mp3title + ".mp3");
						$('#Download').unbind('click');
						$("#Download").click(function(e) {
							$("#btn").val("Go!");
							$("#downloadsection").slideUp("slow",function(e){
							$("#uploadsection").slideDown("slow");
							$("#musiclink").val("");
							});							
						});
					}else{
						$("#DownloadLink").text("Invalid Music Link");
						$("#DownloadLink").attr("href","#");
						$("#DownloadLink").removeAttr("download");
						$("#Download").click(function(e) {
							$("#btn").val("Go!");
							$("#downloadsection").slideUp("slow",function(e){
							$("#uploadsection").slideDown("slow");
							});
						});
					}
					$("#downloadsection").slideDown("slow");
				});
	
            }
        });