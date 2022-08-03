<?php 

//check for noti
// $check_for_post_noti = mysqli_query($conn, "SELECT * FROM post_comments WHERE posted_to='$user' posted_by != '$user' AND opened='no' ORDER BY id DESC");
// if($check_for_post_noti == true){
// 	$post_noti_num = mysqli_num_rows($check_for_post_noti);
// }else $post_noti_num = 0;


$check_for_post_noti = mysqli_query($conn, "SELECT * FROM post_comments WHERE posted_to='$user'  AND opened='no'");
$post_noti= mysqli_fetch_assoc($check_for_post_noti);
$noti_numrows = mysqli_num_rows($check_for_post_noti);
$noti_num = $noti_numrows;



//get msg row
$get_unread_query = mysqli_query($conn, "SELECT opened FROM pvt_messages WHERE user_to='$user' and  opened='no'");
$get_unread = mysqli_fetch_assoc($get_unread_query);
$unread_numrows = mysqli_num_rows($get_unread_query);
$unread_msg_numrows = $unread_numrows;

//get follow row
$get_follow_query = mysqli_query($conn, "SELECT opened FROM follow WHERE user_to='$user' && opened='no'");
$get_follow = mysqli_fetch_assoc($get_follow_query );
$follow_numrows = mysqli_num_rows($get_follow_query );
$unread_follow_numrows = $follow_numrows;

//profile pic upload
$check_pic = mysqli_query($conn, "SELECT profile_pic FROM users WHERE username='$user'");
$get_pic_row = mysqli_fetch_assoc($check_pic);
$profile_pic_db = $get_pic_row['profile_pic'];
						//check for propic delete
						$pro_changed = mysqli_query($conn, "SELECT * FROM posts WHERE added_by='$user' AND (discription='changed his profile picture.' OR discription='changed her profile picture.') ORDER BY id DESC LIMIT 1");
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

//getting user first message
$get_first_message = mysqli_query($conn, "SELECT * FROM pvt_messages WHERE user_to='$user' ORDER BY id DESC LIMIT 1");
$first_message_row = mysqli_fetch_assoc($get_first_message);
$first_message_id = $first_message_row['id'];
$first_message_uname = $first_message_row['user_from'];
if (isset($_POST['gotoinbox'])) {
	$setopened_query = mysqli_query($conn, "UPDATE pvt_messages SET opened='yes' WHERE user_to='$user'");
	header("location: messages.php");
}

//notification 
if (isset($_POST['gotonoti'])) {
	$pstopened_query = mysqli_query($conn, "UPDATE post_comments SET opened='yes' WHERE posted_to='$user'");
    header("location: notification.php");
}

//followers
if (isset($_POST['gotofollow'])) {
	$pstopened_query = mysqli_query($conn, "UPDATE follow SET opened='yes' WHERE user_to='$user'");
	header("location: followRequest.php");
}

//logout
if (isset($_POST['logout'])) {
	header("location: logout.php");
}
//signup
if (isset($_POST['signup'])) {
	header("location: signin.php");
}

?>


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
<script type="text/javascript">
	$(function() {
	  $('body').on('keydown', '#comment', function(e) {
	    console.log(this.value);
	    if (e.which === 32 &&  e.target.selectionStart === 0) {
	      return false;
	    }  
	  });
	});
</script>

<style type="text/css">
	.uiloginbutton {
	    float: right;
	    color: #FFFFFF;
	    background: #e76f39;
	    border: 1px solid #FFFFFF;
	    cursor: pointer;
	    display: inline-block;
	    font-size: 17px;
	    font-weight: bold;
	    line-height: 15px;
	    padding: 4px;
	    text-align: center;
	    text-decoration: none;
	}
