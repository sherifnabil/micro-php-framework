<?php 
ob_start();
$pagename = 'Show Items';
session_start();

 include "init.php"; 



	$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;


	$stmt = $con->prepare("	SELECT
									items.*, users.username, categories.Namee 
								FROM 
									items
								INNER JOIN 
									users 
								ON 
									users.userID = items.Member_ID
								INNER JOIN 
									categories 
								ON 
									categories.ID = items.Cat_ID

								WHERE
									item_ID = ?
								AND
									Approve = 1


						" );

	$stmt->execute(array($itemid));

	$count = $stmt->rowCount();
	$row = $stmt->fetch();

	if($count > 0){





?>
<h1 class="text-center"><?php echo $row['Name']; ?></h1>
 <div class="container">
 	<div class="row">
 		<div class="col-md-3">
 			<img class='img-responsive center-block' src='layout/css/images/img.png'/>
 		</div>
 		<div class="col-md-9 item-show">
 			<h2><?php echo $row ['Name'];  ?></h2>
 			<p><?php echo $row ['Description'];  ?></p>
 			<ul class="list-unstyled">
	 			<li>
	 				<i class="fa fa-calendar-alt fa-fw"></i>

	 				<span>Added Date</span>:<?php echo $row ['Add_Date'];  ?></li>
	 			<li>
					<i class="fa fa-money-bill-alt fa-fw"></i>
	 				<span>Price</span>: $<?php echo $row ['Price'];  ?></li>
	 			<li>
	 				<i class="fa fa-building fa-fw"></i>
	 				<span>Made in</span>: <?php echo $row ['Country_Made'];  ?></li>
	 			<li>
	 				<i class="fa fa-tags fa-fw"></i>
	 				 <span>Catgory</span>: <a href="categories.php?pageid=<?php echo $row['Cat_ID']; ?>"><?php echo $row ['Namee'];?></a></li>
	 			<li>
	 				<i class="fa fa-user fa-fw"></i>
	 				<span>Added By</span>: <a href="profile.php?"><?php echo $row ['username'];  ?></a>
	 			</li>
	 			<li>
	 				<i class="fa fa-user fa-fw"></i>
	 				<span>Tags</span>:
	 				<?php 
	 					$alltags = explode(',', $row['tags']);
	 					if(! empty($row['tags'])){
					 					foreach ($alltags as $tag) {
					 						$tag = str_replace(' ', '', $tag);
					 						$lowertag = strtolower($tag);
					 						echo "<a class='tag' href='tags.php?name={$lowertag}'>" . $tag  . "</a> ";
					 						
					 					}}
	 					

	 				?>
	 			</li>
 			</ul>
 		</div>
 	</div> 
 	<hr class="custom-hr">
 	<?php
 		if(isset($_SESSION['user'])){
 	?>
	<div class="row">
 		<div class="col-md-offset-3">
 			<form method="POST" action="<?php $_SERVER['PHP_SELF'] . "?temid=" . $row['item_ID']; ?>">
	 			<div class="add-comment">
	 				<h3>Add Your Comment</h3>
	 				<textarea class="com-area" name="comment" required="required"></textarea>
	 				<input type="submit" value="Add Comment" class="btn btn-primary">
	 			</div>
 			</form>
 			<?php
 			if($_SERVER['REQUEST_METHOD'] == 'POST')

 				$comment = filter_var($_POST['comment'], FILTER_SANITIZE_STRING);
 				$itemid = $row ['item_ID'];
 				$userid = $_SESSION['uid'];

 				if(! empty($comment)){
 					$stmt = $con->prepare("
 										INSERT INTO 
 												comments(comment, item_id, status, comment_date, user_id) 
 										VALUES 	
 												(:zcomment, :zitemid, 0, Now(), :zuserid) ");
 					$stmt->execute(array(
 						'zcomment' => $comment,
 						'zitemid'  => $itemid,
 						'zuserid'  => $userid

		 					));
 					if($stmt){
 						echo "<div class='alert alert-success'> Comment Added</div> ";
 					}else{
 					echo "<div class='alert alert-danger'>Add Your Comment </div> ";

 				}

 				}



 			?>
 		</div>
 		 	
 	</div>
 	<?php }else{
 		echo "There's No Such id ";
 	}

	 ?> 
	<hr class="custom-hr">

 		<?php

			$stmt = $con->prepare("
								SELECT 	
									comments.*, users.username
								FROM
									comments
								INNER JOIN 
									users
								ON 
									comments.user_id = users.userID
								WHERE
									item_id = ?

								AND 
									Status = 1

								ORDER BY
									 c_id DESC
									 


								  ");
			$stmt->execute(array($row ['item_ID']));
			$comments = $stmt->fetchall();

			foreach ($comments as $comment) {

				 echo "<div class='comment-box'>";
					 echo "<div class='row'>";
					 echo "<div class='col-md-2 text-center' ><img class='img-responsive center-block img-thumbnail img-circle' src='layout/css/images/img.png'/>" . $comment['username']  . "</div>";
					 echo "<div class='col-md-10' > <p class='lead'>" . $comment['comment']  .  "</p></div>";
					  echo "</div>";
				  echo "</div>"; 
				  echo "<hr class='custom-hr'>";

			}



 		?>


 </div>




<?php
	}else{
		echo "<div class='container'>";
			echo "<div class='alert alert-danger'> There's No Such Id or Item is waiting Approval</div>";
		echo "</div>";
	}
include $inclu . "footer.php";
ob_end_flush();?>