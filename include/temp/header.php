<!DOCTYPE html>
	<html>
	<head>
		<meta charset="UTF-8" />
		<title><?php echo $pagename; ?></title>
		<link rel="stylesheet" href="admin/<?php echo $laycss;?>bootstrap.min.css">
		<link rel="stylesheet" href="admin/<?php echo $laycss;?>fontawesome-all.min.css">
		<link rel="stylesheet" href="admin/<?php echo $laycss;?>jquery-ui.css">
		<link rel="stylesheet" href="admin/<?php echo $laycss;?>jquery.selectBoxIt.css">
		<link rel="stylesheet" href="<?php echo $laycss;?>frontend.css">

	</head> 
	<body>
    <div class="upper-bar">
      <?php
       echo "<div class='container'>";
      if(isset($_SESSION['user'])){
        ?>
        <img class='img-responsive img-circle' src='layout/css/images/img.png'/>
        <div class="btn-group my-info">
          <span class="btn btn-default dopdown-toggle" data-toggle="dropdown">
            <?php echo $sessionUser; ?>
            <span class="caret"></span>
          </span>
          <ul class="dropdown-menu">
            <li><a href='profile.php'> My Profile</a></li>
            <li><a href='newad.php'> New Item</a></li>
            <li><a href='profile.php#new-item'> My Items</a></li>
            <li><a href='logout.php'> Logout</a></li>

          </ul>
        </div>


        <?php
      
        }else{
          echo "<a class='pull-right sign ' href='login.php'><span>Login/Sign Up</span></a>";
          }     
         echo "</div>";

       ?>

    </div>
	  <nav class="navbar navbar-inverse">
        <div class="container">
          <!-- Brand and toggle get grouped for better mobile display -->
          <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#ap-nav" aria-expanded="false">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php">Home Page</a>
          </div>

          <div class="collapse navbar-collapse" id="ap-nav">
            <ul class="nav navbar-nav navbar-right">

              <?php foreach (getcat('0') as $cat) {
                           echo "<li>
                          <a href='categories.php?pageid=" . $cat['ID'] . "'>" . $cat['Namee'] . 
                          "</a>
                        </li>";
              } ?>
            </ul>
           
          </div>
        </div>
      </nav>