</style>
<div class="header" style="background-color: #e76f39; border-bottom: 1px solid #e76f39;">
	<div class="headerMainmenu">
		<div>
			<ul>
			<?php
			if ($user != '') {
				echo '<li class="logo"><a href="newsfeed.php" title="Go to Home" >ToSpecial</a></li>
				<li class="search" style="float: left;">
					<form action="search.php" method="get">
						<input type="text" id="search" name="keywords" placeholder="Search Here..."  />
						<select name="topic" class="search_topic">
							<option>User</option>
							<option>Post</option>
						</select>
						<button type="submit" name="search" ><img src="./img/search.png" style="margin: 0 0 -12px 12px; float: right; padding: 0;" height="33" width="33"></button>
					</form>
				</li>
				<div class="leftHeaderMenu">
					<li><form action="" method="POST">
					<button type="submit" name="logout" style=" margin-top: 11px; border-radius: 10px; border:none; background-color:#FFFFFF;
"><a href="logout.php" title="Log me out" style="font-weight: bold; margin: 3px 8px; font-size: 14px; color:#e76f39;">Logout</a>
</button></form></li>';
					
						if ($noti_num == "") {
							echo '<li>
							<form method="POST" action="">
							<button type="submit"  name="gotonoti" style="background: none; cursor: pointer; border: none; ">
							<img src="./img/noti1.png" style="margin: 13px 30px 13px 2px; padding:2px;background-color: #ff9900;" height="22" width="22">
							</button>
							</form>
							</li>';
						}else {
							echo '<li>
							<form method="POST" action="">
							<button type="submit"  name="gotonoti" style="background: none; cursor: pointer; border: none;">
							<a href="notification.php" title="View Notification" style="color: red;">
							<img src="./img/noti2.png" style="margin: -4px;padding:2px;background-color: #ff9900;" height="22" width="22">'.$noti_num.'</a>
							</button>
							</form>
							</li>';
						}
						if ($unread_numrows == "") {
							echo '<li>
							<form method="POST" action="">
							<button type="submit"  name="gotoinbox" style="background: none; cursor: pointer; border: none;">
							<img src="./img/msg2.png" style="margin: 6px; background-color: #ff9900;" height="35" width="37">
							</button>
							</form>
							</li>';
						}else {
							echo '
							<li>
							<form method="POST" action="">
							<button type="submit"  name="gotoinbox" style="background: none; cursor: pointer; border: none;">
							<a href="messages.php"  title="View Messages" style="color: red;">

							<img src="./img/msg3.png" style="margin: -13px; background-color: #ff9900;" height="39" width="39">'.$unread_msg_numrows.'</a>
							</button>
							</form>
							</li>';
						}
						
						if ($follow_numrows == "") {
							echo '<li>
							<form method="POST" action="">
							<button type="submit"  name="gotofollow" style="background: none; cursor: pointer; border: none;">
							<img src="./img/follow1.png" style="margin: 11px 2px 11px 11px;padding:3px;background-color: #ff9900;" height="22" width="22">
							</button>
							</form>
							</li>';
						}else {
							echo '
							<li>
							<form method="POST" action="">
							<button type="submit"  name="gotofollow" style="background: none; cursor: pointer; border: none;">
							<a href="followRequest.php"  title="View Follow" style="color: red; margin: 14px;">
							<img src="./img/follow2.png" style="margin: -4px;padding:2px;background-color: #ff9900;" height="22" width="22">'.$unread_follow_numrows.'</a>
							</button>
							</form>
							</li>';
						}
					 
					
					echo '<li><a href="profile.php?u='.$user.'" title="Go to profile"><img src="'.$profile_pic.'" class="h_propic"  height="30" width="30"></a></li>
					<li><a href="newsfeed.php" style=" margin: 17px 0px 17px 17px;" title="Go to newsfeed"><img src="./img/home1.png" style="margin:-5px; padding: 0 5px;" height="22" width="22"></a></li>
				</div>';
			}else {
				echo '<li class="logo"><a href="newsfeed.php" title="Go to Home" >tospecial</a></li>
				<div style="float: right; margin-top: 10px;">
					<a href="signin.php" class="uiloginbutton" title="Log In" >Log In / Sign Up</a>
				</div>';
			}
			?>	
			</ul>
		</div>
	</div>
</div>
