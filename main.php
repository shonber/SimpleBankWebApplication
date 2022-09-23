<?php
	// Starting a session.
	session_start();
	
	// Checking if there is a running session if not the user will be sent to 'index.php'.
	if (!isset($_SESSION["FirstName"])){
		Header("Location: index.php");
	}
	
	// Connecting to the DataBase.
	$MySQLdb = new PDO("mysql:host=127.0.0.1;dbname=bank", "root", "");
	$MySQLdb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	// Grabbing all the data from the session user.
	$cursor = $MySQLdb->prepare("SELECT * FROM users WHERE FirstName=:usr");
	$cursor->execute(array(":usr"=>$_SESSION["FirstName"]));
	
	// Creating sessions from the data we got from the database.
	if($cursor->rowCount()){
		while ($result = $cursor->fetch()){
			$_SESSION["FirstName"] = $result["FirstName"];
			$_SESSION["LastName"] = $result["LastName"];
			$_SESSION["Mobile"] = $result["Mobile"];
			$_SESSION["AccountID"] = $result["AccountID"];
			$_SESSION["balance"] = $result["balance"];
			
			// If the user does'nt has a last name so he will recive Undefined.
			if ($_SESSION["LastName"] == ''){
				$cursor = $MySQLdb->prepare("UPDATE users SET LastName=:LastName WHERE FirstName=:usr");
				$cursor->execute(array(":LastName"=>"Undefined", ":usr"=>$_SESSION["FirstName"]));
				$_SESSION["LastName"] = "Undefined";

			}
			
			// If the balance is eual 0 so he will recive 250$.
			if ($_SESSION["balance"] == 0){
				$cursor = $MySQLdb->prepare("UPDATE users SET balance=:balance WHERE FirstName=:usr");
				$cursor->execute(array(":balance"=>250, ":usr"=>$_SESSION["FirstName"]));
				$_SESSION["balance"] = 250;
			}
			
			// If the user does'nt has an account id the system will generate one for him.
			if ($_SESSION["AccountID"] == ''){
				
				$result = 1617;
				for ($count = 0; $count < 12; $count++){
					$result .= rand(0,9);
				}
				$AccountID = $result;
				
				// Updating user's Account ID.
				$cursor = $MySQLdb->prepare("UPDATE users SET AccountID=:AccountID WHERE FirstName=:usr");
				$cursor->execute(array(":AccountID"=>$AccountID, ":usr"=>$_SESSION["FirstName"]));
				$_SESSION["AccountID"] = $AccountID;
			}
		}	
	}
	
	// Changing/Updating user's data.
	if ( isset($_POST['c_name']) || isset($_POST['c_last']) || isset($_POST['c_mobile']) || isset($_POST['c_pwd']) ){
		
		// Grabbing the connected user's data.
		$cursor = $MySQLdb->prepare("SELECT * FROM users WHERE FirstName=:usr");
		$cursor->execute(array(":usr"=>$_SESSION["FirstName"]));
		
		// Creating varibles with the data we recived using POST METHOD.
		if($cursor->rowCount()){
			while ($rows = $cursor->fetch()){
				$c_name = $_POST['c_name'];
				$c_last = $_POST['c_last'];
				$c_mobile = $_POST['c_mobile'];
				$c_pwd = $_POST['c_pwd'];
				
				// checking if the field is empty.
				if ( $c_name !== '' ){
					// checking if the data recived is equal to the current data.
					if ( $c_name != $rows["FirstName"] ){
						// Changing the data to the new data & creating a new session for the user with the updated data.
						$cursor = $MySQLdb->prepare("UPDATE users SET FirstName=:FirstName WHERE FirstName=:usr");
						$cursor->execute(array(":FirstName"=>$c_name, ":usr"=>$_SESSION["FirstName"]));
						$_SESSION["FirstName"] = $c_name;
						$msg = "Changed Successfully";						
					// Error msg if the data is equal.
					}else{
						$msg = "You can't change to the same name";
					}					
				}
				
				// checking if the field is empty.
				if ( $c_last != '' ){
					// checking if the data recived is equal to the current data.
					if ( $c_last != $rows["LastName"] ){
						// Changing the data to the new data & creating a new session for the user with the updated data.
						$cursor = $MySQLdb->prepare("UPDATE users SET LastName=:LastName WHERE FirstName=:usr");
						$cursor->execute(array(":LastName"=>$c_last, ":usr"=>$_SESSION["FirstName"]));	
						$_SESSION["LastName"] = $c_last;
						$msg = "Changed Successfully";	
					// Error msg if the data is equal.						
					}else{
						$msg = "You can't change to the same Last name";
					}					
				}
				
				// checking if the field is empty.
				if ( $c_mobile != ''){
					// Chacking if the data recived is numbers.
					if ( is_numeric($c_mobile) == True ){
						// checking if the data recived is equal to the current data.
						if ( $c_mobile != $rows["Mobile"] ){
						// Changing the data to the new data & creating a new session for the user with the updated data.
							$cursor = $MySQLdb->prepare("UPDATE users SET Mobile=:Mobile WHERE FirstName=:usr");
							$cursor->execute(array(":Mobile"=>$c_mobile, ":usr"=>$_SESSION["FirstName"]));		
							$_SESSION["Mobile"] = $c_mobile;					
							$msg = "Changed Successfully";	
						// Error msg if the data is equal.							
						}else{
							$msg = "You can't change to the same number";
						}
					// Error msg if the data is'nt numbers.
					}else{
						$msg = "Please use only numbers";
					}						
				}

				// checking if the field is empty.
				if ( $c_pwd != ''){
					// checking if the data recived is equal to the current data.
					if ( $c_pwd != $rows["password"] ){
						// Changing the data to the new data & creating a new session for the user with the updated data.					
						$cursor = $MySQLdb->prepare("UPDATE users SET password=:password WHERE FirstName=:usr");
						$cursor->execute(array(":password"=>$c_pwd, ":usr"=>$_SESSION["FirstName"]));
						$msg = "Changed Successfully";		
					// Error msg if the data is equal.					
					}else{
						$msg = "You can't change to the same password";
					}					
				}
			}
		// Error msg if the user session is destroyed.
		}else {
			$msg = "Error!";
		}
	}

	// Checking if we recived 3 fields for the transfer option, 'reciver' & 'AccountID' & 'amount'.
	if ( isset($_POST['reciver']) || isset($_POST['AccountID']) || isset($_POST['amount'])) {
		
		// Grabing the data from the reciver account.
		$cursor = $MySQLdb->prepare("SELECT * FROM users WHERE FirstName=:reciver");
		$cursor->execute(array(":reciver"=>$_POST['reciver']));
		
		// Checking if a user is found with this name.
		if($cursor->rowCount()>0){
			// Creating varibles from the data we recived using POST METHOD.
			while ($rows = $cursor->fetch()){
				$reciver = $_POST['reciver'];
				$recAccountID = $_POST['AccountID'];
				$amount = $_POST['Amount'];
				
				// Checking if the reciver's name is in the database.
				if ($reciver == $rows["FirstName"]){
					// Checking if the reciver's AccountID is in the database.
					if ($recAccountID == $rows["AccountID"]){
						// Grabing the data from the sender's account.
						$cursor = $MySQLdb->prepare("SELECT * FROM users WHERE FirstName=:from");
						$cursor->execute(array(":from"=>$_SESSION['FirstName']));
						$line = $cursor->fetch();
						
						// Checcking if the sender has enough money to send.
						if ( $amount > $line["balance"] || $line["balance"] <= 0 ){
							$msg = "Insufficient funds";
						}else{
							
							// // Grabing the data from the sender's account.
							$cursor = $MySQLdb->prepare("SELECT * FROM users WHERE FirstName=:from");			
							$cursor->execute(array(":from"=>$_SESSION["FirstName"]));
							
							// Checking if the account still exist.
							if($cursor->rowCount()>0){
								while ($rows = $cursor->fetch()){
									// If the transfers are empty so we will add a new transfer.
									if ( $rows["transfers"] == null ){
										$_SESSION["retval"] = "<br><div style='background-color:#404040; height: 200px; width: 800px'><p style='padding-top:10px; font-size:20px; font-family: Copperplate; color: white;'>
										<b>Sent Funds</b></p><p style='font-size:17px; font-family: Copperplate; color: white;'><b><u>Sent to Account</u>: " . $recAccountID . "</b></p>
										<p style='font-size:17px; font-family: Copperplate; color: white;'><b><u>Reciver's name</u>: ". $reciver . "</b></p><p style='font-size:17px; font-family: Copperplate; color: white;'>
										<b><u>Amount Sent</u>: " . $amount . "$" . "</b></p><p style='font-size:17px; font-family: Copperplate; color: white;'><b><u>Date</u>: " . date('Y/m/d') . "</b></p></div>";	
									
									// If not we will add the new transfer and not overwrite it.
									}else{
										$_SESSION["retval"] = "<br><div style='background-color:#404040; height: 200px; width: 800px'><p style='padding-top:10px; font-size:20px; font-family: Copperplate; color: white;'>
										<b>Sent Funds</b></p><p style='font-size:17px; font-family: Copperplate; color: white;'><b><u>Sent to Account</u>: " . $recAccountID . "</b></p>
										<p style='font-size:17px; font-family: Copperplate; color: white;'><b><u>Reciver's name</u>: ". $reciver . "</b></p><p style='font-size:17px; font-family: Copperplate; color: white;'>
										<b><u>Amount Sent</u>: " . $amount . "$" . "</b></p><p style='font-size:17px; font-family: Copperplate; color: white;'><b><u>Date</u>: " . date('Y/m/d') . "</b></p></div>" . $rows["transfers"];
										
									}
								}
							}
							
							// Grabing the data from the reciver's account.
							$cursor = $MySQLdb->prepare("SELECT * FROM users WHERE FirstName=:reciver");			
							$cursor->execute(array(":reciver"=>$reciver));
							
							// Checking if the account still exist.
							if($cursor->rowCount()>0){
								while ($rowse = $cursor->fetch()){
									// If the transfers are empty so we will add a new transfer.
									if ( $rowse["transfers"] == null ){
										$_SESSION["retval2"] = "<br><div style='background-color:#404040; height: 200px; width: 800px'><p style='padding-top:10px; font-size:20px; font-family: Copperplate; color: white;'>
										<b>Recived Funds</b></p><p style='font-size:17px; font-family: Copperplate; color: white;'><b><u>Recived From Account</u>: " . $_SESSION["AccountID"] . "</b></p>
										<p style='font-size:17px; font-family: Copperplate; color: white;'><b><u>Sender's name</u>: " . $_SESSION["FirstName"] . "</b></p>
										<p style='font-size:17px; font-family: Copperplate; color: white;'><b><u>Amount recived</u>: " . $amount . "$" ."</b></p><p style='font-size:17px; font-family: Copperplate; color: white;'>
										<b><u>Date</u>: " . date('Y/m/d') . "</b></p></div>";
										
									// If not we will add the new transfer and not overwrite it.
									}else{
										$_SESSION["retval2"] = "<br><div style='background-color:#404040; height: 200px; width: 800px'><p style='padding-top:10px; font-size:20px; font-family: Copperplate; color: white;'>
										<b>Recived Funds</b></p><p style='font-size:17px; font-family: Copperplate; color: white;'><b><u>Recived From Account</u>: " . $_SESSION["AccountID"] . "</b></p>
										<p style='font-size:17px; font-family: Copperplate; color: white;'><b><u>Sender's name</u>: " . $_SESSION["FirstName"] . "</b></p>
										<p style='font-size:17px; font-family: Copperplate; color: white;'><b><u>Amount recived</u>: " . $amount . "$" ."</b></p><p style='font-size:17px; font-family: Copperplate; color: white;'>
										<b><u>Date</u>: " . date('Y/m/d') . "</b></p></div>" . $rowse["transfers"];
									}
								}
							}
							
							// Updating the balance for the reciver.
							$cursor = $MySQLdb->prepare("UPDATE users SET balance= balance + :amount, transfers = :retval2 WHERE FirstName=:reciver");			
							$cursor->execute(array(":amount"=>$amount,":retval2"=>$_SESSION["retval2"], ":reciver"=>$reciver));
							
							// updating the balance for the sender.
							$cursor = $MySQLdb->prepare("UPDATE users SET balance = balance - :amount, transfers = :retval WHERE FirstName=:from");
							$cursor->execute(array(":amount"=>$amount,":retval"=>$_SESSION["retval"] ,":from"=>$_SESSION["FirstName"]));
							
							// Success msg and creating a new session to update the balance.
							$msg = "Success";
							$_SESSION['balance'] = $_SESSION['balance']-$amount;
						}
					// Error msg if the AccountID is'nt found.
					}else{
						$msg = "Account ID has not found";
					}
				// Error msg if the reciver's name is'nt found.
				}else{
					$msg = "Reciver name not found";					
				}					
			}
		// Error msg if not user found.
		}else{
			$msg = "No data found!";
		}
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Account</title>
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
		<ul class="nav navbar-nav navbar-right" style="margin-top: 15px; margin-right: 15px;">
		  <li><a href="index.php" id="logout2"><span class="glyphicon glyphicon-log-in"></span> Log out </a></li>
		</ul> 
	  </div>
	</nav>

	<center>
		<h1 style="font-size: 100px; font-family: Copperplate; color: white; margin-top: 50px;"><b>Welcome <?php echo $_SESSION["FirstName"] ?></b></h1>
		
		<br><br>
		<div class="well well-lg" id="text_panel2"></div>
		<br>
		<div class="DATA">
			<label style="color: white; font-family: Copperplate; font-size: 25px;"><u>First Name</u>: <?php echo $_SESSION['FirstName'] ?> </label><br><br>
			<label style="color: white; font-family: Copperplate; font-size: 25px;"><u>Last Name</u>: <?php echo $_SESSION['LastName'] ?> </label><br><br>
			<label style="color: white; font-family: Copperplate; font-size: 25px;"><u>Mobile Number</u>: <?php echo $_SESSION['Mobile'] ?> </label><br><br>
			<label style="color: white; font-family: Copperplate; font-size: 25px;"><u>Account ID</u>: <?php echo $_SESSION["AccountID"] ?> </label><br><br>
			<label style="color: white; font-family: Copperplate; font-size: 25px;"><u>Account Balance</u>: <?php echo $_SESSION['balance'] ?> $ </label><br><br>
		</div>
		<br>
		<form action="#" method="POST">
			<div id="TRANSFER" hidden>
				<br><br><br>
				<label style="color: white; font-family: Copperplate; font-size: 25px;"><u>Reciver name</u>:</label><br>
				<input type="text" class="form-control" id="reciver" style="width:225px;" name="reciver" required><br>
				
				<label style="color: white; font-family: Copperplate; font-size: 25px;"><u>Bank ID</u>:</label><br>
				<input type="text" class="form-control" id="AccountID" style="width:225px;;" name="AccountID" required><br>
				
				<label style="color: white; font-family: Copperplate; font-size: 25px;"><u>Amount</u>:</label><br>
				<input type="text" class="form-control" id="Amount" style="width:225px;" name="Amount" required><br>
				
				<button type="submit" id="send" class="btn btn-primary" style="width: 150px; height: 250px; margin-left: 440px; margin-top: -313px;"><b>Transfer</b></button>
			</div>
		</form>
		
		<form action="#" method="POST">
			<div id="SETTINGS" hidden>
				<br><br><br>
				<label style="color: white; font-family: Copperplate; font-size: 25px;"><u>First Name</u>:</label>
				<input type="text" class="form-control" id="c_name" style="width:225px;" name="c_name"><br>
				
				<label style="color: white; font-family: Copperplate; font-size: 25px;"><u>Last Name</u>:</label>
				<input type="text" class="form-control" id="c_last" style="width:225px;;" name="c_last"><br>
				
				<label style="color: white; font-family: Copperplate; font-size: 25px;"><u>Password</u>:</label>
				<input type="text" class="form-control" id="c_pwd" style="width:225px;" name="c_pwd"><br>
				
				<label style="color: white; font-family: Copperplate; font-size: 25px;"><u>Mobile number</u>:</label>
				<input type="text" class="form-control" id="c_mobile" style="width:225px;" name="c_mobile">
				
				<button type="submit" id="change" class="btn btn-primary" style="width: 150px; height: 250px; margin-right: 440px; margin-top: -335px;"><b>Change</b></button>
			</div>
		</form>
		<div class="btn-group">
			<button type="button" id="logout" class="btn btn-primary" style="width: 150px; height: 60px"><b>Log out</b></button>
			<button type="button" id="transfer" class="btn btn-primary" style="width: 150px; height: 60px"><b>Send money</b></button>
			<button type="button" id="settings" class="btn btn-primary" style="width: 150px; height: 60px;"><b>Settings</b></button>		
		</div>
		
		<!--Success/Error msgs-->
		<?php
			if (isset($msg))
			{
				if ($msg == "Success" || $msg == "Changed Successfully"){
					echo "<div class='alert alert-default' id='alertHere' style='color: green;'><strong>".$msg."</strong></div>";
				}else {
					echo "<div class='alert alert-default' id='alertHere' style='color: red;' ><strong>".$msg."</strong></div>";
				}
			}
		?>	
		

	</center>
	
	<center>
		<div>
			<label id="TRANSFERS"><u><b>Transfers</u></b>:</label>
			
			<?php
				// Grabing the data from the connected user.
				$cursor = $MySQLdb->prepare("SELECT * FROM users WHERE FirstName=:usr");
				$cursor->execute(array(":usr"=>$_SESSION["FirstName"]));
				
				// "printing" all of the user's transactions in to the page.
				if($cursor->rowCount()>0){
					while ($row = $cursor->fetch()){
						$_SESSION["retval"] = $row["transfers"];
						echo $_SESSION["retval"];
					}		
				}
			?>
			<br>
		</div>
	</center>

		<script>
			// logout button OnClick function to send request to the 'api.php' page to logout the user and place him in 'index.php' page (Middle).
			$("#logout").click(function(){
				$.post("api.php", {"action": "logout"}, function(data){
					if (data.success == "true"){
						location.href = "index.php";
					}
				});
			});
			
			// logout button OnClick function to send request to the 'api.php' page to logout the user and place him in 'index.php' page (Top-Right).
			$("#logout2").click(function(){
				$.post("api.php", {"action": "logout"}, function(data){
					if (data.success == "true"){
						location.href = "index.php";
					}
				});
			});
			
			// Checking if 'alerthere' div exist.
			if ($("#alertHere").length) {
				$("#TRANSFERS").attr("style","margin-top: 0px");
				
				// Lines 387-471 based on margins to place all of the divs in the same place without moving them & showing + hiding features.
				$("#transfer").click(function(){
					if ( $("#SETTINGS").is(":hidden") ){
						if ( $("#TRANSFER").is(":hidden") ){
							$("#TRANSFER").show();
							$(".btn-group").attr("style","margin-top: -37px");
						}else if ( $("#TRANSFER").is(":visible") ){
							$("#TRANSFER").hide();
							$(".btn-group").attr("style","margin-top: 0px");
						}
					}else{
						$("#SETTINGS").hide();
						$(".btn-group").attr("style","margin-top: -37px");	
						$("#TRANSFERS").attr("style","margin-top: 0px");
						$("#alertHere").attr("style","margin-top: 20px");					
						
						$("#TRANSFER").show();
					}					
				});	
				
				$("#settings").click(function(){
					if ( $("#TRANSFER").is(":hidden") ){
						if ( $("#SETTINGS").is(":hidden") ){
							$("#SETTINGS").show();
							$(".btn-group").attr("style","margin-top: -85px");
							$("#TRANSFERS").attr("style","margin-top: 0px");					
							$("#alertHere").attr("style","margin-top: -1px");					

						}else if ( $("#SETTINGS").is(":visible") ){
							$("#SETTINGS").hide();
							$(".btn-group").attr("style","margin-top: 0px");
							$("#TRANSFERS").attr("style","margin-top: 0px");	
							$("#alertHere").attr("style","margin-top: 20px");					
							
						}
					}else{
						$("#TRANSFER").hide();
						$(".btn-group").attr("style","margin-top: -85px");		
						$("#TRANSFERS").attr("style","margin-top: 0px");	
						$("#alertHere").attr("style","margin-top: -1px");					
						
						$("#SETTINGS").show();	
					}					
				});					
			}else{
				$("#TRANSFERS").attr("style","margin-top: 35px");
				
				$("#transfer").click(function(){
					if ( $("#SETTINGS").is(":hidden") ){
						if ( $("#TRANSFER").is(":hidden") ){
							$("#TRANSFER").show();
							$(".btn-group").attr("style","margin-top: -37px");
						}else if ( $("#TRANSFER").is(":visible") ){
							$("#TRANSFER").hide();
							$(".btn-group").attr("style","margin-top: 0px");
						}
					}else{
						$("#SETTINGS").hide();
						$(".btn-group").attr("style","margin-top: -37px");	
						$("#TRANSFERS").attr("style","margin-top: 35px");										
						$("#TRANSFER").show();
					}					
				});	
				
				$("#settings").click(function(){
					if ( $("#TRANSFER").is(":hidden") ){
						if ( $("#SETTINGS").is(":hidden") ){
							$("#SETTINGS").show();
							$(".btn-group").attr("style","margin-top: -85px");
							$("#TRANSFERS").attr("style","margin-top: 14px");					


						}else if ( $("#SETTINGS").is(":visible") ){
							$("#SETTINGS").hide();
							$(".btn-group").attr("style","margin-top: 0px");
							$("#TRANSFERS").attr("style","margin-top: 35px");					
						}
					}else{
						$("#TRANSFER").hide();
						$(".btn-group").attr("style","margin-top: -85px");		
						$("#TRANSFERS").attr("style","margin-top: 14px");					
						$("#SETTINGS").show();	
					}					
				});					
			}

		</script>
		
		<style>
			#TRANSFERS{
				color: white; 
				font-family: Copperplate; 
				font-size: 25px;
				margin-top:35px;
			}
			.alert{
				padding: 15px;
				border: 1px solid transparent;
				border-radius: 4px;
				margin-bottom: 0px;
			}
			#TRANSFER{
				 margin-right: 1150px; 
				 margin-top: -325px;
			}
			
			#SETTINGS{
				margin-left: 1150px; 
				margin-top: -375px;
			}

			#alertHere{
				color: white;
				margin-left: 10px; 
				margin-top: 20px; 
				font-family: Copperplate;
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
			#logout{
				color: #333;
				background-color: #fff;
				border: 0;
				outline:none;

			}
			#logout:hover {
				color: #333;
				background-color: #e6e6e6;
				border: 0;
				outline:none;
			}
			#logout:focus {
				color: #333;
				background-color: #fff;
				border: 0;
				outline:none;
			}
			
			#transfer{
				color: #333;
				background-color: #fff;
				border: 0;
				outline:none;
			}
			#transfer:hover {
				color: #333;
				background-color: #e6e6e6;
				border: 0;
				outline:none;
			}
			#transfer:focus {
				color: #333;
				background-color: #fff;
				border: 0;
				outline:none;
			}
			#send{
				color: #333;
				background-color: #fff;
				border: 0;
				outline:none;
			}
			#send:hover {
				color: #333;
				background-color: #e6e6e6;
				border: 0;
				outline:none;
			}
			#send:focus {
				color: #333;
				background-color: #fff;
				border: 0;
				outline:none;
			}
			
			#change{
				color: #333;
				background-color: #fff;
				border: 0;
				outline:none;
			}
			#change:hover {
				color: #333;
				background-color: #e6e6e6;
				border: 0;
				outline:none;
			}
			#change:focus {
				color: #333;
				background-color: #fff;
				border: 0;
				outline:none;
			}
			
			#settings{
				color: #333;
				background-color: #fff;
				border: 0;
				outline:none;
			}
			#settings:hover {
				color: #333;
				background-color: #e6e6e6;
				border: 0;
				outline:none;
			}
			#settings:focus {
				color: #333;
				background-color: #fff;
				border: 0;
				outline:none;
			}
		</style>
	</body>
</html>