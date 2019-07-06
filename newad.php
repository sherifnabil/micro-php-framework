<?php 
ob_start();
$pagename = 'Create New Item';
session_start();

 include "init.php"; 

 if(isset($_SESSION['user'])){

 	if($_SERVER['REQUEST_METHOD'] == 'POST'){
 		$name 		= filter_var($_POST['name'], FILTER_SANITIZE_STRING);
 		$desc 		= filter_var($_POST['description'], FILTER_SANITIZE_STRING);
 		$price 		= filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT);
 		$country 	= filter_var($_POST['country'], FILTER_SANITIZE_STRING);
 		$status 	= filter_var($_POST['status'], FILTER_SANITIZE_NUMBER_INT);
 		$category 	= filter_var($_POST['category'], FILTER_SANITIZE_NUMBER_INT);
 		$tags 	= filter_var($_POST['tags'], FILTER_SANITIZE_STRING);



 		$formErrors = array();

 		if(strlen($name) < 4){
 			$formErrors[] =  "Item Title Must Be At Least 4 Chars ";
 		}
 		if(strlen($desc) < 10){
 			$formErrors[] =  "Item Description Must Be At Least 10 Chars ";
 		}
 		if(empty($price)){
 			$formErrors[] =  "Item Price Must Not Be Empty ";
 		}
 		if(strlen($country) < 3 ){
 			$formErrors[] =  "Item country Must Not Be less than 4 chars ";
 		}
 		if(empty($category) ){
 			$formErrors[] =  "Item Category Must Not Be Empty ";
 		}
		if(empty($formErrors)){

			$stmt = $con->prepare("INSERT INTO items (Name, Description, Price, Country_Made, Status, Add_Date, Member_ID, Cat_ID, tags) 
						VALUES (:zname, :zdesc, :zprice, :zcountry, :zstatus, Now(), :zmember, :zcat, :ztags)" );		
			$stmt->execute(array(
									'zname'		=>	$name,
									'zdesc'		=>	$desc,
									'zprice'	=>	$price,
									'zcountry'	=>	$country,
									'zstatus'	=>	$status,
									'zmember'	=>	$_SESSION['uid'],
									'zcat'		=>	$category,
									'ztags'		=>	$tags
										));
				
				if($stmt){ echo '<div class="alert alert-success"> Item Added </div>';}
				
		}		





 	}


?>
<h1 class="text-center"><?php echo $pagename; ?></h1>
<div class="information block">
	<div class="container">
		<div class="panel panel-primary mar">
			<div class="panel-heading">
				<?php echo $pagename; ?>
			</div>
			<div class="panel-body inform ad">
				<div class="row">
					<div class="col-md-8">
						<form class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
						<div class="form-group form-group-lg">
								<label class="col-sm-2  ">Name </label>
								<div class="col-sm-6 "> 
									<input 
									type="text" 
									pattern=".{4,}"
									title="This Field Requires At Least 4 Chars" 
									name="name" 
									class="form-control live" 	
									placeholder=" Item Name" 
									required="required"
									data-class=".nam" />
								</div>
						</div>
						<div class="form-group form-group-lg">
								<label class="col-sm-2  ">Description </label>
								<div class="col-sm-6 ">
									<input 
									pattern=".{10,}"
									title="This Field Requires At Least 10 Chars"
									type="text" 
									name="description" 
									class="form-control live" 	
									placeholder=" Item Description"
									required="required"
									data-class=".desc"/ >
								</div>
								
						</div>
						<div class="form-group form-group-lg">
								<label class="col-sm-2  ">Price </label>
								<div class="col-sm-6 ">
									<input 
									type="text" 
									name="price" 
									class="form-control live" 	
									placeholder=" Item Price" 
									required="required"
									data-class=".price"
									required="required" 

									/ >
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
									<select class="form-control " name="status" required="required">
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
								<label class="col-sm-2  ">Category </label>
								<div class="col-sm-6">
									<select class="form-control " name="category" required="required">
										<option value="0">...</option>
										<?php 
											$stm = $con->prepare("SELECT * FROM categories WHERE parent = 0");
											$stm->execute();
											$cats= $stm->fetchAll();
											foreach ($cats as $cat) {
												echo "<option value='" . $cat['ID'] . "'>" . $cat['Namee'] . "</option>";
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

									/ >
								</div>
								
						</div>		
						<div class="form-group form-group-lg">
							<div class="col-sm-offset-2 col-sm-8 "> <input type="submit" class="btn btn-primary btn-md"  value="Add Item">
							</div>
						</div>
					</form>


					</div>
					<div class="col-md-4">
							<div class='col-sm-6 col-md-9'>
		    					<div class='thumbnail item-box'>
		    						
		    					<span class='price-tag'>$<span class='price'></span></span>
		    						<img class='img-responsive' src='layout/css/images/	img.png'>
		               				<h3 class="nam">Title</h3>
		               				<p class="desc">Description</p>
		    					</div>
		    				</div>
		    			
					</div>
				</div>
				
			</div>
			<?php 
				if(! empty($formErrors)){
					foreach ($formErrors as $error) {
						echo "<div class='alert alert-danger'>" . $error . "</div>";
					}
				}




				?>
		</div>
	</div>
</div>

<?php }else{
	header('Location: login.php');
	exit();
}
include $inclu . "footer.php";
ob_end_flush();?>