<?php 
ob_start();
session_start();

$pagename = 'Members';

if(isset($_SESSION['Username'])){

	include 'init.php';

	$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';


	if($do == 'Manage'){ 
		$query = '';
		if(isset($_GET['page']) && $_GET['page'] == 'pending'){
			$query = "AND RegStatus = 0";
		}

		$stmt = $con->prepare("SELECT * FROM users WHERE GroupID != 1 $query");
		$stmt->execute();
		$rows = $stmt->fetchall();

		?>
			
			<div class="container">
				<h1 class="text-center">Manage Member</h1>
				<div class="table-responsive avatar">
						<table class="main-table table table-bordered text-center">
							<tr>
								<td>#ID</td>
								<td>Avatar</td>
								<td>Username</td>
								<td>Email</td>
								<td>Full Name</td>
								<td>Registered Date</td>
								<td>Control</td>
								
									
								

							</tr>
							<?php 
							foreach ($rows as $row) {
								echo "<tr>";
									echo "<td>" . $row['userID'] . "</td>";
									if(! empty($row['avatar'])){
									echo "<td><img src='uploads\avatars\\" . $row['avatar'] . "'></td>";}else{
										echo "<td><img src='uploads\avatars\av5.jpg'></td>";
									}

									echo "<td>" . $row['username'] . "</td>";
									echo "<td>" . $row['Email'] . "</td>";
									echo "<td>" . $row['FullName'] . "</td>";
									echo "<td>" . $row['Date'] . "</td>";

									echo "<td><a href='members.php?do=edit&id=" . $row['userID'] . "' class='btn btn-success '><i class='fa fa-edit'></i> Edit</a> <a href='members.php?do=delete&id=" . $row['userID'] . "' class='btn btn-danger'><i class='far fa-trash-alt'></i>  Delete</a>";
								 	if($row['RegStatus'] == 0){

								 		echo "<a href='members.php?do=activate&id=" . $row['userID'] . "' class='btn btn-info activate'><i class='fa fa-check'></i> Activate</a";}
								 	echo "</td>";
								echo "</tr>";

							}
								

							?>
							
							</td>
							

						</table>
				</div>
				<a href='?do=add' class="btn btn-primary"><i class="fa fa-plus"></i> New Members</a>



			</div>



	<?php }elseif($do == 'add'){?>

			<h1 class="text-center">Add Member</h1>
				<div class="container">
					<form class="form-horizontal" action="?do=insert" method="post" enctype="multipart/form-data" > 
						<div class="form-group form-group-lg">
								<label class="col-sm-2  ">Username </label>
								<div class="col-sm-6 "> <input type="text" name="user" class="form-control" placeholder=" Add Your Username" required="required" autocomplete="off"></div>
						</div>
						<div class="form-group form-group-lg">
								<label class="col-sm-2">Password </label>
								<div class="col-sm-6 "> 
								<input type="password" name="pasword" class="form-control" placeholder=" Add Your Password" required="required" autocomplete="new-password"></div>
						</div>
						<div class="form-group form-group-lg">
								<label class="col-sm-2 ">Email </label>
								<div class="col-sm-6 "> <input type="email" name="email" class="form-control" placeholder=" Add Your Email" required="required" ></div>
						</div>
						<div class="form-group form-group-lg">
							<label class="col-sm-2 ">Full Name </label>
							<div class="col-sm-6 "> <input type="text" name="full" class="form-control" required="required" placeholder=" Add Your Full Name" ></div>
						</div>
						<div class="form-group form-group-lg">
							<label class="col-sm-2 "> User Avatar </label>
							<div class="col-sm-6 "> <input type="file" name="avatar" class="form-control" ></div>
						</div>
						<div class="form-group form-group-lg">
							<div class="col-sm-offset-2 col-sm-8 "> <input type="submit" class="btn btn-primary btn-lg"  value="Add Member"></div>
						</div>
					</form>
				</div>

	<?php }elseif($do == 'insert'){
		echo "<div class='container'>";

		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			echo '<h1 class="text-center">Update Member </h1>';

				$avatar = $_FILES['avatar'];

				$avatarname = $avatar['name'] ;
				 $avatartype = $avatar['type'] ;
				 $avatardir = $avatar['tmp_name'];
				 $avatarsize = $avatar['size'];

				 $avatarAllowedExtention = array("jpeg", "png", "jpg", "gif");

				 //Get Avatar Extention
				 $avatarExtention = strtolower(end(explode('.', $avatarname)));

			


				$user = $_POST['user'];
				$email = $_POST['email'];
				$full = $_POST['full'];
				$full = $_POST['full'];
				$pass = $_POST['pasword'];

				$hashedpass = sha1($_POST['pasword']);

				$formErrors = array();

				if(empty($user)){
					$formErrors[] = 'Username cant be empty';
				}
				if(empty($pass)){
					$formErrors[] = 'password cant be empty';
				}
				
				if(empty($email)){
					$formErrors[] = 'Email cant be empty ';
				}
				if(empty($full)){
					$formErrors[] = 'Full Name cant be empty';
				}
				if(!empty($avatarname) && ! in_array($avatarExtention, $avatarAllowedExtention)){
					$formErrors[] = 'This Extention is Not Allowed';


				 }
				if(empty($avatarname)){
					$formErrors[] = 'Avatar is Required';
				 }
				if($avatarsize > 4194304){
					$formErrors[] = 'Avatar Can\'t Be Larger Than 4MB';
				 }



				foreach($formErrors as $error ){
					echo '<div class="alert alert-danger" >' . $error . '</div>';
				}


				checkitem('username', 'users', $user); 

				if(empty($formErrors)){
					$avatarr = rand(0, 10000000) . "_" . $avatarname;

					move_uploaded_file($avatardir, "uploads\avatars\\" . $avatarr);




				
					if(checkitem('username', 'users', $user)  == 1){
						$msg = "<div class='alert alert-danger'>this user is Exist</div>";
						redirecting($msg, 'back');

					}else{
					
					
					
							$stmt = $con->prepare("INSERT INTO users (username, password, Email, FullName, RegStatus, Date, avatar) 
																		VALUES (:zuser, :zpassword, :zmail, :zname, 1, now(), :zavatar) " );		
							$stmt->execute(array(
													'zuser'		=>	$user,
													'zmail'		=>	$email,
													'zname'		=>	$full,
													'zpassword'	=>	$hashedpass,
													'zavatar'	=>	$avatarr
												));
								
								$msg = '<div class="alert alert-success" >' . $stmt->rowCount() . ' Record Updated </div>';
								redirecting($msg, 'back');
							}
							
					}

					
				}else{
					$msg = "<div class='alert alert-danger'> Sorry You Can\'t browse this page directly </div>";
					redirecting($msg);
			}
		echo "</div>";


	}elseif($do == 'edit'){ 

		$userid = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;


		$stmt = $con->prepare("	SELECT * FROM users WHERE userID = ?  LIMIT 1 " );

		$stmt->execute(array($userid));

		$row = $stmt->fetch();

		$count = $stmt->rowCount();

		if($count > 0 ){


		?>


			<h1 class="text-center">Edit Member</h1>
			<div class="container">
				<form class="form-horizontal" action="?do=update" method="post">
					<input type="hidden" name="id" value="<?php echo $userid ?>">
					<div class="form-group form-group-lg">
							<label class="col-sm-2  req">Username </label>
							<div class="col-sm-6 "> <input type="text" name="user" class="form-control" required="required" value="<?php echo $row['username'];?>" autocomplete="off"></div>
					</div>
					<div class="form-group form-group-lg">
							<label class="col-sm-2">Password </label>
							<div class="col-sm-6 "> 
							<input type="hidden" name="oldpassword" value="<?php echo $row['password'];?>">
							<input type="password" name="newpassword" class="form-control" placeholder="Leave it Blank to keep the same Password" autocomplete="new-password"></div>
					</div>
					<div class="form-group form-group-lg">
							<label class="col-sm-2 req">Email </label>
							<div class="col-sm-6 "> <input type="email" name="email" class="form-control" required="required" value="<?php echo $row['Email'];?>"></div>
					</div>
					<div class="form-group form-group-lg">
						<label class="col-sm-2 req">Full Name </label>
						<div class="col-sm-6 "> <input type="text" name="full" class="form-control" required="required" value="<?php echo $row['FullName'];?>"></div>
					</div>
					<div class="form-group form-group-lg">
						<div class="col-sm-offset-2 col-sm-8 "> <input type="submit" class="btn btn-primary btn-lg"  value="Save"></div>
					</div>
				</form>
			</div>
			
	<?php
		}else{
			$msg = "There's No such ID";
			redirecting($msg);

		}

	}elseif($do == 'update'){

		
		echo "<div class='container'>";

		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			echo '<h1 class="text-center">Update Member </h1>';


			$user = $_POST['user'];
			$email = $_POST['email'];
			$full = $_POST['full'];
			$id = $_POST['id'];
			$pass = empty($_POST['newpassword']) ? $_POST['oldpassword'] : sha1($_POST['newpassword']);



			$formErrors = array();

			if(empty($user)){
				$formErrors[] = '<div class="alert alert-danger" > Username cant be empty</div>';
			}
			if(empty($email)){
				$formErrors[] = '<div class="alert alert-danger" > Email cant be empty </div>';
			}
			if(empty($full)){
				$formErrors[] = '<div class="alert alert-danger" > Full Name cant be empty</div>';
			}

			


			foreach($formErrors as $error ){
				echo $error . '<br>';
			}



			if(empty($formErrors)){
						$stmt2 = $con->prepare("SELECT 
													*
												FROM 
													users 
												WHERE 
													userID != ?
												AND 
													username= ?
												 ");
						$stmt2->execute(array($id, $user));
						$count = $stmt2->rowCount();
						if($count == 1){
							$msg = "<div class='alert alert-danger'>Sorry there\'s A Same Name Here</div>";

							redirecting($msg, 'back');


						}else{


								$stmt = $con->prepare("	UPDATE users SET username = ?, Email = ?, FullName = ?, password = ? WHERE userID = ? " );

								$stmt->execute(array($user, $email, $full, $pass, $id));
					
								$msg = '<div class="alert alert-success" >' . $stmt->rowCount() . ' Record Updated </div>';
								redirecting($msg, 'back');
							}

						}
		}else{

			$msg = "<div class='alert alert-danger'>Sorry You Can\'t browse this page directly</div>";
			redirecting($msg);

			
		}
			echo "</div>";
	}elseif($do == 'delete'){

			$userid = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;


			$stmt = $con->prepare("	SELECT * FROM users WHERE userID = ?  LIMIT 1 " );


			$check = checkitem('userid', 'users', $userid);


			if($check > 0 ){
				$stmt = $con->prepare('DELETE FROM users WHERE userID = :zuser');
				$stmt->bindParam('zuser', $userid);
				$stmt->execute();

				echo '<h1 class="text-center">Delete Member </h1>';

				echo "<div class='container'>";


				$msg = '<div class="alert alert-success" >' . $stmt->rowCount() . ' Record Deleted </div>';
				redirecting($msg, 'back');

				echo "</div>";
			}else{
				echo "<div class='container'>";
				$msg = "<div class='alert alert-danger'> This ID is Not Exist</div> ";
				redirecting($msg);
				echo "</div>";}
	}elseif($do == 'activate'){

			$userid = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;


			$check = checkitem('userid', 'users', $userid);


			if($check > 0 ){
				$stmt = $con->prepare('UPDATE users SET RegStatus = 1 WHERE userID = ?');
				$stmt->execute(array($userid));

				echo '<h1 class="text-center">Activate Member </h1>';

				echo "<div class='container'>";


				$msg = '<div class="alert alert-success" >' . $stmt->rowCount() . ' Record Updated </div>';
				redirecting($msg, 'back');

				echo "</div>";
			}else{
				echo "<div class='container'>";
				$msg = "<div class='alert alert-danger'> This ID is Not Exist</div> ";
				redirecting($msg);
				echo "</div>";
			}
	
	
 	include $inclu . 'footer.php'; 


	} else {

		header('Location: index.php');

	exit();}
}else {

		header('Location: index.php');

}

ob_end_flush();
?>