$(document).ready(function(){
  'use strict';
	
	$("#dochange").click(function() {
		$('input[name="password"]').css({"border": "1px solid #34516e", "background-color": "white"});
		$('input[name="confirmpassword"]').css({"border": "1px solid #34516e", "background-color": "white"});
		//PASSWORD 
		if($('input[name="password"]').val().length < 6) {
			$('input[name="password"]').css({"border": "1px solid red", "background-color": "#FFE6F0"});
			$("#forgotmessage").html("<img src='img/warning.png'>Password must be at least 6 charachters long.").css('visibility', 'visible');
		}
		//CONFIRM PASSWORD
		else if($('input[name="confirmpassword"]').val() !== $('input[name="password"]').val()) {
			$('input[name="confirmpassword"]').css({"border": "1px solid red", "background-color": "#FFE6F0"});
			$("#forgotmessage").html("<img src='img/warning.png'>Confirmation doesn't match password.").css('visibility', 'visible');
		}
		else {
			$("#forgotmessage").remove();
			$("#dochange").remove();
			$("#loadingimage").css('visibility', 'visible');
			var form = $("#changepassword").serialize();
			$.ajax({
			  type : 'POST',
			  url : '../src/changeprocess.php',
			  dataType : 'text',
			  data: form,
			  success : function(data){
					$("#loadingimage").css('visibility', 'hidden');
					var count = 12;
					  var countdown = setInterval(function(){
					    $("#changemain").html(data + "<br><br>" + $("#changemain").attr('text1') + "<br> <a href='index.php'>Stararaja.com</a> <br>" + $("#changemain").attr('text2') + "<br><br><span id='countview'>" + count + "</span>").css('text-align', 'center');
					    if (count == 0) {
					      clearInterval(countdown);
					      window.open('index.php', "_self");

					    }
					    count--;
					  }, 1000);
			      },
			  error : function(XMLHttpRequest, textStatus, errorThrown) {
			  }
			});
			
		}
		
	});
	
});