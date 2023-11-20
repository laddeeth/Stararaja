$(document).ready(function(){
  'use strict';
	$("#formcontainerforgot").keypress(function(e) {
	    if(e.which == 13) {
				e.preventDefault();
				$("#dosend").click();
	    }
	});
	$("#dosend").click(function() {
		var reg = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
		if(!reg.test($('#formforgotpassword input[name="email"]').val())) {
			$("#forgotmessage").html("<img src='img/warning.png'>Not a valid e-mail adress!").css('visibility', 'visible');
		}
		else {
			var form = $("#formforgotpassword").serialize();
			$.ajax({
			  type : 'POST',
			  url : '../src/forgotprocess.php',
			  dataType : 'text',
			  data: form,
			  success : function(data){
					$('#forgotmessage').html(data).css('visibility', 'visible');
			      },
			  error : function(XMLHttpRequest, textStatus, errorThrown) {
		      
			  }
			});
		}
	});

});