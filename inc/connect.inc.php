<?php 
$conn = mysqli_connect("localhost","root","", "tospecial_db") or die("Couldn't connect to SQL server");

function formatDate($day){
	return date('F j, Y, g:i a', strtotime($day));
}
?>
