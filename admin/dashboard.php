<?php 

session_start();
$pagename = 'Dashboard';

if(isset($_SESSION['Username'])){

	include 'init.php';
	 $usersnum = 7; //latest user
	 $users = latestitems('*', 'users', 'userID', $usersnum); //usersnumber of latest user
 	 $itemnum = 6; //latest user
	 $items = latestitems('*', 'items', 'item_ID', $itemnum); //usersnumber of latest user
	 $comnum = 6; //latest comments
	 $comments = latestitems('*', 'comments', 'c_id', $comnum); //comments of latest user


?>
	
	<div class="container text-center main-stat">
		<h1>Dashboard</h1>
		<div class="rpw">
			<div class="col-md-3">
				<div class="stat st-members">
					<i class="fa fa-users "></i>
					<div class="info">
						<h4 class="pull-right">Total Members</h4>
						<span><a href="members.php"><?php echo countitem('userID', 'users') ?></a></span>

					</div>

				</div>
			</div>
			<div class="col-md-3">
				<div class="stat st-pending">
					<i class="fa fa-user-plus "></i>
					<div class="info">
						<h4 class="pull-right">Pending Members </h4>
						<span> <a href="members.php?do=Manage&page=pending"> 	<?php echo checkitem('Regstatus', 'users', 0) ?></a></span>

					</div>

				</div>
			</div>
			<div class="col-md-3">
				<div class="stat  st-items">
					<i class="fa fa-tag "></i>
					<div class="info">
						<h4 class="pull-right"> Total Items </h4>
						<span><a href="items.php"><?php echo countitem('item_ID', 'items') ?></a></span>

					</div>
				</div>
			</div>

			<div class="col-md-3">
				<div class="stat st-comments">
					<i class="fa fa-comments "></i>
					<div class="info">
						<h4 class="pull-right">Total Comments</h4>
						<span><a href="comments.php"><?php echo countitem('c_id', 'comments') ?></a></span>

					</div> 
				</div>
			</div>			
			
		</div>

	</div>
	<div class="container pan">
		<div class="row">
			<div class="col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">

						<i class="fa fa-users"></i> Latest Registered Users
					</div>
					<div class="panel-body">
						<ul class="list-unstyled lat">
							<?php
							  foreach ($users as $user) {
							  	echo '<li>';

								  	echo $user['username'];
							 	 	echo '<a href ="members.php?do=edit&id=' . $user['userID'] . '">';

										echo '<span class="pull-right btn btn-success"><i class="fa fa-edit "></i> Edit</span></a>';
							 	if($user['RegStatus'] == 0){
									echo '<a href="members.php?do=activate&id=' . $user['userID'] . '" class="btn btn-info pull-right"> <i class="fa fa-edit"></i> Activate</a>';
									}

								echo "</li>";
							 	 }
							 	
							 ?>
						 </ul>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">
					<i class="fa fa-tag"></i> Latest Items
					</div>
					<div class="panel-body">
						<ul class="list-unstyled lat">
							<?php
							  foreach ($items as $item) {
							  	echo '<li>';

								  	echo $item['Name'];
							 	 	echo '<a class="edit" href ="items.php?do=edit&itemid=' . $item['item_ID'] . '">';

										echo '<span class="pull-right btn btn-success"><i class="fa fa-edit "></i> Edit</span></a>';
							 	if($item['Approve'] == 0){
									echo '<a class="appro pull-right btn btn-info" href="items.php?do=approve&itemid=' . $item['item_ID'] . '"> <i class="fa fa-edit"></i> Approve</a>';
									}

								echo "</li>";
							 	 }
							 	
							 ?>
						 </ul>
					</div>
				</div>
			</div>
		</div>



		<div class="row">
			<div class="col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">

					<i class="fa fa-comments"></i> Latest  Comments
					</div>
					<div class="panel-body">
						<ul class="list-unstyled latee">
							<?php

								$stmt = $con->prepare("
									SELECT 	
										comments.*, users.username
									FROM
										comments
									INNER JOIN 
										users
									ON 
										comments.user_id	 = users.userID 
									ORDER BY
										c_id DESC
								");

								$stmt->execute();
								$comments = $stmt->fetchall();


							
							  foreach ($comments as $comment) {
							  	echo '<li>';
									echo "<div class='comment-box'>";
										echo "<div class='texts'>";
										echo "<span class='user du' >";
									  		echo "<a href='members.php?do=edit&id=" . $comment['user_id'] . "'>"  . $comment['username'] . "</a>";

										echo "</span>";
										echo "<p class='comm'>";
										  		echo $comment['comment'];
										echo "</p>";
										echo "</div>";
										echo "<div class='buttns'>";

									 	 	echo '<a href ="comments.php?do=edit&comid=' . $comment['c_id'] . '">';

											echo '<span class="pull-right btn btn-sm btn-success"><i class="fa fa-edit "></i> Edit</span></a>';
									 	if($comment['status'] == 0){
											echo '<a href="comments.php?do=approve&comid=' . $comment['c_id'] . '" ><span class="btn btn-info btn-sm pull-right"> <i class="fa fa-edit"></i> Approve</span></a>';
											}
										echo "</div>";
										echo "</div>";

								echo "</li>";
							 	 }
							 	
							 ?>
						 </ul>
					</div>
				</div>
			</div>



		</div>

	</div>




<?php
 	include $inclu . "footer.php"; 


} else {
	header('Location: index.php');
	exit();
}
 