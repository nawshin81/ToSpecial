<?php include ( "./inc/connect.inc.php" ); ?>

<?php 
session_start();
//declear variable
$error = "";
//for pass changed by another
$unameMail = "";
$uCodeMail = "";
$unameMail = $_GET['u'];
$uCodeMail = $_GET['code'];
if (($_GET['u'] && $_GET['code']) == NULL) {
			header("location: passRecover.php");
		}else if (($_GET['u'] || $_GET['code']) == NULL) {
			header("location: passRecover.php");
		}else if (($_GET['u'] && $_GET['code']) != NULL) {
			$check_unameMail = mysqli_query($conn,("SELECT * FROM users WHERE username='$unameMail' AND confirmCode!='0' AND confirmCode='$uCodeMail'");
			$check_unameMail_num = mysqli_num_rows($check_unameMail);
			$usernmm_fetch_query = mysqli_fetch_assoc($check_unameMail);
			$get_first_nm_fetch_query = $usernmm_fetch_query['first_name'];
			$get_unam_fetch_query = $usernmm_fetch_query['username'];
			$get_email_fetch_query = $usernmm_fetch_query['email'];
			if ($check_unameMail_num >= 1) {
				$chengePass_cnfrm = "
							<form action='confirmationPass.php?u=".$unameMail."&code=".$uCodeMail."' method='POST'>
								<p>Change Your Password!</p></br>
									New Password:</br></br><input type='password' name='newpassword' class='placeholder' size='30' required autofocus></br></br>
								</hr>
									Retype Password:</br></br><input type='password' name='newpassword2' class='placeholder' size='30' required></br></br>
								</hr>
								<input class='submRessetp' type='submit' name='confrmChangepass' id='senddata' value='Reset Password'>
							</form>
						";
			}else {
				header('localtion: passRecover.php');
			}

			$newpassword = strip_tags(@$_POST['newpassword']);
			$newpassword = ($newpassword);//mysqli_real_escape_string
			$repear_password = strip_tags(@$_POST['newpassword2']);
			$repear_password = ($repear_password);//mysqli_real_escape_string
			if (isset($_POST['confrmChangepass'])) {
				if ($newpassword == $repear_password) {
					$newpassword = md5($newpassword);
					$updatePassQuery = "UPDATE users SET confirmCode='0', password='$newpassword' WHERE (username='$get_unam_fetch_query')";
					if (mysqli_query($conn,($updatePassQuery)) {
						$error = "<p class='succes_echo'>Password successfully changed.</p>";
						// send email
						$msg = "";
						
					}else {
						$error = "<p class='error_echo'>Password couldn't be changed.</p>";
					}
				}else {
					$error = "<p class='error_echo'>Password don't match!</p>";
					$chengePass_cnfrm = "
						<form action='confirmationPass.php?u=".$unameMail."&code=".$uCodeMail."' method='POST'>
							<p>Change Your Password!</p></br>
								New Password:</br></br><input type='password' name='newpassword' class='placeholder' size='30' required autofocus></br></br>
							</hr>
								Retype Password:</br></br><input type='password' name='newpassword2' class='placeholder' size='30' required></br></br>
							</hr>
							<input class='submRessetp' type='submit' name='confrmChangepass' id='senddata' value='Reset Password'>
						</form>";'
				}
			}
		}


 ?>


<!DOCTYPE html>
<html>
<head>
	<title>Password Recover</title>
	<link rel="icon" href="./img/tlogo.png" type="image/x-icon">
	<meta charset="uft-8">
	<link rel="stylesheet" type="text/css" href="./css/style.css">
</head>
<body>

<div>
	<div><?php include ( "./inc/login.inc.php" ); ?></div></br></br>
	<div class="pass_body1" style="min-width: 900px;">
			<?php echo $error; ?>
		<div class="pass_body2">
				<?php
					if (isset($chengePass_cnfrm)) {
						echo $chengePass_cnfrm;
					}else {
						echo '
							<form action="passRecover.php" method="POST">
								<p>Find your account!</p></br>
									Enter email or username:</br></br><input type="text" name="username" class="placeholder" size="30" required autofocus></br></br>
								</hr></br>
								<input class="submRecov" type="submit" name="searchId" id="senddata" value="Search">
							</form>
						';
					}
				?>
			
		</div>
	</div>
	<div><?php include ( "./inc/footer.inc.php"); ?></div>
</div>

</body>
</html>