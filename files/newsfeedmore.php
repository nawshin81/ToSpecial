<?php 
 include ( "inc/connect.inc.php");
session_start();
if (!isset($_SESSION['user_login'])) {
	header('location: signin.php');
}
else {
	$user = $_SESSION['user_login'];
}
 //showmore for profile home post
 $newsfeedlastid = $_REQUEST['newsfeedlastid'];
 if (isset($newsfeedlastid)) {
 	$newsfeedlastid = $_REQUEST['newsfeedlastid'];
 }else {
 	header("location: index.php");
 }

 $get_type = mysqli_query($conn,"SELECT user_type FROM users WHERE username='$user'");
	$user_type = mysqli_fetch_assoc($get_type);
	$type = $user_type['user_type'];

 if ($newsfeedlastid >= 1) {

 	if($type==1)
			{
				$getposts = mysqli_query($conn,"SELECT * FROM posts WHERE newsfeedshow ='1' AND report ='0' AND id < $newsfeedlastid  ORDER BY FAMSERIAL") or die(mysqli_error());
			}
			else if ($type==2)
			 {
			 	$getposts = mysqli_query($conn,"SELECT * FROM posts WHERE newsfeedshow ='1' AND report ='0' AND id < $newsfeedlastid  ORDER BY DOCSERIAL") or die(mysqli_error());
			}
			else if($type==3)
			{
				$getposts = mysqli_query($conn,"SELECT * FROM posts WHERE newsfeedshow ='1' AND report ='0' AND id < $newsfeedlastid  ORDER BY EDUSERIAL") or die(mysqli_error());
			}
			else if($type==4)
			{
				$getposts = mysqli_query($conn,"SELECT * FROM posts WHERE newsfeedshow ='1' AND report ='0' AND id < $newsfeedlastid   ORDER BY id DESC") or die(mysqli_error());
			}
		// //timeline query table
		// $getposts =mysqli_query($conn,"SELECT * FROM posts WHERE newsfeedshow ='1' AND report ='0' AND id < $newsfeedlastid ORDER BY id DESC") or die(mysqli_error());
		if (mysqli_num_rows($getposts)) {
		
		//declear variable
		$getpostsNum= 0;
			while ($row = mysqli_fetch_assoc($getposts)) {
				$added_by = $row['added_by'];
				if ($added_by == $user) {
					include ( "./inc/newsfeed.inc.php");
					$getpostsNum++;
				}else {
					$checkDeactiveUser=mysqli_query($conn,"SELECT * FROM users WHERE username = '$added_by'") or die(mysqli_error());
					$checkDeactiveUser_row = mysqli_fetch_assoc($checkDeactiveUser);
					$activeOrNot = $checkDeactiveUser_row ['activated'];
					if ($activeOrNot != '0') {
						$check_if_follow =mysqli_query($conn,"SELECT * FROM follow WHERE (user_from='$user' AND user_to='$added_by ') ORDER BY id DESC LIMIT 2");
						$num_follow_found = mysqli_num_rows($check_if_follow);
						if ($num_follow_found != "") {
							include ( "./inc/newsfeed.inc.php");
							$getpostsNum++;
						}
					}
				}
				
				$newsfeedlastvalue = $row['id'];
				if ($getpostsNum == 10){
					break;
				}
		}
			echo '<li class="newsfeedmore" id="'.$newsfeedlastvalue.'" >Show More</li>';
		}else {
			echo '<li class="nomorepost">Opps! Nothing more found.</li>';
	}
 }
?>