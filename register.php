 <?php
	// starting a session
	session_start();
	
	// Connecting to the DataBase
	$MySQLdb = new PDO("mysql:host=127.0.0.1;dbname=bank", "root", "");
	$MySQLdb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	// Checking if we got r_usr & r_pwd & r_phone using POST METHOD
	if ( isset($_POST['r_usr']) && isset($_POST['r_pwd']) && isset($_POST['r_phone']) ){
		// Checking if the fields are empty, if so the user will recive an Error msg
		if ( $_POST['r_usr'] == '' || $_POST['r_pwd'] == '' || $_POST['r_phone'] == ''){
			$msg = "Please don't leave empty fields";
		// otherwise we will grab data from the user we got (r_usr) and check if the data we recived already in the database for another user.
		}else{
			$cursor = $MySQLdb->prepare("SELECT * FROM users WHERE FirstName=:usr");
			$cursor->execute(array(":usr"=>$_POST["r_usr"]));
			
			if($cursor->rowCount()){
				while ($row = $cursor->fetch()) {
					if( $_POST['r_usr'] == $row['FirstName'] ) {
						$msg = "Username already exist";
					}
				}
			// if not we will create the account
			}else{
				$cursor = $MySQLdb->prepare("SELECT * FROM users WHERE Mobile=:phone");
				$cursor->execute(array(":phone"=>$_POST["r_phone"]));
				
				if($cursor->rowCount()){
					while ($row = $cursor->fetch()) {
						if( $_POST['r_phone'] == $row['Mobile'] ) {
							$msg = "Phone number already exist";
						}
					}
				// if not we will create the account
				}else{
					$cursor = $MySQLdb->prepare("INSERT INTO users (FirstName, password, Mobile) value (:username,:password, :phone)");
					$cursor->execute(array(":username"=>$_POST["r_usr"], ":password"=>$_POST["r_pwd"], ":phone"=>$_POST["r_phone"]));
					$msg = "Regsitered successfully";
				}
			}
		}
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Register page</title>
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
		<p style="font-size: 60px; font-family: Copperplate; color: white;"><b>Create a LocalHost account</b></p>
		<div class="well well-lg" id="text_panel"></div>
		
		<br>
		<div class="well well-lg" id="text_panel2"></div>
		<?php
			if (isset($msg))
			{
				if ($msg == "Username already exist" || $msg == "Phone number already exist" || $msg == "Please don't leave empty fields"){
						echo "<div class='alert alert-default' id='alertHere' style='color: red;'><strong>".$msg."</strong></div>";
					}else{
						echo "<div class='alert alert-default' id='alertHere' style='color: green;'><strong>".$msg."</strong></div>";
					}						
			}
		?>	
		<form action="#" id="form1" method="POST" style="margin-top: 50px;">
		

			<label style="color: white; font-family: Copperplate; font-size: 25px;">Name:</label>
			<input type="text" class="form-control" id="r_usr" style="width:900px; margin-top: 10px;" name="r_usr">

			<label style="color: white; font-family: Copperplate; font-size: 25px; margin-top: 10px;">Password:</label>
			<input type="password" class="form-control" id="r_pwd" style="width:900px; margin-top: 10px;" name="r_pwd">

			<label style="color: white; font-family: Copperplate; font-size: 25px; margin-top: 10px;">Mobile number:</label>
			<input type="text" class="form-control" id="r_phone" style="width:900px; margin-top: 10px;" pattern="[0-9]{10}" placeholder="05XXXXXXXX" name="r_phone">
			
		</center>
		
			<button type="submit" class="btn btn-default" style="width: 75px; height: 50px; margin-top: 30px; margin-left: 850px;"><b>Register</b></button>
		</form>

		<a href="index.php">
			<button type="submit" class="btn btn" style="width: 150px; height: 50px; margin-top: -73px; margin-left: 930px;"><b>Go back</b></button>
		</a>
	<script>
	
		if ($("#alertHere").length) {
			$("#form1").attr("style","margin-top: 0px");
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
		margin-bottom: -415px;
		height: 420px;
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
</body>
</html>

