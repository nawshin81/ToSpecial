<?php include ( "./inc/connect.inc.php"); ?>
<?php  
ob_start();
session_start();
if (!isset($_SESSION['user_login'])) {
	header('location: signin.php');
}
else {
	$user = $_SESSION['user_login'];
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Work and Education</title>
	<link rel="icon" href="./img/tlogo.png" type="image/x-icon">
	<link rel="stylesheet" type="text/css" href="./css/header.css">
</head>
<body>

<?php include ( "./inc/header.inc.php"); ?>

<?php
//take the user back
if ($user) {
	if (isset($_POST['no'])) {
		header('Location: workedu_update.php');
	}
}
else {
	die("You must be logged in to view this page!");
}
?>

<?php 

$result = mysqli_query($conn,"SELECT * FROM users WHERE username='$user'");
$get_num = mysqli_fetch_assoc($result);
$type= $get_num['user_type'];

$updatework = @$_POST['updatework'];
$updateinfo = @$_POST['updateinfo'];
//Update Bio and first name last name query

	if($type==1)
	{
		$get_info = mysqli_query($conn,"SELECT work_as,work_in,academic FROM family WHERE username='$user'");
		$get_row = mysqli_fetch_assoc($get_info);
	}
	else if($type==2)
	{
		$get_info = mysqli_query($conn,"SELECT work_as,work_in,academic,specialist FROM doctor WHERE username='$user'");
		$get_row = mysqli_fetch_assoc($get_info);

		$db_specialist = $get_row['specialist'];
	}
	else if($type==3)
	{
		$get_info = mysqli_query($conn,"SELECT work_as,work_in,academic FROM educator WHERE username='$user'");
		$get_row = mysqli_fetch_assoc($get_info);
	}
	else
	{
		$get_info = mysqli_query($conn,"SELECT work_as,work_in,academic FROM everyone WHERE username='$user'");
		$get_row = mysqli_fetch_assoc($get_info);
	}


$db_work_in = $get_row['work_in'];
$db_work_as = $get_row['work_as'];
$db_academic = $get_row['academic'];




//submit what the user type in database
if ($updatework) {
	$work_in = strip_tags(@$_POST['work_in']);
	$work_in = trim($work_in);
	$work_in = ucwords($work_in);
	$work_as = strip_tags(@$_POST['work_as']);
	$work_as = trim($work_as);
	$work_as = ucwords($work_as);
		//submit the form to database
		if($type==1)
		{
			$info_submit_query = mysqli_query($conn,"UPDATE family SET work_in='$work_in', work_as='$work_as' WHERE username='$user'");
		}
		else if($type==2)
		{
			$info_submit_query = mysqli_query($conn,"UPDATE doctor SET work_in='$work_in', work_as='$work_as' WHERE username='$user'");
		}
		else if($type==3)
		{
			$info_submit_query = mysqli_query($conn,"UPDATE educator SET work_in='$work_in', work_as='$work_as' WHERE username='$user'");
		}
		else
		{
			$info_submit_query = mysqli_query($conn,"UPDATE everyone SET work_in='$work_in', work_as='$work_as' WHERE username='$user'");
		}
		
		echo "
		</br>
		</br>
		<p class='error_echo'>Your Profile Information Has Been Updated.</p>";

		if ($type==1) {
			header("Location: family_about.php?u=$user");
					}
		else if($type==2) {
			header("Location: doctor_about.php?u=$user");
					}
		else if($type==3) {
			header("Location: educator_about.php?u=$user");
					}
		else if($type==4) {
		    header("Location: everyone_about.php?u=$user");
					}


}

if ($updateinfo) {
	$academic = strip_tags(@$_POST['academic']);
	$academic = trim($academic);
	$academic = ucwords($academic);
	$specialist = strip_tags(@$_POST['specialist']);
	$specialist = trim($specialist);
	$specialist = ucwords($specialist);

		//submit the form to database

		if($type==1)
		{
			$info_submit_query = mysqli_query($conn,"UPDATE family SET academic='$academic' WHERE username='$user'");
		}
		else if($type==2)
		{
			$info_submit_query = mysqli_query($conn,"UPDATE doctor SET academic='$academic', specialist='$specialist' WHERE username='$user'");
		}
		else if($type==3)
		{
			$info_submit_query = mysqli_query($conn,"UPDATE educator SET academic='$academic' WHERE username='$user'");
		}
		else
		{
			$info_submit_query = mysqli_query($conn,"UPDATE everyone SET academic='$academic' WHERE username='$user'");
		}
		
		echo "</br></br><p class='error_echo'>Your Profile Information Has Been Updated.</p>
		";
		
		if ($type==1) {
			header("Location: family_about.php?u=$user");
					}
		else if($type==2) {
			header("Location: doctor_about.php?u=$user");
					}
		else if($type==3) {
			header("Location: educator_about.php?u=$user");
					}
		else if($type==4) {
		    header("Location: everyone_about.php?u=$user");
					}
}

?>
<div style="margin-top: 48px;">
<div style="width: 900px; margin: 0 auto;">
	<ul>
		<li style="float: left;">
			<div class="settingsleftcontent">
				<ul>
					<li><a href="profile_update.php">Profile Update</a></li>
					<li><a href="account_update.php">Account</a></li>
					<li><a href="password_update.php">Password</a></li>
					<li><a href="workedu_update.php" style="background-color: #e76f39; border-radius: 3px; color: #fff;">Work and Education</a></li>
					<li><a href="cbinfo_update.php">Contact and Basic Info</a></li>
					<li><a href="location_update.php">Location and Places</a></li>
					<li><a href="details_update.php">Details About</a></li>
				</ul>
			</div>
			<div class="settingsleftcontent">
				<?php include './inc/profilefooter.inc.php'; ?>
			</div>
		</li>
		<li style="float: right;">
			<div class="uiaccountstyle">
				<form action="workedu_update.php" method="post">
				<h2><p>Update Work: </p></h2></br>
				Work In: </br><input type="text" name="work_in" id="work_in" class="placeholder" size="30" value="<?php echo $db_work_in; ?>"> </br></br>
				Work As: </br><input type="text" name="work_as" id="work_as" class="placeholder" size="30" value="<?php echo $db_work_as; ?>"> </br></br>
				<input type="submit" name="updatework" id="updatework" class="confirmSubmit" value="Update Information">&nbsp;&nbsp;
				<input type="submit" name="no" value="Cancel" title="Back to Settings" class="cancelSubmit"> </br>
				</form>
			</div>
			<div class="uiaccountstyle">
				<form action="workedu_update.php" method="post">
				<h2><p>Update Education: </p></h2></br>
				academic: </br><input type="text" name="academic" id="academic" class="placeholder" size="30" value="<?php echo $db_academic; ?>"></br></br>
				<?php


					if($type==1)
					{
						
					}
					else if($type==2)
					{
						echo '
					Specialist: </br><input type="text" name="specialist" id="specialist" class="placeholder" size="30" value=" '.$db_specialist.'">
					</br></br>';
					}
					else if($type==3)
					{
						
					}
					else
					{
						
					}
					
?>
				<input type="submit" name="updateinfo" id="updateinfo" class="confirmSubmit" value="Update Information">&nbsp;&nbsp;
				<input type="submit" name="no" value="Cancel" title="Back to Settings" class="cancelSubmit"> </br>
				</form>
			</div>
		</li>
	</ul>
</div>
</div>
</body>
</html>