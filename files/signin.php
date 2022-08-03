<?php include ( "./inc/connect.inc.php" ); ?>
<?php 
session_start();
if (isset($_COOKIE['user_login'])) {
	$_SESSION['user_login'] = $_COOKIE['user_login'];
	header("location: newsfeed.php");
	exit();
}


if (isset($_POST['submconfrmCode'])) {
	$user_confrmCode_db = $_POST['confrmCode']; //mysql_real_escape_string
	$user_loginnn = $_SESSION['user_loginn'];
	$result2 = mysqli_query($conn, "SELECT * FROM users WHERE username='$user_loginnn' AND confirmCode='$user_confrmCode_db' AND activated='0'");
	$num2 = mysqli_num_rows($result2);
	$get_user_info_f = mysqli_fetch_assoc($result2);
	if ($num2>=1) {
		$password_update_query = mysqli_query($conn, "UPDATE users SET activated='1', confirmCode='0' WHERE username='$user_loginnn'");
		
		//creating session
		$_SESSION['user_login'] = $user_loginnn;
				
				setcookie('user_login', $user_loginnn, time() + (365 * 24 * 60 * 60), "/");
				
				header('location: newsfeed.php');
				exit();
		
	}else {
		$success_message = '';
	}
}

?>

<?php
if(isset($_POST["name2check"]) && $_POST["name2check"] != ""){
    $username = preg_replace('#[^a-z0-9]#i', '', $_POST['name2check']); 
    $sql_uname_check = mysqli_query($conn, "SELECT id FROM users WHERE username='$username' LIMIT 1"); 
    $uname_check = mysqli_num_rows($sql_uname_check);
    if (strlen($username) < 2 || strlen($username) > 15 ) {
	    echo '<p style="color: #C10000; font-size: 13px; font-weight: 600; text-align: center; margin: 3px 0;">2 - 15 characters please</p>';
	    exit();
    }
	if (is_numeric($username[0])) {
	    echo '<p style="color: #C10000; font-size: 13px; font-weight: 600; text-align: center; margin: 3px 0;">First character must be a letter</p>';
	    exit();
    }
    if ($uname_check < 1) {
	    echo '<p style="color: #0B810B; font-size: 13px; font-weight: 600; text-align: center; margin: 3px 0;">Success! Remember username for login</p>';
	    exit();
    } else {
	    echo '<p style="color: #C10000; font-size: 13px; font-weight: 600; text-align: center; margin: 3px 0;"><strong>' . $username . '</strong> has taken! Choose another.</p>';
	    exit();
    }
}
?>

<?php

