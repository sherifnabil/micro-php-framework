<?php 
ob_start();
session_start();
$pagename = 'Login';

if(isset($_SESSION['user'])){
	header('Location: index.php'); //redirect to index page
}

 include "init.php"; 
//cheeck if the User is comming from post request
 if($_SERVER['REQUEST_METHOD'] == 'POST'){
 	if(isset($_POST['login'])){


	 	$user = $_POST['username'];
		$pass = $_POST['password'];
		$hashedpass = sha1($pass);



		//check if user is in database

		$stmtx = $con->prepare("SELECT 
									 userID, username, password 
								FROM 
									users 
								WHERE 	

									username = ? 
								AND 
									password = ? 
								" );
		$stmtx->execute(array($user, $hashedpass));
		$get = $stmtx->fetch();

		$count = $stmtx->rowCount();


		if($count > 0){
			$_SESSION['user'] = $user; //register session name
			$_SESSION['uid'] = $get['userID']; //Register User ID
			header('Location: index.php'); //redirect to index page

			exit();

			 
		}
	 	}else{	
	 		$email = $_POST['email'];
		 	$user = $_POST['username'];
			$hashedpass = sha1($pass);
			$pass = $_POST['password'];
			$pass2 = $_POST['password2'];


	 		$errors = array();


	 		if(isset($_POST['username'])) {

	 			$filterUser = filter_var($_POST['username'], FILTER_SANITIZE_STRING);

	 			if(strlen($filterUser) <  4){

	 				$errors[] = 'Sorry User can not be Less Than 4 Charaters' ;

	 			}
	 		}
	 		if(isset($_POST['password'])) {

	 			if(empty($_POST['password'])){

	 				$errors[] = 'Password can\'t be empty';


	 			}

	 			$pass1 = sha1($_POST['password']);

	 			$pass2 = sha1($_POST['password2']);

	 			if($_POST['password'] !== $_POST['password2']){

	 				$errors[] = 'The Passwords aren\'t Matched';

	 			}


	 		}
	 		if(isset($_POST['email'])) {

	 			$filterEmail = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

	 			if(filter_var($filterEmail, FILTER_VALIDATE_EMAIL) != true){

	 				$errors[] = 'Your Email is not valid';	


	 			}

	 			

	 		}




			if(empty($errors)){
			$chek = checkitem('username', 'users', $user); 

				if($chek == 1){
					$err = "This user is Exist";

				}else{
				
				
						$stmt = $con->prepare("
										INSERT INTO
											 users(
											 username,
											 password,
											 Email,
											 RegStatus,
										     Date) 
										VALUES 
											(:zuser,
											 :zpassword,
											 :zmail,
											 0,
											 now()) " );		
						$stmt->execute(array(
											'zuser'		=>	$user,
											'zpassword'	=>	$hashedpass,
											'zmail'		=>	$email
											));
							
							$errors[] = '<div class="err">Congrates You have Registered to our Website</div>';
				}
					
			}					
			


	 		
	 	}

	 
 }
 	
 				 	
?>
 <div class="container">
 	<div class="logs">
	 	<h1 class="text-center"><span data-class="login" class="active">Login</span> | <span data-class="signup">Sign Up</span> </h1>
	 	<!--Start Login Form-->
	 	<form class="login"  action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
	 		<input 
	 			type="text" 
	 			class="form-control" 
	 			autocomplete="off" 
	 			name="username"
	 			placeholder="Type Username"
	 			required="required"
	 			pattern="{}" 
	 		 />
	 		<input 
	 			type="password" 
	 			class="form-control" 
	 			autocomplete="new-password" 
	 			name="password"
	 			placeholder="Type Password" 

	 		 />
	 		<input 
	 			type="submit" 
	 			name="login"
	 			value="Login" 
	 			class="btn btn-primary btn-block" 
	 		 /> 	
	 	</form>
	 	<!--End Login Form-->
	 	<!--Start Signup Form-->
	 	<form class="signup" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
	 		<input 
	 			pattern=".{3,}"
	 			title="Username must be 4 Chars at least" 
	 			type="text" 
	 			class="form-control" 
	 			autocomplete="off" 
	 			name="username"
	 			placeholder="Type Username" 
	 			required="required"

	 		 />
	 		<input 
	 			type="password" 
	 			minlength="4" 
	 			class="form-control" 
	 			autocomplete="new-password" 
	 			name="password"
	 			placeholder="Type a Complex Password"
	 			required="required" 

	 		 />
			<input 
	 			type="password" 
	 			class="form-control" 
	 			autocomplete="new-password" 
	 			name="password2"
	 			placeholder="Retype a Complex Password" 

	 		 />	 		
	 		 <input 
	 			type="email" 
	 			class="form-control" 
	 			autocomplete="off" 
	 			name="email"
	 			placeholder="Type a Valid Email" 

	 		 />
	 		<input 
	 			type="submit" 
	 			name="signup"
	 			value="Sign Up" 
	 			class="btn btn-success btn-block" 

	 		 /> 	
	 	</form>
	 	<!--End Signup Form-->
	 	
 		
 	</div>
 		<?php 
 		
	 		if(! empty($errors)){
	 		 		foreach($errors as $error ){

 				 		echo"<div class='error'>" . $error . "</div>";
 				 	} 
 				 	

 				}
 			if(isset($err)) {
 				 		echo"<div class='error'>" . $err . "</div>";
 				 	}
 				

 		?>
 	
 </div>
 
<?php 

include $inclu . "footer.php";
ob_end_flush();?>