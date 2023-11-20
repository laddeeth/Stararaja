$(document).ready(function(){
  'use strict';

//Login register tab
	
	$("#loginbutton").click(function() {
		$(this).removeClass().addClass("selectedlog");
		$("#signupbutton").removeClass().addClass("unselectedlog");
		$("#login").removeClass();
		$("#register").removeClass().addClass("hidden");
	});
	
	$("#signupbutton").click(function() {
		$(this).removeClass().addClass("selectedlog");
		$("#loginbutton").removeClass().addClass("unselectedlog");
		$("#register").removeClass();
		$("#login").removeClass().addClass("hidden");
	});
	
//Login handling
	$("#dologin").click(function(event) {
		var reg = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
		if(!reg.test($('#loginform input[name="email"]').val())) {
			$("#loginmessage").html("<img src='img/warning.png'>" + $('#loginform input[name="email"]').attr("errortext")).css('display', 'block');
		}
		else if($('#loginform input[name="password"]').val().length < 6){
			$("#loginmessage").html("<img src='img/warning.png'>" + $('#loginform input[name="password"]').attr("errortext")).css('display', 'block');
		}
		else {
			var form = $("#loginform").serialize();
			$.ajax({
			  type : 'POST',
			  url : '../src/loginprocess.php',
			  dataType : 'text',
			  data: form,
			  success : function(data){
					window.open(data, "_self");
			      },
			  error : function(XMLHttpRequest, textStatus, errorThrown) {
		      
			  }
			});
		}
	});
	
	
//Registry form handling

$("#countryletter").change(function() {
	$.get( "../src/geo/select/" + $(this).val() + ".inc", function(data){
		$("#country").empty();
	  $("<option value='notselected'>--</option>" + data).appendTo("#country");
	}, 'text' );
	 $("#countryletter option[value='notselected']").remove();
	 $("#cityletter").empty().append("<option value='notselected'>--</option>");
	 $("#country option[value='notselected']").remove();
	 $("#city").empty().append("<option value='notselected'>--</option>");
 });
 
 $("#birthday").birthdayPicker({
	 minAge: 1,
	 dateFormat: "bigEndian",
	 sizeClass: "span2"
	 
 });
 

$("#country").change(function() {
	$.get( "../src/geo/select/" + $(this).val() + "/letter.inc", function(data){
		$("#cityletter").empty();
	  $("<option value='notselected'>--</option>" + data.toUpperCase()).appendTo("#cityletter");
	}, 'text' );
	$("#city").empty().append("<option value='notselected'>--</option>");
	$("#country option[value='notselected']").remove();
});

$("#cityletter").change(function() {
	$.get( "../src/geo/select/" + $("#country").val() + "/" + $(this).val() + ".inc", function(data){
		$("#city").empty();
	  $("<option value='notselected'>--</option>" + data).appendTo("#city");
	}, 'text' );
	 $("#cityletter option[value='notselected']").remove();
});

$("#city").change(function() {
	$("#city option[value='notselected']").remove();
});

$("#countryfrom").change(function() {
	if($(this).val() == "Other"){
		$("#cityletterfrom").empty();
		$("<option value='O'>O</option>").appendTo("#cityletterfrom");
		$("#cityfrom").empty().append("<option value='Other'>Other</option>");
		$("#countryfrom option[value='notselected']").remove();
	}
	else {
		$.get( "../src/geo/select/" + $(this).val() + "/letter.inc", function(data){
			$("#cityletterfrom").empty();
		  $("<option value='notselected'>--</option>" + data.toUpperCase()).appendTo("#cityletterfrom");
		}, 'text' );
		$("#cityfrom").empty().append("<option value='notselected'>--</option>");
		$("#countryfrom option[value='notselected']").remove();
	}
});

$("#cityletterfrom").change(function() {
	$.get( "../src/geo/select/" + $("#countryfrom").val() + "/" + $(this).val() + ".inc", function(data){
		$("#cityfrom").empty();
	  $("<option value='notselected'>--</option>" + data).appendTo("#cityfrom");
	}, 'text' );
	 $("#cityletterfrom option[value='notselected']").remove();
});

$("#cityfrom").change(function() {
	$("#cityfrom option[value='notselected']").remove();
});


//Registerformvalidaton-----------------------------------
	//Återställ submit error designchanges
	$('#register input[type=text], #register input[type=password]').focus(function() {
		$(this).css({"border": "1px solid #34516E", "background-color": "white"});
});

$('.birthYear, .birthMonth, .birthDate, #countryletter, #country, #cityletter, #city, #countryfrom, #cityletterfrom, #cityfrom').focus(function() {
		$(this).css({"background-color": "white"});
});

$("input[name='gender']").click(function() {
	$("input[name='gender']").prev("span").remove();
});

  //REGISTER SUBMIT

$("#doregister").click(function(event) {
	
	//Förhindra default bettende hos form
  event.preventDefault();
	
	//CHECK FORM
	var success = true;
	var errors = [];
	//EMAIL
	var reg = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
	if(!reg.test($('#register input[name="email"]').val())) {
		$('#register input[name="email"]').css({"border": "1px solid red", "background-color": "#FFE6F0"});
		success = false;
		errors.push($('#register input[name="email"]').attr("errortext"));
	}
	
	//CONFIRM EMAIL
	if($('#register input[name="confirmemail"]').val() !== $('#register input[name="email"]').val() || $('#register input[name="confirmemail"]').val().length < 2) {
		$('#register input[name="confirmemail"]').css({"border": "1px solid red", "background-color": "#FFE6F0"});
		success = false;
		errors.push($('#register input[name="confirmemail"]').attr("errortext"));
	}
	
	//PASSWORD 
	if($('#register input[name="password"]').val().length < 6) {
		$('#register input[name="password"]').css({"border": "1px solid red", "background-color": "#FFE6F0"});
		success = false;
		errors.push($('#register input[name="password"]').attr("errortext"));
	}
	
	//CONFIRM PASSWORD
	if($('#register input[name="confirmpassword"]').val() !== $('#register input[name="password"]').val()) {
		$('#register input[name="confirmpassword"]').css({"border": "1px solid red", "background-color": "#FFE6F0"});
		success = false;
		errors.push($('#register input[name="confirmpassword"]').attr("errortext"));
	}
	
	//NAME
	var reg = XRegExp("^\\p{L}+$");
	if(!reg.test($('#register input[name="name"]').val()) || $('#register input[name="name"]').val().length < 2) {
		$('#register input[name="name"]').css({"border": "1px solid red", "background-color": "#FFE6F0"});
		success = false;
		errors.push($('#register input[name="name"]').attr("errortext"));
	}
	
	//LASTNAME
	if(!reg.test($('#register input[name="lastname"]').val()) || $('#register input[name="lastname"]').val().length < 2) {
		$('#register input[name="lastname"]').css({"border": "1px solid red", "background-color": "#FFE6F0"});
		success = false;
		errors.push($('#register input[name="lastname"]').attr("errortext"));
	}
	
	//BIRTHDATE
	if($('.birthYear').val() == 0 || $('.birthMonth').val() == 0 || $('.birthDate').val() == 0) {
		$('.birthYear').css({"background-color": "#FFE6F0", "font-family": "arial", "font-size": "61.2%"});
		$('.birthMonth').css({"background-color": "#FFE6F0", "font-family": "arial", "font-size": "61.2%"});
		$('.birthDate').css({"background-color": "#FFE6F0", "font-family": "arial", "font-size": "61.2%"});
		success = false;
		errors.push($('#register #birthday').attr("errortext"));
	}
	else {
		$('.birthYear, .birthMonth, birthDate').css({"background-color": "white"});
	}
	
	//GENDER
	if (!$("input[name='gender']").is(':checked') && $('input[name="gender"]').prev("span").html() != "*") {
		$('input[name="gender"]').before("<span style='color:red;'>*</span>");
		success = false;
		errors.push($("#register input[name='gender']").attr("errortext"));
	}
	
	//COUNTRY
	if($('#countryletter').val() == "notselected") {
		$('#countryletter').css({"background-color": "#FFE6F0", "font-family": "arial", "font-size": "61.2%"});
		success = false;
	}
	
	if($('#country').val() == "notselected") {
		$('#country').css({"background-color": "#FFE6F0", "font-family": "arial", "font-size": "61.2%"});
		success = false;
	}
	
	//CITY
	if($('#cityletter').val() == "notselected") {
		$('#cityletter').css({"background-color": "#FFE6F0", "font-family": "arial", "font-size": "61.2%"});
		success = false;
	}
	
	if($('#city').val() == "notselected") {
		$('#city').css({"background-color": "#FFE6F0", "font-family": "arial", "font-size": "61.2%"});
		success = false;
		errors.push($("#register #city").attr("errortext"));
	}
	
	//COUNTRYFROM
	if($('#countryfrom').val() == "notselected") {
		$('#countryfrom').css({"background-color": "#FFE6F0", "font-family": "arial", "font-size": "61.2%"});
		success = false;
	}
	
	//CITYFROM
	if($('#cityletterfrom').val() == "notselected") {
		$('#cityletterfrom').css({"background-color": "#FFE6F0", "font-family": "arial", "font-size": "61.2%"});
		success = false;
	}
	
	if($('#cityfrom').val() == "notselected") {
		$('#cityfrom').css({"background-color": "#FFE6F0", "font-family": "arial", "font-size": "61.2%"});
		success = false;
		errors.push($("#register #cityfrom").attr("errortext"));
	}
	
	//EVERYTHING OKAY, TAKE ACTION
	if(success) {
		$("#doregister").remove();
		$("#loadingimage").css('visibility', 'visible');
		var form = $("#registerform").serialize();
		$.ajax({
		  type : 'POST',
		  url : '../src/registerprocess.php',
		  dataType : 'text',
		  data: form,
		  success : function(data){
				$("#loadingimage").css('visibility', 'hidden');
				$('#register').html(data);
		      },
		  error : function(XMLHttpRequest, textStatus, errorThrown) {
		      
		  }
		});
	}
	else {
		var errorDiv = $('<div id="registererrors"><a id="close" href="#"></a></div>');
		errors.forEach(function(item) {
			errorDiv.append("<p>" + item + "</p>");
		});
	$('<div id="overlay"></div>')
	  .css('opacity', '0')
	  .animate({'opacity' : '0.84'}, 'normal')
	  .appendTo('body');
		
		$('body').append(errorDiv);
		$('#registererrors p').prepend("- ");
		$('#registererrors').center();
		
		$('#close, #overlay').click(function() {
			$('#registererrors, #overlay').remove();
		});
		
	}
	
	
	
	
});


});