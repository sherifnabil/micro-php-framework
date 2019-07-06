<?php 
ob_start();
session_start();

$pagename = 'Comments';

if(isset($_SESSION['Username'])){

	include 'init.php';

	$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';


	if($do == 'Manage'){ 
		

		$stmt = $con->prepare("
							SELECT 	
								comments.*, users.username, items.Name
							FROM
								comments
							INNER JOIN 
								users
							ON 
								comments.user_id	 = users.userID

							INNER JOIN
								items
							ON
								comments.item_id = items.item_ID
							ORDER BY
								 c_id DESC

							  ");
		$stmt->execute();
		$rows = $stmt->fetchall();

		?>
			
			<div class="container">
				<h1 class="text-center">Manage Comments</h1>
				<div class="table-responsive">
						<table class="main-table table table-bordered text-center">
							<tr>
								<td>ID</td>
								<td>Comment</td>
								<td>Item Name</td>
								<td>User Name</td>
								<td>Comment Date</td>
								<td>Control</td>
							</tr>
							<?php 
							foreach ($rows as $row) {
								echo "<tr>";
									echo "<td>" 	. $row['c_id'] 			. "</td>";
									echo "<td>"		. $row['comment'] 		. "</td>";
									echo "<td>" 	. $row['Name'] 		. "</td>";
									echo "<td>" 	. $row['username'] 		. "</td>";
									echo "<td>" 	. $row['comment_date'] 	. "</td>";

									echo "<td><a href='comments.php?do=edit&comid=" . $row['c_id'] . "' class='btn btn-success '><i class='fa fa-edit'></i> Edit</a> <a href='comments.php?do=delete&comid=" . $row['c_id'] . "' class='btn btn-danger'><i class='far fa-trash-alt'></i>  Delete</a>";
								 	if($row['status'] == 0){

								 		echo "<a href='comments.php?do=approve&comid=" . $row['c_id'] . "' class='btn btn-info activate'><i class='fa fa-check'></i> Approve</a";}
								 	echo "</td>";
								echo "</tr>";

							}
								

							?>
							
							</td>
							

						</table>
				</div>

			</div>

	<?php }elseif($do == 'edit'){ 

		$comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;


		$stmt = $con->prepare("	SELECT * FROM comments WHERE c_id = ? " );

		$stmt->execute(array($comid));

		$row = $stmt->fetch();

		$count = $stmt->rowCount();

		if($count > 0 ){


		?>


			<h1 class="text-center">Edit Comment</h1>
			<div class="container">
				<form class="form-horizontal" action="?do=update" method="post">
					<input type="hidden" name="comid" value="<?php echo $comid ?>">
					<div class="form-group form-group-lg">
							<label class="col-sm-2">Comment </label>
							<div class="col-sm-6 "> 
								<input type="text" name="comment" class="form-control" required="required" value="<?php echo $row['comment'];?>" autocomplete="off">
							</div>
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
			echo '<h1 class="text-center">Update Comment </h1>';


			$cid = $_POST['comid'];
			$com = $_POST['comment'];





	
						$stmt = $con->prepare("	UPDATE comments SET comment = ? WHERE c_id = ? ");
			
						$stmt->execute(array($com, $cid));
			
						$msg = '<div class="alert alert-success" >' . $stmt->rowCount() . ' Record Updated </div>';
						redirecting($msg, 'back');
				}else{

			$msg = "<div class='alert alert-danger'>Sorry You Can\'t browse this page directly</div>";
			redirecting($msg);

			
			}
		echo "</div>";

	}elseif($do == 'delete'){

			$comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;


			$stmt = $con->prepare("	SELECT * FROM comments WHERE c_id = ?  LIMIT 1 " );


			$check = checkitem('c_id', 'comments', $comid);


			if($check > 0 ){
				$stmt = $con->prepare('DELETE FROM comments WHERE c_id = :zcom');
				$stmt->bindParam('zcom', $comid);
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
	}elseif($do == 'approve'){

			$comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;


			$check = checkitem('c_id', 'comments', $comid);


			if($check > 0 ){
				$stmt = $con->prepare('UPDATE comments SET status = 1 WHERE c_id = ?');
				$stmt->execute(array($comid));

				echo '<h1 class="text-center">Approve Comment</h1>';

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