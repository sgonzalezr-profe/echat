<?php
//Controlling if there is a valid session
session_start();

if (array_key_exists("username", $_COOKIE) && $_COOKIE['username']) {

	$_SESSION['username'] = $_COOKIE['username'];

} else {
	header("Location: index.php");
}
?>
<!DOCTYPE html>
<!------
** For chat bootstrap template:

Snippets License (MIT license)
Copyright (c) 2013 Bootsnipp.com

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

** The rest of the code was inspired in a Rob Percival's course (The Complete Web Developer Course 2.0) and my knowledge about JS and AJAX.
---------->
<html>
<head>
	<title>Chat</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.min.css">
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.min.js"></script>
	<link rel="stylesheet" href="styles.css">
</head>
<!--Coded With Love By Mutiullah Samim-->
<body>
	<div class="container-fluid h-50">
		<div class="row justify-content-center h-100">
			<div class="col-md-4 col-xl-3 chat">
				<div class="card mb-sm-3 mb-md-0 contacts_card">
					<div class="card-header">
						<div class="input-group">
							<input type="text" placeholder="Search... (not implemented)" name="" class="form-control search">
							<div class="input-group-prepend">
								<span class="input-group-text search_btn"><i class="fas fa-search"></i></span>
							</div>
						</div>
					</div>
					<div class="card-body contacts_body">
						<ui class="contacts" id="contacts">
						</ui>
					</div>
					<div class="card-footer"></div>
				</div>
			</div>
			<div class="col-md-8 col-xl-6 chat">
				<div class="card">
					<div class="card-header msg_head">
						<div class="d-flex bd-highlight">
							<div class="img_cont" id="div_img_big">
								<img src="" class="rounded-circle user_img">
								<!-- <span class="online_icon"></span> -->
							</div>
							<div class="user_info">
								<span id="userName">Chat with unknown</span>
								<p id="nMessages">0 Messages</p>
							</div>
							<!-- <div class="video_cam">
								<span><i class="fas fa-video"></i></span>
								<span><i class="fas fa-phone"></i></span>
							</div> -->
						</div>
						<span id="action_menu_btn"><i class="fas fa-ellipsis-v"></i></span>
						<div class="action_menu">
							<ul>
								<li><i class="fas fa-user-circle"></i> View profile (not implemented)</li>
								<!-- 								
								<li><i class="fas fa-users"></i> Add to close friends</li>
									<li><i class="fas fa-plus"></i> Add to group</li>
								-->
								<li><i class="fas fa-ban"></i> Block (not implemented)</li>
								<li id="logout"><i class="fas fa-sign-out-alt"></i>Logout</li>

							</ul>
						</div>
					</div>
					<div class="card-body msg_card_body" id="messages">
