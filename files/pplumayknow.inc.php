<?php 



$get_user = mysqli_query($conn,"SELECT * FROM users WHERE username='$user'");
$user_row = mysqli_fetch_assoc($get_user);
if($get_user) {
	$get_ppl_info =mysqli_query($conn,"SELECT * FROM users WHERE username!='$user'  AND activated='1' AND blocked_user='0' ORDER BY  RAND() ");
	//declear variable
	$getuserNum= '0';
	if($get_ppl_info === FALSE) { 
	    die(mysqli_error()); // TODO: better error handling
	}

	while ($row_user = mysqli_fetch_assoc($get_ppl_info)) {
			
			$user_name= $row_user['username'];
			//if follow	
			$if_user_to_follow = mysqli_query($conn,"SELECT * FROM follow WHERE (user_from='$user' AND user_to='$user_name')");
			$count_user_to_follow = mysqli_num_rows($if_user_to_follow);
			if ($count_user_to_follow == 0) {
			  	$profile_pic_db= $row_user['profile_pic'];
				$user_name_f = $row_user['first_name'];

				$user_from_user = mysqli_query($conn,("SELECT * From users WHERE first_name='$user_name_f'"));

				$from_user = mysqli_fetch_assoc($user_from_user);

				$pro_type = $from_user['user_type'];

				if($pro_type == 1){
				$type = "Family.";
				}else if($pro_type ==2){
				$type = "Doctor.";
				}else if($pro_type ==3){
				$type = "Educator.";
				}else{
				$type = "";
				}
	
				//check for propic delete
				$pro_changed = mysqli_query($conn,"SELECT * FROM posts WHERE added_by='$user_name' AND (discription='changed his profile picture.' OR discription='changed her profile picture.') ORDER BY id DESC LIMIT 1");
				$get_pro_changed = mysqli_fetch_assoc($pro_changed);
				$pro_num = mysqli_num_rows($pro_changed);
				if ($pro_num == 0) {
					$profile_pic = "img/default_propic.png";
				}else {
					$pro_changed_db = $get_pro_changed['photos'];
				if ($pro_changed_db != $profile_pic_db) {
					$profile_pic = "/img/default_propic.png";
				}else {
					$profile_pic = "./userdata/profile_pics/".$profile_pic_db;
				}
				}
	
				echo "
					<form method='POST' action=''>
					<div style='display: flex; padding: 8px 0;'> ";
							echo "<div>
							<img src='$profile_pic' style= 'border-radius: 4px' border: 1px solid #ddd; title=\"$user_name_f\" height='70' width='65'  />
							</div>";
						
						echo "<div style='margin-left: 10px;'><b><a href='profile.php?u=$user_name' style='text-decoration: none; font-size: 14px; color: #505050;' title=\"Go to $user_name_f's Profile\" class='posted_by'>$type$user_name_f</a></b> <br><br>
						
						<b><a href='profile.php?u=$user_name' style='text-decoration: none; margin: 0px;' class='frndPokMsg' title='View Full Profile' >View Profile</a></b>
						</div>";
					echo "
					</div>
					</form>
					";	
	
				$getuserNum++;
	
				if ($getuserNum == 3){
					break;
				}
			}
			
			//follow request system
			if (@($_POST[''.$user_name.''])) {
				header("location: profile.php?u=".$user_name."");
			}
			
		}
}
?>