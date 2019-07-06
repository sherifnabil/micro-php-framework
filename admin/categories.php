<?php

ob_start(); // Output Buffering start
session_start();

$pagename = 'Categories';

if(isset($_SESSION['Username'])){

	include 'init.php';

	$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';


	if($do == 'Manage'){
		$ordered = 'ASC';
		$theorder = array('ASC', 'DESC');
		if(isset($_GET['ord']) && in_array($_GET['ord'], $theorder)){
			$ordered = $_GET['ord'];
		}

		$stmt = $con->prepare("SELECT * FROM categories WHERE parent = 0 ORDER BY Ordering $ordered");
		$stmt->execute();
		$cats = $stmt->fetchAll();


		?>
		<div class="container">
			<h1 class="text-center">Manage Categories</h1>
			<div class="panel panel-default category">
				<div class="panel-heading">
					<i class="fa fa-sort"></i> Manage Categories
					<div class="pull-right option"> Order: 
						[<a href="?ord=ASC" class="<?php if($ordered == 'ASC'){ echo 'active';} ?>"> ASC </a> | 
						<a href="?ord=DESC" class="<?php if($ordered == 'DESC'){ echo 'active';} ?>"> DESC </a>]  
						<i class="fa fa-eye"></i> View: 
						[<span>Classic</span> |  
						<span>Full</span>]
					</div>
				</div>
				<div class="panel-body">
					<?php 
						foreach ($cats as $cat) {
						echo "<div class='cats'>";
								echo '<h3>' . $cat['Namee'] . '</h3>';

								echo "<div class='slide'>";
								echo '<p>'; if($cat['Description'] == ''){echo "<p>No Description for The Category</p>"; }else{echo $cat['Description'] ;} echo '</p>';
								if($cat['Visibility'] == 1){echo ' <span class="visiability"><i class="fa fa-eye"></i> Hidden</span>'; }
								if($cat['Add_comment'] == 1){echo '<span class="comment"><i class="fa fa-times"></i> Comment Disabled</span>'; }
								if($cat['Allow_Ads'] == 1){echo '</i><span class="ads"><i class="fa fa-times"></i> Ads Disabled</span>'; }
								echo "</div>";
								echo "<div class='hidden-buttons'>";
								echo "<a href='?do=edit&id=" .  $cat['ID'] . "' class='btn btn-primary'><i class='fa fa-edit'></i> Edit</a> <a href='categories.php?do=delete&id=" .  $cat['ID'] . "'' class='btn btn-danger'><i class='fa fa-trash-alt'></i> Delete</a>";
								echo "</div>";

								$catts = getcat($cat['ID']);
								if(! empty($catts)){
									echo "<h4 class='headd'>Child Categories</h4>";
												echo "<ul class='list-unstyled child-tex' >";
											  foreach ($catts as $c) {
						                           echo "<li>
						                         		 	<a href='categories.php?do=edit& id=" . $c['ID'] . "'>" . $c['Namee'] . 
						                         		 	"</a>
						                        		</li>";
						              				} 
						              			echo "</ul>";}
								echo "<hr>";
					echo "</div>";


						}
					?>
				</div>
			</div>
			<a class="btn btn-primary" href="categories.php?do=add"><i class='fa fa-plus'></i>  Add New Category</a>
		</div>

	 <?php }elseif($do == 'add'){?>

			<h1 class="text-center">Add New Category</h1>
				<div class="container">
					<form class="form-horizontal" action="?do=insert" method="post">
						<div class="form-group form-group-lg">
								<label class="col-sm-2  ">Name </label>
								<div class="col-sm-6 "> <input type="text" name="name" class="form-control" placeholder=" Category Name" required="required" autocomplete="off"></div>
						</div>
						<div class="form-group form-group-lg">
								<label class="col-sm-2">Description </label>
								<div class="col-sm-6 "> 
								<input type="text" name="description" class="form-control" placeholder=" Category Description"></div>
						</div>
						<div class="form-group form-group-lg">
								<label class="col-sm-2 ">Ordering </label>
								<div class="col-sm-6 "> <input type="text" name="ordering" class="form-control" placeholder=" Number To Arrange The Category"  ></div>
						</div>
						<div class="form-group form-group-lg">
								<label class="col-sm-2 ">Parent </label>
								<div class="col-sm-6 "> 
									<select name="parent">
										<option value="0">None</option>
										<?php
											$paren = getparent('0');
											foreach($paren as$paentt){
												echo "<option class='len' value='" . $paentt['ID'] . "'>" . $paentt['Namee'] . " </option>";

											}


										?>
									</select>

								</div>
						</div>
						<div class="form-group form-group-lg">
							<label class="col-sm-2 ">Visibility </label>
							<div class="col-sm-6 "> 
								<div>
									 <input type="radio" name="vis"  checked value="0" id="viso">
									  <label for="viso">Yes</label>
								</div>
								<div>
									 <input type="radio" name="vis" value="1" id="nvis">
									 <label for="nvis" >No</label>
									</div>

							</div>
						</div>
						<div class="form-group form-group-lg">
							<label class="col-sm-2 ">Allow Commenting  </label>
							<div class="col-sm-6 "> 
								<div>
									 <input type="radio" name="coms"  checked value="0" id="acoms">
									  <label for="acoms">Yes</label>
								</div>
								<div>
									 <input type="radio" name="coms" value="1" id="n-coms">
									 <label for="n-coms" >No</label>
									</div>

							</div>
						</div>
						<div class="form-group form-group-lg">
							<label class="col-sm-2 ">Allow Ads </label>
							<div class="col-sm-6 "> 
								<div>
									 <input type="radio" name="ads"  checked value="0" id="ads">
									  <label for="ads">Yes</label>
								</div>
								<div>
									 <input type="radio" name="ads" value="1" id="nads">
									 <label for="nads" >No</label>
									</div>

							</div>
						</div>
						<div class="form-group form-group-lg">
							<div class="col-sm-offset-2 col-sm-8 "> <input type="submit" class="btn btn-primary btn-lg"  value="Add Category"></div>
						</div>
					</form>
				</div>

	<?php }
	elseif($do == 'insert'){

		echo "<div class='container'>";

		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			echo '<h1 class="text-center"> Insert Category </h1>';


				$name = $_POST['name'];
				$desc = $_POST['description'];
				$order = $_POST['ordering'];
				$parent = $_POST['parent'];
				$visiable = $_POST['vis'];
				$comment = $_POST['coms'];
				$ads = $_POST['ads'];


				$check = checkitem('Namee', 'categories', $name); 

				if($check == 1){
					$msg = "<div class='alert alert-danger'>This Category is Exist</div>";
					redirecting($msg, 'back');

				}else{
				
			
			
					$stmt = $con->prepare("INSERT INTO categories (Namee, Description, Ordering, parent, Visibility, Add_comment, Allow_Ads) 
																VALUES (:name, :zdesc, :zorder, :zparent, :zvis, :zcoms, :zads) " );		
					$stmt->execute(array(
											'name'			=>	$name,
											'zdesc'			=>	$desc,
											'zorder'		=>	$order,
											'zparent'		=>	$parent,
											'zvis'			=>	$visiable,
											'zcoms'			=>	$comment,
											'zads'			=>	$ads
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

		$catid = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;


		$stmt = $con->prepare("	SELECT * FROM categories WHERE ID = ? " );

		$stmt->execute(array($catid));

		$catg = $stmt->fetch();

		$count = $stmt->rowCount();

		if($count > 0 ){


		?>
		<h1 class="text-center">Edit Category <?php echo  $catg['parent']?></h1>
		<div class="container">
			<form class="form-horizontal" action="categories.php?do=update" method="post">
				<input type="hidden" name="id" value="<?php echo $catid  ?>">

				<div class="form-group form-group-lg">
						<label class="col-sm-2  ">Name </label>
						<div class="col-sm-6 "> <input type="text" name="name" class="form-control" placeholder=" Category Name"  value="<?php echo $catg['Namee'] ?>"></div>
				</div>
				<div class="form-group form-group-lg">
						<label class="col-sm-2">Description </label>
						<div class="col-sm-6 "> 
						<input type="text" name="description" class="form-control" placeholder=" Category Description" value="<?php echo $catg['Description'] ?>"></div>
				</div>
				<div class="form-group form-group-lg">
						<label class="col-sm-2 ">Ordering </label>
						<div class="col-sm-6 "> <input type="text" name="ordering" class="form-control" placeholder=" Number To Arrange The Category" value="<?php echo $catg['Ordering'] ?>" ></div>
				</div>
				<div class="form-group form-group-lg">
					<label class="col-sm-2 ">Parent </label>
					<div class="col-sm-6 "> 
						<select name="parent">
							<option value="0">None</option>
							<?php
								$paren = getparent('0');
								foreach($paren as$paentt){
									echo "<option class='len' value='" . $paentt['ID'] . "'";
									if($paentt['ID'] == $catg['parent']){
										echo " selected ";
									}
									echo ">" . $paentt['Namee'] . " </option>";

								}


							?>
						</select>

					</div>
				</div>
				<div class="form-group form-group-lg">
					<label class="col-sm-2 ">Visibility </label>
					<div class="col-sm-6 "> 
						<div>
							 <input type="radio" name="vis" id="viso" value='0' <?php if($catg['Visibility'] == 0){ echo "checked";} ?>>
							  <label for="viso">Yes</label>
						</div>
						<div>
							 <input type="radio" name="vis" value='1' <?php if($catg['Visibility'] == 1){ echo "checked";} ?> id="nvis">
							 <label for="nvis" >No</label>
							</div>

					</div>
				</div>
				<div class="form-group form-group-lg">
					<label class="col-sm-2 ">Allow Commenting  </label>
					<div class="col-sm-6 "> 
						<div>
							 <input type="radio" name="coms" value='0'   <?php if($catg['Add_comment'] == 0){ echo "checked";} ?> id="acoms">
							  <label for="acoms">Yes</label>
						</div>
						<div>
							 <input type="radio" name="coms" value='1' <?php if($catg['Add_comment'] == 1){ echo "checked";} ?> id="n-coms">
							 <label for="n-coms" >No</label>
							</div>

					</div>
				</div>
				<div class="form-group form-group-lg">
					<label class="col-sm-2 ">Allow Ads </label>
					<div class="col-sm-6 "> 
						<div>
							 <input type="radio" name="ads" value='0' <?php if($catg['Allow_Ads'] == 0){ echo "checked";} ?> id="ads">
							  <label for="ads">Yes</label>
						</div>
						<div>
							 <input type="radio" name="ads" value='1' <?php if($catg['Allow_Ads'] == 1){ echo "checked";} ?> id="nads">
							 <label for="nads" >No</label>
							</div>

					</div>
				</div>		 
				<div class="form-group form-group-lg">
					<div class="col-sm-offset-2 col-sm-8 "> <input type="submit" class="btn btn-primary btn-lg" value="Save"></div>
				</div>
			</form>
		</div>

	<?php }else{
			$msg = "There's No such ID";
			redirecting($msg);

		}

	}elseif($do == 'update'){

		
		echo "<div class='container'>";

		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			echo '<h1 class="text-center">Update Category </h1>';

				$name 		= $_POST['name'];
				$desc 		= $_POST['description'];
				$order 		= $_POST['ordering'];
				$parent 	= $_POST['parent'];
				$visiable 	= $_POST['vis'];
				$comment 	= $_POST['coms'];
				$ads 		= $_POST['ads'];
				$id 		= $_POST['id'];


				$stmt = $con->prepare("	UPDATE 
											categories 
										SET 
											 Namee 			= ?,
											 Description 	= ?,
											 Ordering 		= ?,
											 parent 		= ?,
											 Visibility 	= ?,
											 Add_comment 	= ?,
											 Allow_Ads 		= ?
										WHERE 
											ID 				= ? " );
	
				$stmt->execute(array($name, $desc, $order, $parent, $visiable, $comment, $ads, $id));
	
				$msg = '<div class="alert alert-success" >' . $stmt->rowCount() . ' Record Updated </div>';
				redirecting($msg, 'back');

						
		}else{

			$msg = "<div class='alert alert-danger'>Sorry You Can\'t browse this page directly</div>";
			redirecting($msg);

			
		}
			echo "</div>";

	}
	elseif($do == 'delete'){
			$id = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;


			$check = checkitem('ID', 'categories', $id);


			if($check > 0 ){
				$stmt = $con->prepare('DELETE FROM categories WHERE ID = :zid');
				$stmt->bindParam('zid', $id);
				$stmt->execute();

				echo '<h1 class="text-center">Delete Category </h1>';

				echo "<div class='container'>";


				$msg = '<div class="alert alert-success" >' . $stmt->rowCount() . ' Record Deleted </div>';
				redirecting($msg, 'back');

				echo "</div>";
			}else{
				echo "<div class='container'>";
				$msg = "<div class='alert alert-danger'> This ID is Not Exist</div> ";
				redirecting($msg);
				echo "</div>";}

	}
	
 	include $inclu . 'footer.php'; 


	} else {

		header('Location: index.php');

	exit();}

ob_end_flush();
?>