<?php 
$pagename = 'Tags';

 include "init.php"; 
 ?>
 <div class="container">
 	<?php $itemid = isset($_GET['name']); 
 			$tag = $_GET['name']; ?>
 	 	<h1 class="text-center"><?php echo $tag; ?></h1>

 	<div class="row">
	    <?php
 		getccat($tag);


	     foreach (getccat($tag) as $ite)
	    		{
	    			echo "<div class='col-sm-6 col-md-3'>";
	    				echo "<div class='thumbnail item-box'>";
	    					echo "<span class='price-tag'>" . $ite['Price'] . "</span>";
	    					echo "<img class='img-responsive' src='layout/css/images/img.png'/>";
	               			echo "<h3><a href='items.php?itemid=" . $ite['item_ID'] . "'>" . $ite['Name'] . "</a></h3>";
	               			echo "<p>" . $ite['Description'] . "</p>";
				            echo "<div class='date'>" . $ite['Add_Date'] . "</div>";
	    				echo "</div>";
	    			echo "</div>";
	            } 
	    ?>
 	</div>
 </div>
    


<?php include $inclu . "footer.php";?>


