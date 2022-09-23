<?php
	// Starting a session
	session_start();
	
	// Checking if there is already a running session, If there is he will be sent to the main.php if not he will stay in this page and log in or sign up
	if (isset($_SESSION["FirstName"])){
		Header("Location: main.php");	
	}else{
		$_SESSION["signlog"] = "<ul class='nav navbar-nav navbar-right' style='margin-top: 15px; margin-right: 15px;'>
		<li><a href='register.php'><span id='test' class='glyphicon glyphicon-user'></span> Sign Up</a></li><li><a href='login.php'>
		<span class='glyphicon glyphicon-log-in'></span> Login</a></li></ul>";
	}

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Lobby</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    </head>
    <body>
	
		<nav class="navbar navbar-inverse">
		  <div class="container-fluid">
			<div id="header" class="navbar-header">
			  <a class="navbar-brand" href="index.php"><img id="logo" src="./imgs/logo.png"></a>
			</div>
			
			<ul class="nav navbar-nav" style="margin-top: 15px">

			</ul>
			<?php echo $_SESSION["signlog"]; ?>
			
		  </div>
		</nav>
		
		<br /><br /><br /><br />

		<center>
			<h1 style="font-size: 100px; font-family: Copperplate; color: white;"><b>LocalHost Bank</b></h1>
			<p style="font-size: 60px; font-family: Copperplate; color: white;"><b>Forget about lines</b></p>
			<div class="well well-lg" id="text_panel"></div>
		</center>

		<br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
		<center>
			<a href="login.php" class="btn btn-primary" style="height:300px; width:185px; font-size: 25px; margin-top: -125px; font-family: Copperplate; padding-top: 125px; font-size: 35px;"><b>Login</b></a>
			<a href="register.php" class="btn btn-primary" style="height:300px; width:185px; font-size: 25px; margin-top: -125px; font-family: Copperplate; padding-top: 125px; font-size: 35px;"><b>Sign Up</b></a>
			<div class="well well-lg" id="panel" ></div>
		</center>

		
		<style>
		#logo{
			height: 100px;
			margin-top: -20px;
		}

		#header{
			height: 80px;
		}
		
		#panel{
			pointer-events: none;
			opacity: 0.1;
			margin-top: -342px;
			height: 380px;
			width: 500px;
		}
		#text_panel{
			pointer-events: none;
			opacity: 0.1;
			margin-top: -295px;
			height: 380px;
			width: 800px;			
		}
		.btn-primary {
			color: white;
			background-color: #8285886b;
			border-color: #000000;
		}
		.btn-primary:hover{
			color: white;
			background-color: #5254566b;
			border-color: black;
		}
		.btn-primary.active.focus, .btn-primary.active:focus, .btn-primary.active:hover, .btn-primary:active.focus, .btn-primary:active:focus, .btn-primary:active:hover, .open>.dropdown-toggle.btn-primary.focus, .open>.dropdown-toggle.btn-primary:focus, .open>.dropdown-toggle.btn-primary:hover {
			color: #fff;
			background-color: #8285886b;
			border-color: black;
		}
		body {
			background-color: #2b2b2b;
		}
		</style>
    </body>
</html>

