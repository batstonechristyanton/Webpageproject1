"use strict"

$( document ).ready(function() {  

//=======================Delete Dialog Box=======================//
var $delete = $( '.delete' );

var $li = $( '#editDelete' ).parent();
$( "#dialog-confirm" ).dialog({autoOpen: false});

$delete.on('click', function(ev) {
	ev.preventDefault();
	var $this = $( this );
	var $link = $this.attr( 'href' );
	var $item = $this.parent().parent();
	$( "#dialog-confirm" ).dialog({
		autoOpen: true,
		resizable: false,
		height: "auto",
		width: 400,
		modal: true,
		buttons: {
			"Delete Item": function() {
				$.post( $link )
				.done(function(){
					$( '#dialog-confirm' ).dialog( "close" );
					$item.remove();
				});
			},
			Cancel: function() {
				$( '#dialog-confirm' ).dialog( "close" );
			}
		}
	});		
});

//=======================Wish Details Box=======================//
var $details = $('.details');

var dialogBox2 = $( "#dialog-wish-details" ).dialog({
  autoOpen:false,
  resizable: false,
  height: "auto",
  width: 800,
  modal: true,
});
 
$details.on('click', function(ev){
  ev.preventDefault(); 
  var $this = $( this );  
  var $link = $this.attr('href');  
  dialogBox2.load($link);  
  dialogBox2.dialog('open');
});

//=======================Date Picker Code=======================//
$( function() {
	$( "#fromdate" ).datepicker();
});

$( function() {
	$( "#todate" ).datepicker();
});

//=======================Username Check=======================//
var uExist=false;
$("#username").on("blur", function(ev){
  var $aerror = $("#ajaxerror");
  var $error = $("#usernameerror");
  var $ajaxmessage = $("#ajaxmessage");
  $error.text("");
  $aerror.text("");
  $.get( "checkusername.php", { username:$("#username").val() } )
  
  .done(function( data ) {
   if(data>0){
    $aerror.text("Your username already exist " + "="+ $("#username").val());
    uExist = true;
  }
})

  .fail(function(jqXHR, textStatus, errorThrown) {
    $ajaxmessage.text(jqXHR.responseText);
       });
  
});

  //=======================Error Checks=======================//
  $("button").eq(0).on("click", function(ev){
    var valid = true;

    if($("#email").val().length==0){
      $("#emailerror").addClass("error");
      $("#emailerror").removeClass("noerror");
      valid=false;
    }else{
      $("#emailerror").addClass("noerror");
      $("#emailerror").removeClass("error");
    }

    if($("#name").val().length==0){
      $("#nameerror").addClass("error");
      $("#nameerror").removeClass("noerror");
      valid=false;
    }else{
      $("#nameerror").addClass("noerror");
      $("nameerror").removeClass("error");
    }

    if($("#title").val().length==0){
      $("#titleerror").addClass("error");
      $("#titlerror").removeClass("noerror");
      valid=false;
    }else{
      $("#titleerror").addClass("noerror");
      $("#titleerror").removeClass("error");
    }
    
    if(!valid)
      ev.preventDefault();
  });

//=======================Password Dialog=======================//
var pass=false;
var dialogBox3 = $( "#dialog-confirm3" ).dialog({
  autoOpen: true,
  resizable: false,
  height: "auto",
  width: 500, 
  modal: true,
  buttons: {
    "Sign In": function() {
          var $error = $("#ajaxerror");
          $.get( "checklistpass.php", { lpassword: $("#lpassword").val() } )
          .done(function( data ) {
            if(data>0){
              $error.text("Correct password ");
              pass = true;
              dialogBox3.dialog( "close" );
            }
            else{
             $error.text("Write the correct password");
             pass=false;
           }

         })
          .fail(function(jqXHR, textStatus, errorThrown) {
           $ajaxmessage.text(jqXHR.responseText);
         });
        },
        Cancel: function() {
          dialogBox3.dialog( "close" );
          var $main = $("main");
          $main.html("<h2>You don't have the permission to view this list</h2>"); 
        }
      }
    });

//=======================Strikethrough Button=======================//
var $button = $( '.button' );
var $clickedlink;
$button.on('click', function(ev) {
  ev.preventDefault();
  $clickedlink =$(this);
  var x = $(this).attr("href");
  $.post( x)
  .done( function(data ) {

    if($clickedlink.prev().attr("class")=="checked"){
      $clickedlink.prev().removeClass("checked");
      $clickedlink.text("Checkoff");
      var href = $clickedlink.attr("href");
      var url = href.split('.',1); 
      $clickedlink.attr('href', href.replace(url, 'checkoff'));
    }

    else{
      $clickedlink.prev().addClass("checked");
      $clickedlink.text("Uncheck");
      var href = $clickedlink.attr("href");
      var url = href.split('.',1);
      $clickedlink.attr('href', href.replace(url, 'uncheck') );
    }

  })

  .fail(function(jqXHR, textStatus, errorThrown) {
    $ajaxmessage.text(jqXHR.responseText);
  });;

}); 

//=======================Password Strength=======================//
$('#password').pwstrength(); 

});