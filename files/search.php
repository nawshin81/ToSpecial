<?php include ( "./inc/connect.inc.php" ); ?>
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
//getting user info
$check_user = mysqli_query($conn,"SELECT * FROM users WHERE username='$user'");
$get_check_user_row = mysqli_fetch_assoc($check_user );

?>

<!DOCTYPE html>
<html>
<head>
	<title>Search </title>
	<link rel="icon" href="./img/tlogo.png" type="image/x-icon">
	<link rel="stylesheet" type="text/css" href="./css/header.css">
	<script type="text/javascript" src="js/main.js"></script>
	<script type="text/javascript" src="js/jquery-1.11.3.min.js"></script>
	<script type="text/javascript">
		$(function() {
		  $('body').on('keydown', '#search', function(e) {
		    console.log(this.value);
		    if (e.which === 32 &&  e.target.selectionStart === 0) {
		      return false;
		    }  
		  });
		});
	</script>
</head>
<body>
<?php include ( "./inc/header.inc.php" ); ?>
<div class="search_body">
<center>
	<div class="search_nav">
		<ul class="search_nav_menu"></ul>
	</div>
	<?php
		if (($_GET['keywords'] && $_GET['topic']) == NULL) {
			header("location: newsfeed.php");
		}else if (($_GET['keywords'] || $_GET['topic']) == NULL) {
			header("location: newsfeed.php");
		}else if (($_GET['keywords'] && $_GET['topic']) != NULL) {
			$search_value = "";
			$count = "";
			if (isset($_GET['keywords'])) {
				if ($_GET['topic'] == "User") {
					$search_value = $_GET['keywords'];
					$search_value = trim($search_value);
				if ($search_value == "") {
				echo '
				<div class="search_banner">Please input something!
					</div>
				';
			}else {
            if ( $get_check_user_row) {
				$query = "SELECT id,username,first_name,profile_pic,cover_pic,city,hometown,gender,verify_id FROM users where (username like '%$search_value%' OR first_name like '%$search_value%') AND (activated !='0')";
				}
				$query = mysqli_query($conn,$query) or die ("could not count");
				$count = mysqli_num_rows($query);
			if ($count == 0){
				echo '<div class="search_banner">No match found!
				</div>';
			}else {
				echo '<div class="search_banner">Result for: 
						<span class="search_for">'.$search_value.'</span><br>
						<div class="search_found_num">'.$count.' matches found...</div>
					</div>
					<div class="search_result_container">
						';
					while ($row=mysqli_fetch_array($query)) {
						$id = $row['id'];
						$username = $row['username'];
						$first_name = $row['first_name'];
						$city = $row['city'];
						$hometown = $row['hometown'];
						// $company = $row['company'];
						// $school = $row['school'];
						$profile_pic_db = $row['profile_pic'];
						$cover_pic_db = $row['cover_pic'];
						//check for propic delete
						$pro_changed = mysqli_query($conn,"SELECT * FROM posts WHERE added_by='$username' AND (discription='changed his profile picture.' OR discription='changed her profile picture.') ORDER BY id DESC LIMIT 1");
						$get_pro_changed = mysqli_fetch_assoc($pro_changed);
		$pro_num = mysqli_num_rows($pro_changed);
		if ($pro_num == 0) {
			$profile_pic = "img/default_propic.png";
		}else {
			$pro_changed_db = $get_pro_changed['photos'];
		if ($pro_changed_db != $profile_pic_db) {
			$profile_pic = "img/default_propic.png";
		}else {
			$profile_pic = "userdata/profile_pics/".$profile_pic_db;
		}
		}
						//check for cover delete
						$pro_changed = mysqli_query($conn,"SELECT * FROM posts WHERE added_by='$username' AND (discription='updated his cover photo.' OR discription='updated her cover photo.') ORDER BY id DESC LIMIT 1");
						$get_pro_changed = mysqli_fetch_assoc($pro_changed);
		$pro_num = mysqli_num_rows($pro_changed);
		if ($pro_num == 0) {
			$cover_pic= "img/default_covpic.png";
		}else {
			$pro_changed_db = $get_pro_changed['photos'];
		if ($pro_changed_db != $cover_pic_db ) {
			$cover_pic= "img/default_covpic.png";
		}else {
			$cover_pic= "userdata/profile_pics/".$cover_pic_db ;
		}
		}
						$default_covpic = "img/default_covpic.png";
						$verify_id_user = $row['verify_id'];

						echo '
							<div class="user_search_result_box" >';
								if ($cover_pic_db == "") {
									echo '<div style= "background: url('.$default_covpic.') repeat center center; height: 130px; width: 300px; border-radius: 2px; margin: -1px 0 0 -1px; background-size: cover !important; border-bottom: 1px solid #d3d6db;">';
								}else {
									echo '<div style= "background: url( '.$cover_pic.') repeat center center; height: 130px; width: 300px; border-radius: 2px;margin: -1px 0 0 -1px;background-size: cover !important; border-bottom: 1px solid #d3d6db;">';
								}
									echo '<div class="coll1">';
									if ($profile_pic_db == "") {
										echo "<img src='img/default_propic.png' />";
									}else {
										echo "<img src=".$profile_pic." />";
									}
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
								//}
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
				}
			}else if ($_GET['topic'] == "Post") {
				$search_value = $_GET['keywords'];
				$search_value = trim($search_value);
				$search_value = preg_replace('/[^\p{L}0-9\s]+/u', '-', $search_value);
				if ($search_value == "") {
				echo '
				<div class="search_banner">Please input something!
					</div>
				';
			}else {

				$search_for = $search_value;
				$query = "SELECT id,body,date_added,added_by,photos,user_posted_to,discription FROM posts where  (body like '%$search_value%')  ORDER BY id DESC";
				$query = mysqli_query($conn,$query) or die ("could not count");
				$count = mysqli_num_rows($query);
			if ($count == 0){
				echo '<div class="search_banner">No match found!
				</div>';
			}else {
				echo '<div class="search_banner">Result for: 
						<span class="search_for">'.$search_value.'</span><br>
						<div class="search_found_num">'.$count.' matches found...</div>
					</div>
					<div class="search_result_container">
						';
					while ($row=mysqli_fetch_array($query)) {
							include ( "./inc/getProfilepost.inc.php" );
						}
					}
				}
			}else if ($_GET['topic'] == "ToSpecial") {
				$search_value = $_GET['keywords'];
				$search_value = trim($search_value);
				if ($search_value == "") {
				echo '
				<div class="search_banner">Please input something!
					</div>
				';
			 
			}

			}
		}
	}
		
?>
</center>
</div>

</body>
</html>