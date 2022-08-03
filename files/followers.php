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
<?php 
	$username ="";
	$firstname ="";
	if (isset($_GET['u'])) {
		$username = ($_GET['u']);//real_escape_stringmysql_
		if (ctype_alnum($username)) {
			//check user exists
			$check = mysqli_query($conn,"SELECT username, first_name FROM users WHERE username='$username'");
			if (mysqli_num_rows($check)===1) {
				$get = mysqli_fetch_assoc($check);
				$username = $get['username'];
				$firstname = $get['first_name'];
			}
			else {
				die();
			}
		}
	}

	$get_title_info = mysqli_query($conn,"SELECT * FROM users WHERE username='$username'");
	$get_title_fname = mysqli_fetch_assoc($get_title_info);
	$title_fname = $get_title_fname['first_name'];
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo $title_fname; ?> </title>
	<link rel="icon" href="./img/tlogo.png" type="image/x-icon">
	<link rel="stylesheet" type="text/css" href="./css/header.css">
	<script type="text/javascript" src="js/main.js"></script>
</head>
<body>

	<?php 

	$result = mysqli_query($conn,"SELECT * FROM users WHERE username='$username'");
	$num = mysqli_num_rows($result);
	$dbtype = mysqli_fetch_assoc($result);
	$type = $dbtype['user_type'];

	if ($num == 1) {

	include ( "./inc/header.inc.php");
	include ( "./inc/profile.inc.php");
	echo '<li style="float: right;">
							
							
					<div >
						<nav>
						<ul>
						<li><a href="photo.php?u='.$username.'">Photo</a></li>
						<li><a href="friends.php?u='.$username.'" style="background-color: #cdcdcd; color: #505050">Friend</a></li>';

						 if ($type==1) {
					    echo '<li><a href="family_about.php?u='.$username.'">About</a></li>';}	
						else if ($type==2) {
					    echo '<li><a href="doctor_about.php?u='.$username.'">About</a></li>';}
					    else if ($type==3) {
					    echo '<li><a href="educator_about.php?u='.$username.'">About</a></li>';}
					    else if ($type==4){
					    echo '<li><a href="everyone_about.php?u='.$username.'">About</a></li>';}

						echo 
						'
						<li><a href="profile.php?u='.$username.'" >Post</a></li>
						</ul>
						</nav>
					</div>
					
				</li>
			</ul>
			
			</div>
		</div>
	</div>';
	$get_msg_num = mysqli_query($conn,"SELECT * FROM pvt_messages WHERE user_from='$username' AND user_to='$user' LIMIT 2");
	$msg_count = mysqli_num_rows($get_msg_num);
	if (($msg_count >=1 ) || ($username == $user)) {
			$count = "";
			$friends_num = 1;
			$count_frnd_num = 0;
				//count followers
				$queryfollowers = "SELECT * FROM follow WHERE user_to='$username' ORDER BY id DESC";
				$queryfollowers = mysqli_query($conn,$queryfollowers ) or die ("");
				$countfollowers = mysqli_num_rows($queryfollowers );
				//count following
				$queryfollowing = "SELECT * FROM follow WHERE user_from='$username' ORDER BY id DESC";
				$queryfollowing= mysqli_query($conn,$queryfollowing) or die ("");
				$countfollowing = mysqli_num_rows($queryfollowing);
				
				//getting all friend
				$queryfollowing2 = "SELECT * FROM follow WHERE user_from='$username' ORDER BY id DESC";
				$queryfollowing2= mysqli_query($conn,$queryfollowing2) or die ("");
				while ($row=mysqli_fetch_array($queryfollowing2)) {
						$user_from = $row['user_from'];
						$user_to = $row['user_to'];
						//user to if friend 
						
						$if_user_to_follow = mysqli_query($conn,"SELECT * FROM follow WHERE (user_from='$user_to' AND user_to='$user_from')");
						$count_user_to_follow = mysqli_num_rows($if_user_to_follow);
						if ($count_user_to_follow != 0) {
						  $count_frnd_num = $friends_num++;
						}
					     }
					    //buddy list menu
				echo '<div>
					     <table class="friendsmenu">
							<tr>
								<th><a href="friends.php?u='.$username.'"><h1>All Friends </br>'.$count_frnd_num.'</h1></a></th>
								<th ><a href="following.php?u='.$username.'" ><h1>Following </br>'.$countfollowing.'</h1></a></th>
								<th style="background-color: #686B68;"><a href="followers.php?u='.$username.'"><h1>Followers </br>'.$countfollowers.'</h1></a></th>
							</tr>
						</table>
					</div>';
					if ($countfollowers == 0){
						echo '<div class="search_banner">No followers found!
						</div>';
					}else {
					echo ' <div class="search_result_container">
						';
					while ($row=mysqli_fetch_array($queryfollowers )) {
						$user_from = $row['user_from'];
						$user_to = $row['user_to'];
						//spacif user profile
						$family_query = mysqli_query($conn,"SELECT id,username,first_name,profile_pic,cover_pic,city,hometown,gender,verify_id FROM users where username='$user_from' ORDER BY id DESC LIMIT 1");
						$family_row = mysqli_fetch_assoc($family_query) or die ("");
						$id = $family_row['id'];
						$username = $family_row['username'];
						$first_name = $family_row['first_name'];
						$city = $family_row['city'];
						$hometown = $family_row['hometown'];
						// $company = $family_row['company'];
						// $school = $family_row['school'];
						$profile_picuser_from_db = $family_row['profile_pic'];
						$cover_picuser_from_db = $family_row['cover_pic'];
						$verify_id_user = $family_row['verify_id'];
						
						//check for userfrom propic delete
						$pro_changed = mysqli_query($conn,"SELECT * FROM posts WHERE added_by='$user_from' AND (discription='changed his profile picture.' OR discription='changed her profile picture.') ORDER BY id DESC LIMIT 1");
						$get_pro_changed = mysqli_fetch_assoc($pro_changed);
		$pro_num = mysqli_num_rows($pro_changed);
		if ($pro_num == 0) {
			$profile_picuser_from = "img/default_propic.png";
		}else {
			$pro_changed_db = $get_pro_changed['photos'];
		if ($pro_changed_db != $profile_picuser_from_db) {
			$profile_picuser_from = "img/default_propic.png";
		}else {
			$profile_picuser_from = "userdata/profile_pics/".$profile_picuser_from_db;
		}
		}
						
						//Check whether the user has uploaded a cover pic or not
$check_pic = mysqli_query($conn,"SELECT cover_pic FROM users WHERE username='$user'");
$get_pic_row = mysqli_fetch_assoc($check_pic);
$cover_pic_db = $get_pic_row['cover_pic'];
//check for userfrom propic delete
						$pro_changed = mysqli_query($conn,"SELECT * FROM posts WHERE added_by='$user_from' AND (discription='updated his cover photo.' OR discription='updated her cover photo.') ORDER BY id DESC LIMIT 1");
						$get_pro_changed = mysqli_fetch_assoc($pro_changed);
		$pro_num = mysqli_num_rows($pro_changed);
		if ($pro_num == 0) {
			$cover_picuser_from= "img/default_covpic.png";
		}else {
			$pro_changed_db = $get_pro_changed['photos'];
		if ($pro_changed_db != $cover_picuser_from_db) {
			$cover_picuser_from= "img/default_propic.png";
		}else {
			$cover_picuser_from= "userdata/profile_pics/".$cover_picuser_from_db;
		}
		}

						echo '
							<div class="user_search_result_box" >';
									echo '<div style= "background: url('.$cover_picuser_from.') repeat center center; height: 130px; width: 300px; border-radius: 2px; margin: -1px 0 0 -1px; background-size: cover !important; border-bottom: 1px solid #d3d6db;">';
									echo '<div class="coll1">';
										echo "<img src=".$profile_picuser_from." />";
									echo '</div>';
									if ($user == $username) {
										echo '<div class="coll3">
										<form action="profile_update.php" method="POST">
										<button value="button" style="float: right; margin-top: 104px;" name="updateProfile" >Edit profile</button />
										</form>
										</div>';
									}else {
										echo '<div class="coll3" style=" float: right; margin: 104px 0 0 5px;">
										<form action="messages.php?u='.$username.'" method="POST">
										<input input type="submit" name="sendmsg" value="Message" />
										</form>
										</div>
										<div class="coll3" style="float: right; margin-top: 104px;">
										<form action="profile.php?u='.$username.'" method="POST">
										<button value="button" name="viewProfile" >View profile</button />
										</form>
										</div>';
									}
									
								echo '</div>
								<div class="coll2">';
									if ($verify_id_user == 'yes') {
									echo '<span class="coll2_spn" style="margin-right: 3px; float: left;"><a href="profile.php?u='.$username.'">'.$first_name.'</a></span><div class="verifiedicon" style="background: url(img/verifiLogo.png) repeat; background-size: cover !important; margin-top: -2px; width: 19px; height: 19px;" title="Verified profile"></div>';
								}else {
									echo '<span class="coll2_spn"><a href="profile.php?u='.$username.'">'.$first_name.'</a></span>';
								}
								echo '
								</div><br><br>
								<div class="coll4">';
								// if ($school == "") {
								// 		if ($company == "") {
								// 		//nothing
								// 	}else {
								// 		echo 'Work at <span style="color: #505050;">'.$company.'</span><br>' ;
								// 	}
								// }else {
								// 	echo 'Study at <span style="color: #505050;">'.$school.'</span><br>' ;
								// }
								if ($city == "") {
										if ($hometown == "") {
										//nothing
									}else {
										echo 'From <span style="color: #505050;">'.$hometown.'</span>' ;
									}
								}else {
									echo 'Lives in <span style="color: #505050;">'.$city.'</span><br>' ;
								}
								echo '
								</div>
							</div>
						';

						}
					}
				}else {
				echo "<p style='text-align: center; color: #4A4848; margin: 30px; font-weight: bold; font-size: 36px;'>Sorry! Nothing to view. </p>";
		}
	}else {
	header("location: profile.php?u=$user");
} 

?>
</body>
</html>