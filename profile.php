<?php
	session_start();

	$pagetitle = 'Profile';

	include "init.php";

	if (isset($_SESSION['client'])) {

		$getUser = $conn->prepare("SELECT * FROM users WHERE Username = ?");
		$getUser->execute(array($_SESSION['client']));
		$info = $getUser->fetch();
		$userid = $info['UserID'];
	?>
	<h1 class="text-center text-secondary mt-4 display-3">My Profile</h1>
	<div class="information mt-5">
		<div class="container">
			<ul class="list-group">
			  	<li class="list-group-item list-group-item-primary">My Information</li>
			  	<li class="list-group-item">Name: <?php echo $info['Username'] ?></li>
			  	<li class="list-group-item">Fullname: <?php echo $info['Fullname'] ?></li>
			  	<li class="list-group-item">Email: <?php echo $info['Email'] ?></li>
			  	<li class="list-group-item">Date: <?php echo $info['Date'] ?></li>
			  	<li class="list-group-item">Favorite: Category</li>
			  	<li class="list-group-item"><button class="btn btn-primary">Edit Information</button></li>
			</ul>
		</div>
	</div>

	<div id="my-ads" class="my-ads">
		<div class="container">
			<ul class="list-group my-4">
			  	<li class="list-group-item list-group-item-primary">My Items</li>
			  	<li class="list-group-item">
			  		<div class="row">
						<?php
							 //$getItem = getItems("Member_ID",$info['UserID'],1);

							$getItem = getAll("*","items","where Member_ID = $userid","","ItemID");

							if (!empty($getItem)) {
							
								foreach ($getItem as $item) { ?>
									<div class="col-md-3 col-sm-6">
										<div class="card position-relative mb-2">
											<?php if ($item['Approve'] == 0) {
												echo '<span class="bg-danger text-white wait-approve">Waiting Approve</span>';
											} ?>
											<img src="iphone-13.jpeg" class="card-img-top" alt="...">
											<div class="card-body">
 												<h5 class="">
	 												<a href="item.php?itemid=<?php echo $item['ItemID'] ?>">
	 												<?php echo $item['Name'] ?>
	 												</a>
											    </h5>

											    <span class="d-block text-desc"> <?php echo $item['Description'] ?></span>

											    <strong class="fst-italic text-secondary">
											    	<?php echo $item['Price'] ?>
											    </strong>

											    <p class="card-text">
											    	<?php echo $item['Add_Date'] ?>		
											    </p>
											</div>
										</div>
									</div>
						<?php
							} 
								}else{
									echo "<p class='text-center pt-2'>No Ads<a href='newad.php'> Add New Ad</a></p>";
								}

						?>
					</div>
			  	</li>
			</ul>
		</div>
	</div>

	<div class="my-comments">
		<div class="container">
			<ul class="list-group">
			  	<li class="list-group-item list-group-item-primary">My Comments</li>
			  	<li class="list-group-item">
  					<?php

  						$comments = getAll("Comment","comments","WHERE User_ID = $userid","","C_ID");
						if (!empty($comments)) {
							
							foreach ($comments as $comment) {
								echo "<p>". $comment['Comment'] ."</p>";
							}

						}else{
							echo "<p class='text-center pt-2'>No Comments</p>";
						}
					?>
			  	</li>
			</ul>
		</div>
	</div>

	
<?php
}else{
	header("Location:login.php");
	exit();
}

	include $tpl ."footer.php";

?>