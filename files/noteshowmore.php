<?php 
 include ( "inc/connect.inc.php");
ob_start();
session_start();
if (!isset($_SESSION['user_login'])) {
	header('location: signin.php');
}
else {
	$user = $_SESSION['user_login'];
}
 //showmore for profile home post
 $noteshowmorelastid = $_REQUEST['noteshowmorelastid'];
 if (isset($noteshowmorelastid)) {
 	$noteshowmorelastid = $_REQUEST['noteshowmorelastid'];
 }else {
 	header("location: index.php");
 }
 if ($noteshowmorelastid >= 1) {
 			//getting note_uname
		 $result = mysqli_query($conn,"SELECT * FROM posts WHERE id ='$noteshowmorelastid'") or die(mysqli_error());
		 $name = mysqli_fetch_assoc($result);
		 $note_uname = $name['user_posted_to'];
		//timeline query table

		if ($user == $note_uname) {
			$getposts = mysqli_query($conn,"SELECT * FROM posts WHERE user_posted_to ='$note_uname' AND note='1' AND id < $noteshowmorelastid ORDER BY id DESC LIMIT 5") or die(mysqli_error());
		}else {
			$getposts = mysqli_query($conn,"SELECT * FROM posts WHERE user_posted_to ='$note_uname' AND note='1' AND note_privacy='public' AND id < $noteshowmorelastid ORDER BY id DESC LIMIT 5") or die(mysqli_error());
		}
		if (mysqli_num_rows($getposts)) {
			while ($row = mysqli_fetch_assoc($getposts)) {
			include ( "./inc/getProfilepost.inc.php" );
			$noteshowmorelastvalue = $row['id'];
		}
			echo '<li class="noteshowmore" id="'.$noteshowmorelastvalue.'" >Show More</li>';
		}else {
			echo '<li class="nomorepost">Opps! Nothing more found.</li>';
	}
 }
?>