if (isset($_POST['signup'])) {
//declere veriable
$u_name = $_POST['username'];
$u_name  = trim($u_name);
$u_name  = strtolower($u_name);
$u_name  = preg_replace('/\s+/','',$u_name);
$u_email = $_POST['email'];
//triming name
$_POST['first_name'] = trim($_POST['first_name']);
$_POST['username'] = trim($_POST['username']);
$_POST['username'] = strtolower($_POST['username']);
$_POST['username'] = preg_replace('/\s+/','',$_POST['username']);
	try {
		if(empty($_POST['first_name'])) {
			throw new Exception('Fullname can not be empty');
			
		}
		if (is_numeric($_POST['first_name'][0])) {
			throw new Exception('Please write your correct name!');

		}
		if(empty($_POST['username'])) {
			throw new Exception('Username can not be empty');
			
		}
		if (is_numeric($_POST['username'][0])) {
			throw new Exception('Username first character must be a letter!');

		}
		if(empty($_POST['email'])) {
			throw new Exception('Email can not be empty');
			
		}
		if(empty($_POST['password'])) {
			throw new Exception('Password can not be empty');
			
		}
		if(empty($_POST['gender'])) {
			throw new Exception('Gender can not be empty');
			
		}

		if (strlen($_POST['first_name']) <2 || strlen($_POST['first_name']) >15 )  {
			throw new Exception('Full name must be 2 to 15 characters!');
		}

		//username check
		$u_check = mysqli_query($conn, "SELECT username FROM users WHERE username='$u_name'");
		$check = mysqli_num_rows($u_check);
		// Check if email already exists
		$e_check = mysqli_query($conn, "SELECT email FROM users WHERE email='$u_email'");
		$email_check = mysqli_num_rows($e_check);
		if (strlen($_POST['username']) >1 && strlen($_POST['username']) <16 ) {
			if ($check == 0 ) {
				if ($email_check == 0) {
					if (strlen($_POST['password']) >4 ) {
						$d = date("Y-m-d"); //Year - Month - Day
						$_POST['first_name'] = ucwords($_POST['first_name']);
						$_POST['username'] = strtolower($_POST['username']);
						$_POST['username'] = preg_replace('/\s+/','',$_POST['username']);
						$_POST['password'] = md5($_POST['password']);
						$confirmCode   = substr( rand() * 900000 + 100000, 0, 6 );
						// send email
						$msg = "
						Assalamu Alaikum 
						
						Your activation code: ".$confirmCode."
						Username: ".$_POST['username']."
						Signup email: ".$_POST['email']."
						
						";
						$user_type=$_POST['user_type'];
							
						$result = mysqli_query($conn, "INSERT INTO users (first_name,username,email,password,gender,sign_up_date,activated,user_type) VALUES ('$_POST[first_name]','$_POST[username]','$_POST[email]','$_POST[password]','$_POST[gender]','$d','1','$_POST[user_type]')");
						

						$_SESSION['user_loginn'] = $_POST['username'];

						if($user_type==1)
						{
							$res=mysqli_query($conn,"INSERT INTO family (username) VALUES ('$_POST[username]')");
						}
						else if($user_type==2)
						{
							$res=mysqli_query($conn,"INSERT INTO doctor (username) VALUES ('$_POST[username]')");
						}
						else if($user_type==3)
						{
							$res=mysqli_query($conn,"INSERT INTO educator (username) VALUES ('$_POST[username]')");
						}
						else 
						{
							$res=mysqli_query($conn,"INSERT INTO everyone (username) VALUES ('$_POST[username]')");
						}
						
						//sent follow
						//$user_from = $_POST['username'];
						//$user_to = 'nur';
						//$create_followMe = mysqli_query($conn, "INSERT INTO follow VALUES ('', '$user_from', '$user_to', NOW(), 'no')");
						//$create_followFrom = mysqli_query($conn, "INSERT INTO follow VALUES ('', '$user_to', '$user_from', NOW(), 'no')");
						//send message
						//$msg_body = 'Assalamu Alaikum';
						//$msgdate = date("Y-m-d");
						//$opened = "no";
						//$messages = mysqli_query($conn, "INSERT INTO pvt_messages VALUES ('','$user_to','$user_from','$msg_body','$msgdate','NOW()','$opened', '')");
						
						//success message
						// $success_message = '
						// <h2><font face="bookman">Registration successfull!</font></h2>
						// <div class="maincontent_text" style="text-align: center;">
						// <font face="bookman">You can login with usename or email. <br>
						// 	Email: '.$u_email.'<br>
						// 	Username: '.$_POST['username'].'
						// </font></div>';
						//}else {
						//	throw new Exception('Email is not valid!');
						//}
						echo "<script>alert('SignUp Successfully.')</script>";
						
					}else {
						throw new Exception('Password must be 5 or more then 5 characters!');
					}
				}else {
					throw new Exception('Email already taken!');
				}
			}else {
				throw new Exception('Username already taken!');
			}
		}else {
			throw new Exception('Username must be 5-15 characters!');
		}

	}
	catch(Exception $e) {
		$error_message = $e->getMessage();
	}
}



?>


