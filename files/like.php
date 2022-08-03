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

//inserting  like
if (isset($_REQUEST['did'])) {
	$dwt_id = $_REQUEST['did'];

	$insertDwtlike =  mysqli_query($conn,"INSERT INTO dwt_likes VALUES ('','$user','$dwt_id')");
	header("location: index.php");
}else {
	header('location: index.php');
}

//deleting  like
if (isset($_REQUEST['udid'])) {
	$dwt_uid = $_REQUEST['udid'];

	$del_dwtlike =  mysqli_query($conn,"DELETE FROM dwt_likes WHERE dwt_id='$dwt_uid'");
	header("location: index.php");
}else {
	header('location: index.php');
}
//inserting post like
if (isset($_REQUEST['pid'])) {
	$post_id = $_REQUEST['pid'];

	$insertPostlike =  mysqli_query($conn,"INSERT INTO post_likes VALUES ('','$user','$post_id')");
	header("location: newsfeed.php");
}else {
	header('location: newsfeed.php');
}

//deleting post like
if (isset($_REQUEST['upid'])) {
	$post_uid = $_REQUEST['upid'];

	$del_postlike =  mysqli_query($conn,"DELETE FROM post_likes WHERE post_id='$post_uid'");
	header("location: newsfeed.php");
}else {
	header('location: newsfeed.php');
}

?>