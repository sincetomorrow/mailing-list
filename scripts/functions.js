// JavaScript Document

function validateEmail(email) { 
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
} 

$(document).ready(function(){

$(".show").click(function(e) {
	e.preventDefault();
	var mc = $(this).parent().find(".more");
	//var $mc = $(this).next("exp_content");
	mc.toggle();
});
$(".many_emails").submit(function(e) {
	e.preventDefault();
	//var emails = $(".many_emails textarea").val();
	//var array = string.split(',');
	$(".outcome").html("go");
	$.post("library/import_email.php",$(this).serialize(),function(data) {
		$(".outcome").html(data);
	});
});
	
});