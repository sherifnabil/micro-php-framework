<?php 
$pagename = 'Category';

 include "init.php"; 
 ?>
 <div class="container">
 	<h1 class="text-center">Show Category</h1>
 	<?php $itemid = isset($_GET['pageid']) && is_numeric($_GET['pageid']) ? intval($_GET['pageid']) : 0; ?>
 	<div class="row">
	    <?php foreach (getItems('Cat_ID', $itemid) as $item)
	    		{
	    			echo "<div class='col-sm-6 col-md-3'>";
	    				echo "<div class='thumbnail item-box'>";
	    					echo "<span class='price-tag'>" . $item['Price'] . "</span>";
	    					echo "<img class='img-responsive' src='layout/css/images/img.png'/>";
	               			echo "<h3><a href='items.php?itemid=" . $item['item_ID'] . "'>" . $item['Name'] . "</a></h3>";
	               			echo "<p>" . $item['Description'] . "</p>";
				            echo "<div class='date'>" . $item['Add_Date'] . "</div>";
	    				echo "</div>";
	    			echo "</div>";
	            } 
	    ?>
 	</div>
 </div>
    


<?php include $inclu . "footer.php";?>


