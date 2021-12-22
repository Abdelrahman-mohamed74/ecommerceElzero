<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title><?php echo getTitle()?></title>
	<link rel="stylesheet"  href="<?php echo $css?>bootstrap.min.css" />
	<link rel="stylesheet"  href="<?php echo $css?>all.min.css" />
	<link rel="stylesheet"  href="layout/font/bootstrap-icons.css"/>
	<link rel="stylesheet"  href="<?php echo $css?>frontend.css" />
</head>
<body>

	<div class="container">

		<?php if(isset($_SESSION['client'])) { ?>

			<nav class="navbar">
				<div class="d-flex align-items-center">
					<img src="profile.jpeg" class="rounded-circle me-2" />
					<div class="dropdown">
						<button class="btn btn-outline-dark dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-expanded="false">
						    <?php echo $_SESSION['client'] ?>
						</button>
						<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
						    <a class="dropdown-item" href="profile.php">My profile</a>
				          	<a class="dropdown-item" href="newad.php">New Item</a>
				          	<a class="dropdown-item" href="profile.php#my-ads">My Items</a>
				          	<div class="dropdown-divider"></div>
				          	<a class="dropdown-item" href="logout.php">Logout</a>
						</div>
					</div>
				</div>
			</nav>
		<?php

			}else{
				echo '<div class="d-flex flex-row-reverse py-2">
						<a href="login.php">Login / Signin</a>
					</div>';
			}
		?>
	</div>

	<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
		<div class="container">
		  	<a class="navbar-brand" href="index.php">Home</a>
		  	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#app-nav" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
		    <span class="navbar-toggler-icon"></span>
		  	</button>

		  	<div class="collapse navbar-collapse flex-row-reverse" id="app-nav">
			    <ul class="navbar-nav">
			        <?php
			        
			        	$categories = getAll("*","categories","WHERE Parent = 0","","ID","ASC");
			            foreach ($categories as $cat) {
			            echo ' <li class="nav-item">
			                    	<a class="nav-link" href="categories.php?pageid=' .$cat['ID'].'"> ' .$cat['Name']. '
			                        </a>
			                    </li>';
            		} ?>

			    </ul>
		  	</div>
		</div>
	</nav>
