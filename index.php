<?php

session_start();

$error = "";  

if (array_key_exists("logout", $_GET)) {

	unset($_SESSION);
	setcookie("username", "", time() - 60*60);
	$_COOKIE["username"] = "";  

	session_destroy();

} else if ((array_key_exists("username", $_SESSION) AND $_SESSION['username']) OR (array_key_exists("username", $_COOKIE) AND $_COOKIE['username'])) {

	header("Location: echat.php");

}

if (array_key_exists("submit", $_POST)) {

	include("connection.php");

	if (!$_POST['username']) {

		$error .= "A username is required<br>";

	} 

	if (!$_POST['password']) {

		$error .= "A password is required<br>";

	} 

	if ($_POST['signUp'] == '1' && !isset($_FILES['file01'])) {
		$error .= "An image is required<br>";
	} elseif ($_FILES["file01"]["error"]>0) {
		$error .= "There was an error uploading your picture.<br>";
	}

	if ($error != "") {

		$error = "<p>There were error(s) in your form:</p>".$error;

	} else {

		if ($_POST['signUp'] == '1') {

			$query = "SELECT * FROM `users` WHERE username = '".mysqli_real_escape_string($link, $_POST['username'])."' LIMIT 1";

			$result = mysqli_query($link, $query);

			if (mysqli_num_rows($result) > 0) {

				$error = "That username is taken.";

			} else {
				//$name = $_FILES['file01']['name'];
				$mime = $_FILES['file01']['type'];
				$data = file_get_contents($_FILES['file01']['tmp_name']);
				$data = base64_encode($data);

				$query = "INSERT INTO users (username, password, image, mime) VALUES ('".mysqli_real_escape_string($link, $_POST['username'])."', '".md5(md5(mysqli_real_escape_string($link, $_POST['username'])).$_POST['password'])."','".$data."','".$mime."')";

				if (!mysqli_query($link, $query)) {

					$error = "<p>Could not sign you up - please try again later.</p>";

				} else {

					//$query = "UPDATE `users` SET password = '".md5(md5(mysqli_insert_id($link)).$_POST['password'])."' WHERE id = ".mysqli_insert_id($link)." LIMIT 1";

					//$id = mysqli_insert_id($link);

					//mysqli_query($link, $query);

					$_SESSION['username'] = $_POST['username'];

					if ($_POST['stayLoggedIn'] == '1') {

						setcookie("username", $_POST['username'], time() + 60*60*24*365); //set to only 1h from current time

					} 

					header("Location: echat.php");

				}

			} 

		} else {

			$query = "SELECT username, password FROM users WHERE username = '".mysqli_real_escape_string($link, $_POST['username'])."'";

			$result = mysqli_query($link, $query);

			$row = mysqli_fetch_array($result);

			if (isset($row)) {

				$hashedPassword = md5(md5(mysqli_real_escape_string($link, $_POST['username'])).$_POST['password']);
				//$hashedPassword = $_POST['password'];

				if ($hashedPassword == $row['password']) {

					$_SESSION['username'] = $row['username'];

					if (isset($_POST['stayLoggedIn']) AND $_POST['stayLoggedIn'] == '1') {

						setcookie("username", $row['username'], time() + 60*60*24*365); //only 1h since current time

					} 

					header("Location: echat.php");

				} else {

					$error = "That username/password combination could not be found.";

				}

			} else {

				$error = "That username/password combination could not be found.";

			}

		}

	}


}


?>
<!DOCTYPE html>
<!------
** This code was inspired in a Rob Percival's course (The Complete Web Developer Course 2.0).
---------->
<html>
<head>
	<title>eChat</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.min.css">
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.min.js"></script>
	<link href='https://fonts.googleapis.com/css?family=Bangers' rel='stylesheet'>
	<link rel="stylesheet" href="styles.css">
</head>
<!--Coded With Love By Mutiullah Samim-->
<body>

	<div class="container" id="homePageContainer">

		<h1 style="font-family: 'Bangers'"><strong>Ephemeral Chat</strong></h1>

		<p><strong>A chat deleted every hour. This website is an example to teach a subject about Databases.</strong></p>

		<div id="error"><?php if ($error!="") {
			echo '<div class="alert alert-danger" role="alert">'.$error.'</div>';

		} ?></div>

		<form method="POST" action="index.php" enctype="multipart/form-data" id="signUpForm">


			<p>Interested? Sign up now!</p>

			<fieldset class="form-group">

				<input class="form-control" type="text" name="username" placeholder="Your username" tabindex="1">

			</fieldset>

			<fieldset class="form-group">

				<input class="form-control" type="password" name="password" placeholder="Password" tabindex="2">

			</fieldset>

			<fieldset class="form-group">
				<div class="custom-file">
					<input type="file" class="custom-file-input form-control" name="file01" id="file01" tabindex="3">
					<label class="custom-file-label form-control" for="file01" id="labelfile01">Choose your image</label>
				</div>
			</fieldset>

			<div class="checkbox">

				<label>

					<input type="checkbox" name="stayLoggedIn" value="1" tabindex="4"><span class="green"> Stay logged in</span>

				</label>

				<label>

					<input type="checkbox" name="termAndConditions" value="1" tabindex="5"> <span class="green"> I have read the</span> <a href="#" tabindex="6">Terms & Conditions</a>

				</label>

			</div>

			<fieldset class="form-group">

				<input type="hidden" name="signUp" value="1">

				<input class="btn btn-success" type="submit" name="submit" value="Sign Up!" tabindex="7">

			</fieldset>

			<p><a class="toggleForms" href="#" tabindex="8">Log in</a></p>

		</form>

		<form method="post" id = "logInForm">

			<p>Log in using your username and password.</p>

			<fieldset class="form-group">

				<input class="form-control" type="text" name="username" placeholder="Your username">

			</fieldset>

			<fieldset class="form-group">

				<input class="form-control"type="password" name="password" placeholder="Password">

			</fieldset>

			<div class="checkbox">

				<label>

					<input type="checkbox" name="stayLoggedIn" value="1">Stay logged in

				</label>

				<label>

					<input type="checkbox" name="termAndConditions" value="1">I have read the<a href="#">Terms & Conditions</a>

				</label>

			</div>

			<input type="hidden" name="signUp" value="0">

			<fieldset class="form-group">

				<input class="btn btn-success" type="submit" name="submit" value="Log In!">

			</fieldset>

			<p><a class="toggleForms" href="#">Sign up</a></p>
<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalLong">
  Launch demo modal
</button>
		</form>

	</div>
	<!-- Modal -->
	<div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					...
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary">Save changes</button>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		$(document).ready(function(){


			$("#file01").change(function(e){
				var fileName = e.target.files[0].name;
				$("#labelfile01").text(fileName);
			});

			$(".toggleForms").click(function() {
				$("#signUpForm").toggle();
				$("#logInForm").toggle();
			});

		});


	</script>
</body>
</html>