<!-- 						<div class="d-flex justify-content-start mb-4">
							<div class="img_cont_msg">
								<img src="https://static.turbosquid.com/Preview/001292/481/WV/_D.jpg" class="rounded-circle user_img_msg">
							</div>
							<div class="msg_cotainer">
								Hi, how are you samim?
								<span class="msg_time">8:40 AM, Today</span>
							</div>
						</div> -->
					</div>
					<div class="card-footer">
						<div class="input-group">
							<div class="input-group-append">
								<span class="input-group-text attach_btn"><i class="fas fa-paperclip"></i></span>
							</div>
							<textarea name="" class="form-control type_msg" placeholder="Type your message..." id="message_text"></textarea>
							<div class="input-group-append bg-li" id="send">
								<span class="input-group-text send_btn"><i class="fas fa-location-arrow"></i></span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		var currentSender = "<?php echo $_SESSION['username'] ?>"; //current_user from session variable
		var currentReceiver = ""; //user with the conversation
		var users = null; //all user data
		var iSender = 0; //index in the user array of sender
		var iReceiver = 0; //index in the user array of receiver
		var messages = null; //all messages of the current conversation
		var firstTime = true; //load the first user the first load
		

		//paint all received messages of the current conversation
		function paint_messages() {
			
			//remove everything before updating
			$("#messages").empty();

			//looking for the data about currentSender and currentReceiver
			for(var i = 0; i < users.length; i++) {
				if (users[i].username == currentSender) {
					iSender = i;
				} else if (users[i].username == currentReceiver) {
					iReceiver = i;
				} else if (iReceiver != 0 && iSender != 0) {
					break;
				}
			}

			$.each(messages, function(key, value) {
				//console.log('value["sender_id"]='+value["sender_id"]+', value["sender_id"]='+value["receiver_id"]);
				if (value["sender_id"] == currentSender) {
					$("#messages").append(paint_message("justify-content-end", users[iSender].mime, users[iSender].image,
						value["content"], value["tmessage"], "msg_cotainer_send"));
				} else {
					$("#messages").append(paint_message("justify-content-start", users[iReceiver].mime, users[iReceiver].image,
						value["content"], value["tmessage"], "msg_cotainer"));
				}
			});
			//I set the number of messages
			$("#nMessages").html(messages.length + " Messages");

			//scroll to the last
			$('#messages').scrollTop($('#messages')[0].scrollHeight);
		}

		//creates a single message in the chat
		function paint_message(pClass, pMime, pImage, pContent, pTMessage, pClass2) {
			var str = '<div class="d-flex '+ pClass + ' mb-4">';
			str += '<div class="img_cont_msg">';
			str += '<img src="data:' + pMime + ';base64,' + pImage + '" class="rounded-circle user_img_msg">';
			str += '</div>';
			str += '<div class="' + pClass2 + '">';
			str += pContent;
			str += '<span class="msg_time">' + pTMessage + '</span>';
			str += '</div>';
			str += '</div>';
			return str;
		}

	    //load chat message between pSender and pReceiver
	    function load_messages(pSender, pReceiver) {
	      //requesting data to the server
	      $.ajax({
	      	method: "POST",
	      	url: "messageList.php",
	      	data: {"currentSender": pSender, "currentReceiver": pReceiver},
	      	success: function(data) {
	      		messages = $.parseJSON(data);
	      		paint_messages(messages, users);
	      	},
	      	error: function (request, status, error) {
	      		console.log("error: "+request.responseText);
	      		paint_messages([], users);
	      	}
	      });
	  }

	    //load list of users but not currentSender
	    function load_users() {
	    	
	    	$("#contacts").empty();
	    	$.ajax({
	    		method: "POST",
	    		url: "userList.php",
	    		data: {},
	    		success: function(data) {
	    			users = $.parseJSON(data);
		    		paint_users(users);
	    		}
	    	}).done(function() {
	    		//load user info and messages for the first time
				if (currentReceiver=="") {
		    		for(var i = 0; i < users.length; i++) {
		    			if (users[i].username != currentSender) {
		    				currentReceiver = users[i].username;
		    				iReceiver = i;
		    				break;
		    			}
		    		}
	    		}
	    		load_user_info(users, iReceiver);
	    		load_messages(currentSender, currentReceiver);
	    	});
	    }

	    function load_user_info(pUsers, pIReceiver) {
	    	//change image
	    	$("#div_img_big").empty(); //id doesn't exist

	    	$("#div_img_big").append('<img src="data:'+pUsers[pIReceiver].mime+';base64,'+pUsers[pIReceiver].image+'" class="rounded-circle user_img">');
	    	//change username
	    	$("#userName").html("Chat with " + pUsers[pIReceiver].username);
	    }

	    function paint_users(pUsers) {
	    	$.each(pUsers, function( key, value) {
	    		console.log(value["username"] + "-" + currentSender + "-"+currentReceiver);
	    		if (value["username"] != currentSender) {
	    			var myClass = "inactive"
	    			if (value["username"] == currentReceiver) {
	    				myClass = "active";
	    			}
	    			
	    			paint_user(value["username"], value["mime"], value["image"], myClass);
	    		}
	    	});

			//activate click event
			$('div .user-menu').click(function(){
				console.log("User clicked and load messages");

				$("li .user-menu .user_info span").each(function(index){
					if ($(this).text() == currentReceiver) {
						//the old user is now inactive
						$(this).parent().parent().parent().removeClass("active");
						$(this).parent().parent().parent().addClass("inactive");	
					}
					
				});

				//set new current receiver to the user clicked
				currentReceiver = $(this).parent().find(".user_info span").text();

				//set class "active" for the active user
				$(this).parent().removeClass("inactive");
				$(this).parent().addClass("active");
				//update iReceiver index
				for(var i = 0; i < pUsers.length; i++) {
					if (pUsers[i].username == currentReceiver) {
						iReceiver = i;
						break;
					}
				}
				load_user_info(users, iReceiver);

				load_messages(currentSender, currentReceiver);
			});
		}

		function paint_user(pUser, pMime, pImage, pClass) {
			//Do it better creating DOM nodes
			var str = "";

			str += '<li class="'+pClass+'">';
			str += '<div class="d-flex user-menu">'; //class: bg-highlight?
			str += '<div class="img_cont">';
			str += '<img src="data:' + pMime + ';base64,' + pImage + '" class="rounded-circle user_img">';
			//str += '<!-- <span class="online_icon"></span> -->';
			//str += '<!-- <span class="online_icon offline"></span> -->';
			str += '</div>';
			str += '<div class="user_info">';
			str += '<span>' + pUser + '</span>';
			str += '<p>' + pUser + ' may be online or offline</p>';
			str += '</div>';
			str += '</div>';
			str += '</li>';
			$("#contacts").append(str);

		}

		$(document).ready(function(){
			//activation og click event in the menu
			$('#action_menu_btn').click(function(){
				$('.action_menu').toggle();
			});

			//loading all the users but the current user,
			//all the user information will be stored in the array users
			load_users();

			//when you click a user, message between current user an that user
			//will be loaded
			$('#send').click(function(){
				$.ajax({
					method: "POST",
					url: "updatedatabase.php",
					data: { "content": $("#message_text").val(), "currentSender": currentSender, "currentReceiver": currentReceiver},
					success: function(data) {

						$("#messages").append(paint_message("justify-content-end", users[iSender].mime, users[iSender].image, $("#message_text").val(), data, "msg_cotainer_send"));

						$("#nMessages").html(($("#nMessages").text().split(" Messages")[0]*1+1) + " Messages");

						$('#messages').scrollTop($('#messages')[0].scrollHeight);

						$('#message_text').val("");
					}
				});
			});

			$("#message_text").keypress(function(event) {
			    if (event.keyCode === 13) {
			        $("#send").click();
			        event.preventDefault();
			    }
			});

			$('#logout').click(function() {
				window.location.replace("index.php?logout=1");
			});

			//every two seconds I reload user list and message
			//if there are new ones
			window.setInterval(function(){
				//a better implementation all this is with sockets.io
				//https://www.telerik.com/blogs/building-a-real-time-data-visualization-dashboard-with-jquery-socket.io-and-kendo-ui

				//load users because there could be new ones
				//I'll load all of them if there is a new one
				//** improve it loading only the new ones
				$.ajax({
		    		method: "POST",
		    		url: "numUsers.php",
		    		data: {},
		    		success: function(data) {
		    			var parsedData = $.parseJSON(data);
		    			if (users.length < parsedData[0]["numUsers"]) {
		    				load_users();
		    			}
		    		}
	    		});

				//I'll load all messages if there are new ones
				//** improve it loading only the new ones
				$.ajax({
		    		method: "POST",
		    		url: "numMessages.php",
		    		data: {"currentSender": currentSender, "currentReceiver": currentReceiver},
		    		success: function(data) {
		    			var parsedData = $.parseJSON(data);

		    			if ($("#nMessages").text().split(" Messages")[0]*1 < parsedData[0]["numMessages"]) {
		    				console.log("*entro");
		    				load_messages(currentSender, currentReceiver);
		    			}
		    		}
	    		});
				
			}, 2000);

		});
	</script>
</body>
</html>