<?php 
session_start();
$noNavbar = '';
$pagename = 'Login';

if(isset($_SESSION['Username'])){
	header('Location: dashboard.php'); //redirect to dashboard page
}

 include "init.php"; 
//cheeck if the User is comming from post request
 if($_SERVER['REQUEST_METHOD'] == 'POST'){

 	$username = $_POST['user'];
	$password = $_POST['pass'];
	$hashedpass = sha1($password);

	//check if user is in database

	$stmt = $con->prepare("	SELECT 
								userID, username, password 
							FROM 
								users 
							WHERE 	

								username = ? 
							AND 
								password = ? 
							AND 
								GroupID = 1" );
	$stmt->execute(array($username, $hashedpass));
	$row = $stmt->fetch();
	$count = $stmt->rowCount();

	if($count > 0){
		$_SESSION['Username'] = $username; //register session name
		$_SESSION['ID'] = $row['userID']; //register session name
		header('Location: dashboard.php'); //redirect to dashboard page
		exit();
	}
 }
  ?>

	 <form class="login"  action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	 	<h4 class="text-center">Admin Login</h4>
	 	<input type="text" class="form-control" name="user" placeholder="User Name" autocomplete="off" />
	 	<input type="password" class="form-control" name="pass" placeholder="Password" autocomplete="new-passwoed" />
	 	<input class="btn btn-primary btn-block	" type="submit" name="submit" value="Login">
	 </form>

 

<?php include $inclu . "footer.php"; ?>