<!doctype html>
<html>
	<head>
	<title>Accept the Special</title>
	<meta charset="uft-8">
	<link rel="icon" href="./img/tlogo.png" type="image/x-icon">
	
	<link rel="stylesheet" type="text/css" href="./css/style.css">

	    <meta name="viewport" content="width=device-width, initial-scale=1.0">

	    <script type="text/javascript">

		</script>

		<script src="js/modernizr.custom.js"></script>
		<script src="js/hideShowPassword.js"></script>	
		<script>
			$(window).ready(function(){
				$('#password-1').hideShowPassword({
				  // Creates a wrapper and toggle element with minimal styles.
				  innerToggle: true,
				  // Makes the toggle functional in touch browsers without
				  // the element losing focus.
				  touchSupport: Modernizr.touch
				});
			});
		</script>
	    <style>
		  ::-ms-reveal {
		    display:none !important;
		  }
		  .hideShowPassword-toggle {
		    background-image: url(./img/wink.svg);
		    background-position: 0 center;
		    background-repeat: no-repeat;
		    cursor: pointer;
		    height: 100%;
		    overflow: hidden;
		    text-indent: -9999em;
		    width: 44px;
		  }
		  .hideShowPassword-toggle-hide {
		    background-position: -44px center;
		  }
		  .hideShowPassword-toggle,
		  .my-toggle-class {
		    z-newsfeed: 3;
		  }
		  #NavigationBar{
			    position: sticky;
			    top: 0;
			    z-index: 10px;
			}
			.navbar
			{
			    background: #f4f2f0;
			}
			.navbar-nav li
			{
			        padding-right: 48px;
			} 

			.navbar-nav li a
			{
			    color: black;
			}

			.signupbutton {
			    background-color: #FFFFFF;
			    border: 2px solid #e76f39;
			    border-radius: 6px;
			    margin: 15px 48px;

			}
			.uisignupbutton {
			    color: #e76f39;
			    cursor: pointer;
			    display: inline-block;
			    font-size: 18px;
			    font-weight: bold;
			    line-height: 13px;
			    padding: 10px 0;
			    width: 200px;
			    text-align: center;
			    text-decoration: none;
			    border-width: 2px;
			   
			}
			.uisignupbutton:hover {
			  background-color: #e76f39;
			  color: #ffffff;

			}
			.signupbox {
			  font-size: 15px;
			  font-style: italic;
			  margin-bottom: 5px;
			  margin-top: 5px;
			  line-height: 25px;
			  border-radius: 4px;
			  border: 1px solid #e76f39;
			  color: #333;
			  margin-left: 0;
			  width: 320px;
			  height: 30px;
			}

			

			.navbar-logo {
			    display: block;
			    z-index: 2;
			    box-shadow: 0 0 16px -2px #222;
			    background-color: #fff;
			    color: #fff;
			    width: 230px;
			    position: absolute;
			    top: 0;
			    left: 0;
			    margin-left: 100px;
			}

			.navbar-logo img {
			    padding: 14px 19px 19px;
			    margin: 0 auto;
			    width: 225px;
			}
		</style>
			<link rel="stylesheet" href="bootstrap.min.css"/>
	    <script src="js/jquery.js"></script>
		<script type="text/javascript" src="js/jquery-1.11.3.min.js"></script>
		<script type="text/javascript">
			$(function() {
			  $('body').on('keydown', '#first_name', function(e) {
			    console.log(this.value);
			    if (e.which === 32 &&  e.target.selectionStart === 0) {
			      return false;
			    }  
			  });
			});
		</script>
		<script type="text/javascript" language="javascript">
		function checkusername(){
			var status = document.getElementById("usernamestatus");
			var u = document.getElementById("username").value;
			if(u != ""){
				status.innerHTML = 'checking...';
				var hr = new XMLHttpRequest();
				hr.open("POST", "signin.php", true);
				hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				hr.onreadystatechange = function() {
					if(hr.readyState == 4 && hr.status == 200) {
						status.innerHTML = hr.responseText;
					}
				}
		        var v = "name2check="+u;
		        hr.send(v);
			}
		}
		</script>
		<script type="text/javascript">
			function clean (username) {
				var textfield = document.getElementById(username);
				var regex = /[^a-z0-9]/g;
				textfield.value = textfield.value.replace(regex, "");
			    }
		</script>
		
	</head>
	<body style= "background: url(<?php echo $photosrow; ?>) no-repeat center center; background-size: 100%; ">
		<div class="main">
				<section id="NavigationBar">
		<nav class="navbar navbar-expand-lg navbar-light">
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
			</button>
			<div class="collapse navbar-collapse" id="navbarNav">
				<ul class="navbar-nav ml-auto">
					<li class="nav-item">
						<a class="nav-link" href="r_home.php">Home</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="tospecial_about.php">About</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="r_login.php">Login</a>
					</li>
				</ul>
			</div>
		</nav>
		<div class="navbar-logo">
			<a href="r_home.php">
				<img src="r_images/logo.png">
			</a>
		</div>
	</section>
			<div class="holecontainer">
				<div class="container">
					<div>
						<div>
							
							<div class="signupform_content" >
								<h2 style="color: #e76f39;">Sign Up!</h2>
								<div class="signupform_text"></div>
								<div>
									<form action="" method="POST" class="registration">
										<div class="signup_form" style="background-color: #e76f39;">
											<div>
												<td >
													<input name="first_name" id="first_name" placeholder="Full Name" required="required" class="first_name signupbox_wihei signupbox" type="text" size="30" value="" >
												</td>
											</div>
											<div>
												<td>
													<input name="username" id="username" placeholder="Username" required="required" onBlur="checkusername()" onkeyup="clean('username')" onkeydown="clean('username')" class="user_name signupbox signupbox_wihei" type="text" size="30" value="" >
												</td>
												<td style=" margin: 10px; padding: 2px; background-color: white;">
												<p id="usernamestatus"></p>
												</td>
											</div>
											<div>
												<td>
													<input name="email" placeholder="Enter Your Email" required="required" class="email signupbox signupbox_wihei" type="email" size="30" value="">
												</td>
											</div>
											<div>
												<td>
													<input name="password" id="password-1" required="required" style="overflow: hidden;" placeholder="Enter New Password" class="password signupbox passbox_wihei" type="password" size="30" value="">
												</td>
											</div>
											<div class="gender">
												<td>
													<th>
														<div style="float: left;padding: 13px 13px 0 13px;font-size: 16px;font-weight: bold;">
															<input type="radio" name="gender" value="1" requred checked/><span>Male</span>
														</div>
													</th>
													<th>
														<div style="float: left;padding: 13px 13px 0 13px;font-size: 16px;font-weight: bold;">
															<input type="radio" name="gender" value="2" /><span>Female</span>
														</div>
													</th>
												</td>
												<style type="text/css">
													.select 
													{
														    font-size: 15px;
														    margin-left: -78px;
													}
													.select p{
														font-size: 20px;
														float: left;
														margin-top: 5px;
													}
													.select select{
														    float: right;
														    margin-top: 5px;
														    height: 35px;
														    width: 133px;
														    margin-bottom: 65px;
														}
													}
													</style>
												<td>
													<div class="select">
													<p>Account Type:</p>
													<select name="user_type">
														<option value="4">Everyone</option>
														<option value="1">Family</option>
														<option value="2">Doctor</option>
														<option value="3">Educator</option>
														
													</select>
													</div>
												</td>
											</div>
											<div>


												<input name="signup" class="uisignupbutton signupbutton" type="submit" value="Sign Up">
											</div>
											<div class="signup_error_msg">
									<?php 
										if (isset($error_message)) {echo $error_message;}

									?>
									</div>
										</div>
									</form>
									
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<br>
			<br>
<?php include ( "./inc/footer.inc.php"); ?>
