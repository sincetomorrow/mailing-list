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

$(".email2list").click(function(e) {
	e.preventDefault();
	var mc = $(this);
	var emailid=$(this).attr("emailid");
	var listid=$(this).attr("listid");
	var a = $(this).attr("a");
	var confirmed = '1';
	
	$.post("library/list_email.php",{emailid:emailid,listid:listid,a:a,confirmed:confirmed},function(data) {
		if(a=="add") {
			mc.attr("a","delete");
			mc.html("<img src='images/bt_listemail.png'/>");
		}
		else if(a=="delete") {
			mc.attr("a","add");
			mc.html("<img src='images/bt_add2.png'/>");
		}
	});
});
	
});