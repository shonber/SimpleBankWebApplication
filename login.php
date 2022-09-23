<?php
	// Starting a session
	session_start();
	
	// Connecting to the DataBase
	$MySQLdb = new PDO("mysql:host=127.0.0.1;dbname=bank", "root", "");
	$MySQLdb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	// Checking if we got l_usr & L_pwd using POST METHOD
	if ( isset($_POST['l_usr']) && isset($_POST['l_pwd'])){
		// Grabbing data for the user and password
	   $cursor = $MySQLdb->prepare("SELECT * FROM users WHERE FirstName=:usr AND password=:pwd");
	   $cursor->execute(array(":usr"=>$_POST["l_usr"], ":pwd"=>$_POST["l_pwd"]));
	   
	   // If there is data, we will log in if not we will get a $msg saying "Wrong username or password"
	   // now we are creating a session for the username and ID we got from the log in and sending the user to the main.php
	   	if ($cursor->rowCount()){
			$return_value = $cursor-> fetch();
			$_SESSION["FirstName"] = $return_value["FirstName"];
			$_SESSION["usr_id"] = $return_value["id"];
			
		    Header("Location: main.php");
		   
	   }else{
		   $msg = "Wrong username or password";
	   }
   }
?>

<!DOCTYPE html>
<html>
<head>
	<title>Login page</title>
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
			
			<ul class="nav navbar-nav navbar-right" style="margin-top: 15px; margin-right: 15px;">
			  <li><a href="register.php"><span id="test" class="glyphicon glyphicon-user"></span> Sign Up</a></li>
			  <li><a href="login.php"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
			</ul>
			
		  </div>
		</nav>
		
		<center>
			<h1 style="font-size: 100px; font-family: Copperplate; color: white; margin-top: 100px;"><b>LocalHost Bank</b></h1>
			<p style="font-size: 60px; font-family: Copperplate; color: white;"><b>Login to your bank account</b></p>
			<div class="well well-lg" id="text_panel"></div>
			
			<br><br>
			<div class="well well-lg" id="text_panel2"></div>
			
			<form action="#" id="form2" method="POST">
				<?php
					if (isset($msg))
					{
						if ($msg == "Wrong username or password"){
							echo "<div class='alert alert-default' id='alertHere' style='color: red; margin-bottom: -10px;'><strong>".$msg."</strong></div>";
						}else{
							echo "<div class='alert alert-default' id='alertHere'><strong>".$msg."</strong></div>";
						}						
					}
				?>
				<label style="color: white; font-family: Copperplate; font-size: 35px;">Name:</label><br/>
				<input type="text" class="form-control" id="l_usr" style="width:900px; margin-top: 10px;" name="l_usr">

				<label style="color: white; margin-top: 10px; font-family: Copperplate; font-size: 35px;">Password:</label>	
				<input type="password" class="form-control" id="l_pwd" style="width:900px; margin-top: 10px;" name="l_pwd">
			</center>

				<button type="submit" class="btn btn-default" style="width: 75px; height: 50px; margin-top: 30px; margin-left: 850px;"><b>Login</b></button>
				

            </form>	
			
			
			<a href="index.php">
				<button type="submit" class="btn btn" style="width: 150px; height: 50px; margin-top: -73px; margin-left: 930px;"><b>Go back</b></button>
			</a>
		<script>
		
			if ($("#alertHere").length) {
				$("#form2").attr("style","margin-top: 0px");
			}
		</script>
		
		<style>
		#alertHere{
			color: white;
		}
		.alert{
			padding: 15px;
			border: 1px solid transparent;
			border-radius: 4px;
			margin-bottom: 0px;
		}
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
			margin-top: -275px;
			height: 380px;
			width: 800px;			
		}
		#text_panel2{
			pointer-events: none;
			opacity: 0.1;
			margin-bottom: -350px;
			height: 380px;
			width: 1600px;			
		}
		
		
		body {
			background-color: #2b2b2b;
		}
		
		.form-control:focus {
			border-color: black;
			outline: 0;
			-webkit-box-shadow: inset 0 1px 1px rgb(0 0 0 / 8%), 0 0 8px rgb(0 0 0 / 60%);
			box-shadow: inset 0 1px 1px rgb(0 0 0 / 8%), 0 0 8px rgb(0 0 0 / 60%);
		}
		.btn-default{
			color: #333;
			background-color: #fff;
			border: 0;
			outline:none;

		}
		.btn-default:hover {
			color: #333;
			background-color: #e6e6e6;
			border: 0;
			outline:none;
		}
		.btn-default:focus {
			color: #333;
			background-color: #fff;
			border: 0;
			outline:none;
		}
		
		.btn{
			color: #333;
			background-color: #fff;
			border: 0;
			outline:none;
		}
		.btn:hover {
			color: #333;
			background-color: #e6e6e6;
			border: 0;
			outline:none;
		}
		.btn:focus {
			color: #333;
			background-color: #fff;
			border: 0;
			outline:none;
		}
		</style>
		
		<script>
		
		</script>
</body>
</html>

