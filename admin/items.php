<?php

/*
=================
=====Items Page
=================
*/
ob_start(); // Output Buffering start
session_start();

$pagename = 'Items';

if(isset($_SESSION['Username'])){

		include 'init.php';

		$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';


		if($do == 'Manage'){

		$stmt = $con->prepare("SELECT
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
								ORDER BY
										item_ID DESC  
								");
		$stmt->execute();
		$items = $stmt->fetchall();

		?>
			
			<div class="container">
				<h1 class="text-center">Manage Items</h1>
				<div class="table-responsive">
						<table class="main-table table table-bordered text-center">
							<tr>
								<td>#ID</td>
								<td>Name</td>
								<td>Description</td>
								<td>Price</td>
								<td>Adding Date</td>
								<td>Category</td>
								<td>Member</td>
								<td>Control</td>
								
									
								

							</tr>
							<?php 
							foreach ($items as $item) {
								echo "<tr>";
									echo "<td>" . $item['item_ID'] . "</td>";
									echo "<td>" . $item['Name'] . "</td>";
									echo "<td>" . $item['Description'] . "</td>";
									echo "<td>" . $item['Price'] . "</td>";
									echo "<td>" . $item['Add_Date'] . "</td>";
									echo "<td>" . $item['Namee'] . "</td>";
									echo "<td>" . $item['username'] . "</td>";

									echo "<td><a href='items.php?do=edit&itemid=" . $item['item_ID'] . "' class='btn btn-success '><i class='fa fa-edit'></i> Edit</a> <a href='items.php?do=delete&itemid=" . $item['item_ID'] . "' class='btn btn-danger'><i class='far fa-trash-alt'></i>  Delete</a>";
										if($item['Approve'] == 0){

								 		echo "<a href='items.php?do=approve&itemid=" . $item['item_ID'] . "' class='btn btn-info activate'><i class='fa fa-check'></i> Approve</a";}
								 	echo "</td>";
								echo "</tr>";

							}
								

							?>
							
							</td>
							

						</table>
				</div>
				<a href='?do=add' class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> New Items</a>



			</div>



	<?php 
		
		}elseif($do == 'add'){
			?>

			<h1 class="text-center">Add New Item</h1>
				<div class="container">
					<form class="form-horizontal" action="?do=insert" method="post">
						<div class="form-group form-group-lg">
								<label class="col-sm-2  ">Name </label>
								<div class="col-sm-6 "> 
									<input 
									type="text" 
									name="name" 
									class="form-control" 	
									placeholder=" Item Name" 
									required="required" />
								</div>
						</div>
						<div class="form-group form-group-lg">
								<label class="col-sm-2  ">Description </label>
								<div class="col-sm-6 ">
									<input 
									type="text" 
									name="description" 
									class="form-control" 	
									placeholder=" Item Description"
									required="required" / >
								</div>
								
						</div>
						<div class="form-group form-group-lg">
								<label class="col-sm-2  ">Price </label>
								<div class="col-sm-6 ">
									<input 
									type="text" 
									name="price" 
									class="form-control" 	
									placeholder=" Item Price" 
									required="required"/ >
								</div>
								
						</div>
						<div class="form-group form-group-lg">
								<label class="col-sm-2  ">Country </label>
								<div class="col-sm-6 ">
									<input 
									type="text" 
									name="country" 
									class="form-control" 	
									placeholder="Country Of The Item  "
									required="required" / >
								</div>
								
						</div>
						<div class="form-group form-group-lg">
								<label class="col-sm-2  ">Status </label>
								<div class="col-sm-6">
									<select class="form-control " name="status">
										<option value="0">...</option>
										<option value="1">New</option>
										<option value="2">Like New</option>
										<option value="3">Used</option>
										<option value="4">Old</option>
										<option value="5">Very Old</option>
									</select>
								</div>
								
						</div>	
						<div class="form-group form-group-lg">
								<label class="col-sm-2  ">Member </label>
								<div class="col-sm-6">
									<select class="form-control " name="member">
										<option value="0">...</option>
										<?php 
											$stmt = $con->prepare("SELECT * FROM users");
											$stmt->execute();
											$membs= $stmt->fetchAll();
											foreach ($membs as $memb) {
												echo "<option value='" . $memb['userID'] . "'>" . $memb['username'] . "</option>";


											}
										?>
									</select>
								</div>
								
						</div>	
						<div class="form-group form-group-lg">
								<label class="col-sm-2  ">Category </label>
								<div class="col-sm-6">
									<select class="form-control " name="category">
										<option value="0">...</option>
										<?php 
											$stm = $con->prepare("SELECT * FROM categories WHERE parent = 0");
											$stm->execute();
											$cats= $stm->fetchAll();
											foreach ($cats as $cat) {
												echo "<option value='" . $cat['ID'] . "'>" . $cat['Namee'] . "</option>";
												$childs = getcat($cat['ID']);
												foreach ($childs as $child) {
												echo "<option value='" . $child['ID'] . "'> --- " . $child['Namee'] . '  Child of '  .  $cat['Namee'] .    "</option>";	
												}


											}
										?>
									</select>
								</div>
								
						</div>
						<div class="form-group form-group-lg">
								<label class="col-sm-2  ">Tags </label>
								<div class="col-sm-6 ">
									<input 
									type="text" 
									name="tags" 
									class="form-control" 	
									placeholder="Seperate Tags with (,)  "
									required="required" / >
								</div>
								
						</div>	
						<div class="form-group form-group-lg">
							<div class="col-sm-offset-2 col-sm-8 "> <input type="submit" class="btn btn-primary btn-md"  value="Add Item">
							</div>
						</div>
					</form>
				</div>

	<?php 

		}elseif($do == 'insert'){
			echo "<div class='container'>";

			if($_SERVER['REQUEST_METHOD'] == 'POST'){
			echo '<h1 class="text-center">Insert Item </h1>';


				$name = $_POST['name'];
				$desc = $_POST['description'];
				$price = $_POST['price'];
				$country = $_POST['country'];
				$status = $_POST['status'];
				$memb = $_POST['member'];
				$catg = $_POST['category'];
				$tags = $_POST['tags'];


				$formErrors = array();

				if(empty($name)){
					$formErrors[] = 'Name cant be empty';
				}
				if(empty($desc)){
					$formErrors[] = 'Description cant be empty';
				}
				
				if(empty($price)){
					$formErrors[] = 'Price cant be empty ';
				}
				if(empty($country)){
					$formErrors[] = 'Country Made cant be empty';
				}
				if($status == 0 ){
					$formErrors[] = 'You Must Choose The Status';
				}
				if($memb == 0 ){
					$formErrors[] = 'You Must Choose The Member';
				}
				if($catg == 0 ){
					$formErrors[] = 'You Must Choose The Category';
				}

				foreach($formErrors as $error ){
					echo '<div class="alert alert-danger" >' . $error . '</div>';
				}


				checkitem('Name', 'items', $name); 

				if(empty($formErrors)){

					$stmt = $con->prepare("INSERT INTO items (Name, Description, Price, Country_Made, Status, Add_Date, Member_ID, Cat_ID, tags) 
								VALUES (:zname, :zdesc, :zprice, :zcountry, :zstatus, Now(), :zmember, :zcat, ztags)" );		
					$stmt->execute(array(
											'zname'		=>	$name,
											'zdesc'		=>	$desc,
											'zprice'	=>	$price,
											'zcountry'	=>	$country,
											'zstatus'	=>	$status,
											'zmember'	=>	$memb,
											'zcat'		=>	$catg,
											'ztags'		=>	$tags
												));
						
						$msg = '<div class="alert alert-success" >' . $stmt->rowCount() . ' Record Updated </div>';
						redirecting($msg, 'back');
							
				}

					
				}else{
					$msg = "<div class='alert alert-danger'> Sorry You Can\'t browse this page directly </div>";
					redirecting($msg);
			}
		echo "</div>";

		}elseif($do == 'edit'){


		$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;


		$stmt = $con->prepare("	SELECT * FROM items WHERE item_ID = ? " );

		$stmt->execute(array($itemid));

		$row = $stmt->fetch();

		$count = $stmt->rowCount();

		if($count > 0 ){


		?>



			<h1 class="text-center">Edit Item</h1>
				<div class="container">
					<form class="form-horizontal" action="?do=update" method="post">
						<input type="hidden" name="itemid" value="<?php echo $itemid ?>">
						<div class="form-group form-group-lg">
								<label class="col-sm-2  ">Name </label>
								<div class="col-sm-6 "> 
									<input 
									type="text" 
									name="name" 
									class="form-control" 	
									placeholder=" Item Name" 
									required="required" 
									value="<?php echo $row['Name']?>" />
								</div>
						</div>
						<div class="form-group form-group-lg">
								<label class="col-sm-2  ">Description </label>
								<div class="col-sm-6 ">
									<input 
									type="text" 
									name="description" 
									class="form-control" 	
									placeholder=" Item Description"
									required="required"
									value="<?php echo $row['Description']?>" / >
								</div>
								
						</div>
						<div class="form-group form-group-lg">
								<label class="col-sm-2  ">Price </label>
								<div class="col-sm-6 ">
									<input 
									type="text" 
									name="price" 
									class="form-control" 	
									placeholder=" Item Price" 
									required="required"
									value="<?php echo $row['Price']?>"/ >
								</div>
								
						</div>
						<div class="form-group form-group-lg">
								<label class="col-sm-2  ">Country </label>
								<div class="col-sm-6 ">
									<input 
									type="text" 
									name="country" 
									class="form-control" 	
									placeholder="Country Of The Item  "
									required="required" 
									value="<?php echo $row['Country_Made']?>"/ >
								</div>
								
						</div>
						<div class="form-group form-group-lg">
								<label class="col-sm-2  ">Status </label>
								<div class="col-sm-6">
									<select class="form-control " name="status">
										<option value="0">...</option>
										<option value="1" <?php if($row['Status'] == 1){echo "selected";} ?>>New</option>
										<option value="2" <?php if($row['Status'] == 2){echo "selected";} ?>>Like New</option>
										<option value="3" <?php if($row['Status'] == 3){echo "selected";} ?>>Used</option>
										<option value="4" <?php if($row['Status'] == 4){echo "selected";} ?>>Old</option>
										<option value="5" <?php if($row['Status'] == 5){echo "selected";} ?>>Very Old</option>
									</select>
								</div>
								
						</div>	
						<div class="form-group form-group-lg">
								<label class="col-sm-2  ">Member </label>
								<div class="col-sm-6">
									<select class="form-control " name="member">
										<option value="0">...</option>
										<?php 
											$stmt = $con->prepare("SELECT * FROM users");
											$stmt->execute();
											$membs= $stmt->fetchAll();
											foreach ($membs as $memb) {
												echo "<option value='" . $memb['userID'] . "'";
												if($row['Member_ID'] == $memb['userID']){echo "selected";} 

												echo ">" . $memb['username'] . "</option>";


											}
										?>
									</select>
								</div>
								
						</div>	
						<div class="form-group form-group-lg">
								<label class="col-sm-2  ">Category </label>
								<div class="col-sm-6">
									<select class="form-control " name="category">
										<option value="0">...</option>
										<?php 
											$stm = $con->prepare("SELECT * FROM categories");
											$stm->execute();
											$cats= $stm->fetchAll();
											foreach ($cats as $cat) {
												echo "<option value='" . $cat['ID'] . "'";
												if($row['Cat_ID'] == $cat['ID']){echo "selected";} 
												echo ">" . $cat['Namee'] . "</option>";


											}
										?>
									</select>
								</div>
								
						</div>	
						<div class="form-group form-group-lg">
								<label class="col-sm-2  ">Tags </label>
								<div class="col-sm-6 ">
									<input 
									type="text" 
									name="tags" 
									class="form-control" 	
									placeholder="Seperate Tags with (,)  "
									required="required" 
									value="<?php echo $row['tags']; ?>"

									/ >
								</div>
								
						</div>	
						<div class="form-group form-group-lg">
							<div class="col-sm-offset-2 col-sm-8 "> <input type="submit" class="btn btn-primary btn-md"  value="Edit Item">
							</div>
						</div>
					</form>
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
							WHERE 
								item_id = ?

							
											  ");
						$stmt->execute(array($itemid));
						$rows = $stmt->fetchall();

						if(! empty($rows)){

					?>
			
					<h1 class="text-center">Manage [ <?php echo $row['Name']?> ] Comments</h1>
					<div class="table-responsive">
							<table class="main-table table table-bordered text-center">
								<tr>
									<td>Comment</td>
									<td>User Name</td>
									<td>Comment Date</td>
									<td>Control</td>
								</tr>
								<?php 
								foreach ($rows as $row) {
									echo "<tr>";
										echo "<td>"		. $row['comment'] 		. "</td>";
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

					<?php } ?>
				</div>

	<?php
		}else{
			$msg = "There's No such ID";
			redirecting($msg);

		}

	



		}elseif($do == 'update'){



		
		echo "<div class='container'>";

		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			echo '<h1 class="text-center">Update Item </h1>';

			$itemid 	= $_POST['itemid'];
			$name 		= $_POST['name'];
			$desc 		= $_POST['description'];
			$price 		= $_POST['price'];
			$country 	= $_POST['country'];
			$status 	= $_POST['status'];
			$memb 		= $_POST['member'];
			$catg 		= $_POST['category'];
			$tags 		= $_POST['tags'];

			$formErrors = array();

			if(empty($name)){
				$formErrors[] = 'Name cant be empty';
			}
			if(empty($desc)){
				$formErrors[] = 'Description cant be empty';
			}
			
			if(empty($price)){
				$formErrors[] = 'Price cant be empty ';
			}
			if(empty($country)){
				$formErrors[] = 'Country Made cant be empty';
			}
			if($status == 0 ){
				$formErrors[] = 'You Must Choose The Status';
			}
			if($memb == 0 ){
				$formErrors[] = 'You Must Choose The Member';
			}
			if($catg == 0 ){
				$formErrors[] = 'You Must Choose The Category';
			}

			foreach($formErrors as $error ){
				echo '<div class="alert alert-danger" >' . $error . '</div>';
			}



			if(empty($formErrors)){

						$stmt = $con->prepare("	
												UPDATE 
														items 
												SET 
														Name 			= ?,
														Description 	= ?, 
														Price 			= ?, 
														Country_Made 	= ?, 
														Status 			= ?, 
														Member_ID 		= ?, 
														Cat_ID 			= ?,
														tags 			= ? 

												WHERE 
														item_ID = ? 
											");
			
						$stmt->execute(array($name, $desc, $price, $country, $status, $memb, $catg, $tags, $itemid));
			
						$msg = '<div class="alert alert-success" >' . $stmt->rowCount() . ' Record Updated </div>';

						redirecting($msg, 'back');

						}
		}else{

			$msg = "<div class='alert alert-danger'>Sorry You Can\'t browse this page directly</div>";
			redirecting($msg);

			
		}
			echo "</div>";
		
		}elseif($do == 'delete'){

				$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;


				$stmt = $con->prepare("	SELECT * FROM items WHERE item_ID = ?  LIMIT 1 " );


				$check = checkitem('item_ID', 'items', $itemid);


				if($check > 0 ){
					$stmt = $con->prepare('DELETE FROM items WHERE item_ID = :zitem');
					$stmt->bindParam('zitem', $itemid);
					$stmt->execute();

					echo '<h1 class="text-center">Delete Item </h1>';

					echo "<div class='container'>";


					$msg = '<div class="alert alert-success" >' . $stmt->rowCount() . ' Record Deleted </div>';
					redirecting($msg, 'back');

					echo "</div>";
				}else{
					echo "<div class='container'>";
					$msg = "<div class='alert alert-danger'> This ID is Not Exist</div> ";
					redirecting($msg);
					echo "</div>";
				}
		}elseif($do == 'approve'){


				$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;


				$check = checkitem('item_ID', 'items', $itemid);


				if($check > 0 ){
					$stmt = $con->prepare('UPDATE items SET Approve = 1 WHERE item_ID = ?');
					$stmt->execute(array($itemid));

					echo '<h1 class="text-center">Approve Item</h1>';

					echo "<div class='container'>";


					$msg = '<div class="alert alert-success" >' . $stmt->rowCount() . ' Record Updated </div>';
					redirecting($msg, 'back');

					echo "</div>";
				}else{
					echo "<div class='container'>";
					$msg = "<div class='alert alert-danger'> This ID is not exist</div> ";
					redirecting($msg);
					echo "</div>";
				

				}
			}
			
	 	include $inclu . 'footer.php'; 


	} else {

		header('Location: index.php');

	exit();}

ob_end_flush();
?>