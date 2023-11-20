$(document).ready(function(){
  'use strict';

	/*$('#authinfo').css({"min-height": $('#yuimage').height()});*/
	
	var count = 12;
	  var countdown = setInterval(function(){
	    $("p.countdown").html($("p.countdown").attr('text1') + "<br> <a href='index.php'>Stararaja.com</a> <br>" + $("p.countdown").attr('text2') + "<br><span id='countview'>" + count + "</span>");
	    if (count == 0) {
	      clearInterval(countdown);
	      window.open('index.php', "_self");

	    }
	    count--;
	  }, 1000);
});