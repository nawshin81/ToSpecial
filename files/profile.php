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

//update online time
$sql = mysqli_query($conn,"UPDATE users SET chatOnlineTime=now() WHERE username='$user'");

?>
<?php 
	$username ="";
	$firstname ="";
	if (isset($_GET['u'])) {
		$username = ($_GET['u']);//mysql_real_escape_string
		if (ctype_alnum($username)) {
			//check user exists
			$check = mysqli_query($conn,"SELECT username, first_name, user_type FROM users WHERE username='$username'");
			if (mysqli_num_rows($check)==1) {
				$get = mysqli_fetch_assoc($check);
				$username = $get['username'];
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
	<title><?php echo $title_fname; ?></title>
	<link rel="icon" href="./img/tlogo.png" type="image/x-icon">
	<link rel="stylesheet" type="text/css" href="./css/header.css">
	<script type="text/javascript" src="js/main.js"></script>
	<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>

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
		  $('body').on('keydown', '#post', function(e) {
		    console.log(this.value);
		    if (e.which === 32 &&  e.target.selectionStart === 0) {
		      return false;
		    }  
		  });
		});
	</script>

</head>
<body>
<div id="top"></div>
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
						<li><a href="friends.php?u='.$username.'">Friend</a></li>';

                        if ($type==1) {
					    echo '<li><a href="family_about.php?u='.$username.'">About</a></li>';}	
						else if ($type==2) {
					    echo '<li><a href="doctor_about.php?u='.$username.'">About</a></li>';}
					    else if ($type==3) {
					    echo '<li><a href="educator_about.php?u='.$username.'">About</a></li>';}
					    else if ($type==4){
					    echo '<li><a href="everyone_about.php?u='.$username.'">About</a></li>';}


                    echo '<li style="float: right;">
						<li><a href="profile.php?u='.$username.'" >Post</a></li>
						</ul>
						</nav>
					</div>
					
				</li>
			</ul>
			
			</div>
		</div>
			</div>';
		echo '	
		<div id="top">
			<div style="width: 560px; margin: 0 auto;">';
			$get_msg_num = mysqli_query($conn,"SELECT * FROM pvt_messages WHERE user_from='$username' AND user_to='$user' LIMIT 2");
			$msg_count = mysqli_num_rows($get_msg_num);
			if (($username == $user)){
				echo '
					<div class="postForm">
					<form action="profile.php?u='.$username.'" method="POST" enctype="multipart/form-data">
						<textarea type="text" id="post" name="post" onkeyup="clean("post")" onkeydown="clean("post")" rows="4" cols="58"  class="postForm_text" placeholder="What you are thinking..."></textarea>
						<input type="submit" name="send" value="Post" class="postSubmit" >
					</form>
					</div>
				';
			}else {
				//nothing
			}
				echo '<div class="profilePosts">';

				//post update
					$profilehmlastid = "";
					$post = htmlspecialchars(@$_POST['post'], ENT_QUOTES);
					$post = trim($post);
					$post = ($post);//mysql_real_escape_string

					if ($post != "") {
						$date_added = date("Y-m-d");
						$added_by = $user;
						$user_posted_to = $username;
						if ($username == $user) {
							$newsfeedshow = '1';
						}else {
							$newsfeedshow = '0';
						}
						$sqlCommand = "INSERT INTO posts(body,date_added,added_by,user_posted_to,newsfeedshow ) VALUES('$post', '$date_added','$added_by', '$user_posted_to', '$newsfeedshow')";
						$query = mysqli_query($conn,$sqlCommand) or die (mysql_error());
					}

				//for getting post

				$getposts = mysqli_query($conn,"SELECT * FROM posts WHERE user_posted_to ='$username' AND report='0' ORDER BY id DESC LIMIT 9") or die(mysql_error());
				$count_post = mysqli_num_rows($getposts);
				echo '<ul id="profilehmpost">';
				while ($row = mysqli_fetch_assoc($getposts)) {
						include ( "./inc/newsfeed.inc.php");
						$profilehmlastid = $row['id'];
						$profilehm_uname = $row['user_posted_to'];
					}
					if ($count_post >= 9) {
						echo '<li class="profilehmmore" id="'.$profilehmlastid.'" >Show More</li>';
						echo '</ul>';
						echo '
						</div>
						<a href="#top" class="backtotop">top</a>
						</br>
					</div>
				</div>
			</div>
			</div>';
					}else {
					echo '</ul>';
					echo '
					</div>
				</br>
			</div>
		</div>
	</div>
	</div>';
					}

	}
	else {
		header("location: profile.php?u=$user");
	}
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$('.profilehmmore').live('click',function() {
			var profilehmlastid = $(this).attr('id');
			$.ajax({
				type: 'GET',
				url: 'profilehmmore.php',
				data: 'profilehmlastid='+profilehmlastid,
				beforeSend: function() {
					$('.profilehmmore').html('Loading ...');
				},
				success: function(data) {
					$('.profilehmmore').remove();
					$('#profilehmpost').append(data);
				}
			});
		});
	});
</script>
</body>
</html>