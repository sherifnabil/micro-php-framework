<?php 
ob_start();
$pagename = 'Profile';
session_start();

 include "init.php"; 

 if(isset($_SESSION['user'])){
 	$theUser = $con->prepare("SELECT * FROM users WHERE username = ?");
 	$theUser->execute(array($sessionUser));
 	$info = $theUser->fetch();





?>
<h1 class="text-center">My Profile</h1>
<div class="information block">
	<div class="container">
		<div class="panel panel-primary">
			<div class="panel-heading">
				My Information
			</div>
			<div class="panel-body inform">
				<ul class="list-unstyled">
					<li>
						<i class="fa fa-unlock-alt fa-fw"></i>
						<span>Name</span> : <?php echo $info['username'] ?>
							
					</li>
					<li>
						<i class="fa fa-envelope fa-fw"></i>
						<span>Email</span> : <?php echo $info['Email'] ?></li>
					<li>
						<i class="fa fa-user fa-fw"></i>

						<span>Full Name</span> : <?php echo $info['FullName']?>
					</li>
					<li>
						<i class="fa fa-calendar-alt fa-fw"></i>
						<span>Registered Date</span> : <?php echo $info['Date'] ?>
					</li>
					<li>
						<i class="fa fa-tag fa-fw"></i>
						<span>Favourite Category</span> : 
					</li>
				</ul>
				<a href="#" class="btn btn-default my-bt"> Edit Information</a>
			</div>
		</div>
	</div>
</div>
<div class="myads block">
	<div class="container">
		<div class="panel panel-primary">
			<div class="panel-heading">
				My Ads
			</div>
			<div class="panel-body ad" id="new-item">
				<?php
				if(! empty(getItems('Member_ID', $info['userID']))) {
						echo "<div class='container'>";
				
							foreach (getItems('Member_ID', $info['userID'], 1) as $item)
				    		{
				    			echo "<div class='col-sm-4 col-md-3'>";
				    				echo "<div class='thumbnail item-box'>";
				    					if( $item['Approve'] == 0 ){ echo "<span class='approve-stat'>Waiting Approval</span>";}
				    					echo "<span class='price-tag'>$" . $item['Price'] . "</span>";
				    					echo "<img class='img-responsive' src='layout/css/images/img.png'/>";
				               			echo "<h3><a href='items.php?itemid=" . $item['item_ID'] . "'>" . $item['Name'] . "</a></h3>";
				               			echo "<p>" . $item['Description'] . "</p>";
				               			echo "<div class='date'>" . $item['Add_Date'] . "</div>";
				    				echo "</div>";
				    			echo "</div>";
				            } 

					}else{
						echo "Sorry There\'s no Ads to Show Create <a href='newad.php'> New Ad</a>";
					}
						echo "</div>";

				?>
									
			</div>
		</div>
	</div>
</div>
<div class="information block">
	<div class="container">
		<div class="panel panel-primary">
			<div class="panel-heading">
				Latest Comments
			</div>
			<div class="panel-body">
			<?php 
				$stmt = $con->prepare("	SELECT comment FROM comments WHERE user_id = ? " );

				$stmt->execute(array($info['userID']));

				$rows = $stmt->fetchAll();
				if(! empty($rows)){
				
								foreach ($rows as $row) {
									echo $row['comment'] . "<br>";
									}	
					}else{
						echo "There\'s No Comments To show";
					}
			?>			
				</div>
		</div>
	</div>
</div>




<?php }else{
	header('Location: login.php');
	exit();
}
include $inclu . "footer.php";
ob_end_flush();?>