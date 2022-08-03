<?php include ( "./inc/connect.inc.php" ); ?>
<?php 
session_start();
if (isset($_COOKIE['user_'])) {
	$_SESSION['user_'] = $_COOKIE['user_'];
	header("location: newsfeed.php");
	exit();
}

if (isset($_POST['login']))
 {
		if (isset($_POST['user_login']) && isset($_POST['password_login'])) {
			$user_login = $_POST['user_login']; // mysql_real_escape_string(
			$user_login = mb_convert_case($user_login, MB_CASE_LOWER, "UTF-8");	
			$password_login = $_POST['password_login']; // mysql_real_escape_string(
			$rememberme = $_POST['rememberme'];		
			$num = 0;
			$password_login_md5 = md5($password_login);
			$result = mysqli_query($conn, "SELECT * FROM users WHERE (username='$user_login' || email='$user_login') AND password='$password_login_md5' AND activated='1' AND blocked_user='0'");
			$num = mysqli_num_rows($result);
			$get_user_email = mysqli_fetch_assoc($result);
				$get_user_uname_db = $get_user_email['username'];
			if ($num>0) {
				$_SESSION['user_login'] = $get_user_uname_db;
				if ($rememberme != NULL) {
					setcookie('user_login', $user_login, time() + (365 * 24 * 60 * 60), "/");
				}
				header('location: newsfeed.php');
				exit();
			}
			else {
				$result1 = mysqli_query($conn, "SELECT * FROM users WHERE (username='$user_login' || email='$user_login') AND password='$password_login_md5' AND activated ='0'");
				$num1 = mysqli_num_rows($result1);
				$get_user_email = mysqli_fetch_assoc($result1);
				$get_user_name_db = $get_user_email['username'];
				$get_user_email_db = $get_user_email['email'];
				$get_user_confrmCode_db = $get_user_email['confirmCode'];
				if ($num1>0) {
					$_SESSION['user_loginn'] = $get_user_name_db ;
					$_SESSION['user_confrmCode'] = $get_user_confrmCode_db;
					$success_message = '
						<div class="maincontent_text" style="text-align: center;">
						<font face="bookman">Account activation code send to you. <br>
							Please check your mail: '.$get_user_email_db.'
						</font>
						<form action="signin.php" method="POST">
								Enter varification code:<input type="text" name="confrmCode" class="submRecov" size="30" required></br>
							
							<input class="submRecov" type="submit" name="submconfrmCode" id="senddata" 	</form>
						</div>
						';
						
				}
			}
		}

	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>login</title>
	<link rel="stylesheet" type="text/css" href="css/r_login.css"/>
	<link rel="stylesheet" href="bootstrap.min.css"/>
</head>
<body>
	<form action="" method="POST">
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
						<a class="nav-link" href="signin.php">SignUp</a>
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
	<div class="loginbox" style="  height: 450px;">
    <img src="r_images/avatar.png" class="avatar">
        <h1>Login Here</h1>
        <form>
            <p>Username</p>
            <!-- <input type="text" name="Username" placeholder="Enter Username"> -->
            <input type="text" name="user_login" id="email" required="required" value="" class="inputtext">

            <p>Password</p>
           <input type="password" name="password_login" required="required" id="pass" value="" class="inputtext">
            <input type="submit" name="login" class="uiloginbutton" value="Log In">
        <!-- <table>
            <tr>
            	<td class="">
					<label> -->
						<div>
							<input class="logincheckbox uiInputLabelInput" name="rememberme" type="checkbox" checked>
							<div class="uiInputLabelLabel" style="color:#FFFFFF" align="center"><p>Keep me logged in</p>
							<p><a href="passRecover.php" style="text-decoration:none; color:#FFFFFF">Forgot your password?</a></p>
							</div>
						</div>
<!-- 					</label>
			</tr>
		</table>
        </form> -->
    </div>
</form>
</body>
</html>