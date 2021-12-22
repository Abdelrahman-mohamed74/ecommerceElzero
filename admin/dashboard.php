<?php	
	session_start();

	if (isset($_SESSION['Username'])) {
		$pagetitle = 'Dashboard';

		include "init.php";
		/* Start Dashboard Page*/

		$LatestUser  = getLatest("*","users","UserID", 5);
		$LatestItems = getLatest("*","items","ItemID", 5);

		?>

		<div class="container dashboard text-center">
			<h1 class="text-center text-secondary my-4">Dashboard</h1>
			<div class="row">
				<div class="col-md-3">
					<div class="stat bg-dark">
						Total members <span><a href="members.php"><?php echo countItems("UserID", "users"); ?></a></span>
					</div>
				</div>
				<div class="col-md-3">
					<div class="stat bg-info">
						Pending members <span><a href="members.php?do=Manage&page=pending"><?php echo checkItem("RegStatus","users",0) ; ?>
							
						</a></span>
					</div>
				</div>
				<div class="col-md-3">
					<div class="stat bg-success">
						Total Items <span><a href="items.php"><?php echo countItems("ItemID", "items") ?></a></span>
					</div>
				</div>
				<div class="col-md-3">
					<div class="stat bg-primary">
						Total Comments <span><a href="comments.php"><?php echo countItems("Item_ID", "comments") ?></a></span>
					</div>
				</div>
			</div>
		</div>

		<div class="container mt-3">
			<div class="row">
				<div class="col-sm-6">
					<div class="card">
						<div class="card-header">
							<i class="fas fa-users"></i> Latest Users
						</div>
							<ul class="list-group list-group-flush">

							    	<?php

							    	if(!empty($LatestUser)){
								    	foreach ($LatestUser as $user) {
				
											echo "<li class='list-group-item d-flex justify-content-between align-items-center'>" .$user['Username']."<div> <a href='members.php?do=Edit&userid=".$user['UserID']."'><span class='btn btn-success btn-sm'><i class='far fa-edit'></i>Edit";

												if ($user['RegStatus'] == 0) {
												echo "<a href='members.php?do=Activate&userid=".$user['UserID']."' class='btn btn-info activate btn-sm text-white'><i class='fas fa-check'></i>Activate</a>";
											}
											echo "</span></div></a> </li>";
										}
									}else{
										echo "<div class='container'>
												<div class='alert alert-danger'>Here Not Record To show</div>
											</div>";
									}

							    	?>
							</ul>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="card">
						<div class="card-header">
							<i class="fas fa-tag"></i> Items
						</div>
							<ul class="list-group list-group-flush">
								<?php
								if (!empty($LatestItems)) {
								
									foreach ($LatestItems as $items) { ?>
										<li class="list-group-item d-flex justify-content-between align-items-center">
											<?php echo $items['Name'] ?>
											<div>
												<a href="items.php?do=Edit&itemid=<?php echo $items['ItemID'] ?>"><span class="btn btn-success btn-sm"><i class="far fa-edit"></i>Edit</span></a>
												<?php

													if ($items['Approve'] == 0) { ?>
														<a href="items.php?do=Approve&itemid=<?php echo $items['ItemID'] ?>"><span class="btn btn-info btn-sm text-white"><i class="fas fa-check"></i>Approve</span></a>

												<?php } ?>
											</div>
										</li>
								<?php }
								}else{

								 	echo "<div class='container mt-3'>
											<div class='alert alert-danger'>Here Not Record To show</div>
										</div>";
									}
								?>
    
							</ul>
					</div>
				</div>
			</div>
			<!-- Latest comments -->
			<div class="row">
				<div class="col-sm-6">
					<div class="card mt-4">
						<div class="card-header">
							<i class="far fa-comments"></i> Latest Comments
						</div>
							<ul class="list-group list-group-flush">

							    <?php

							    	$stmt = $conn->prepare("SELECT comments.*,users.Username As Member
									FROM comments
									INNER JOIN users ON users.UserID = comments.User_ID
									ORDER BY C_ID DESC 
									LIMIT 5");

									$stmt->execute();
									$comments = $stmt->fetchAll();

									if(!empty($comments)){
							    	
							    	foreach ($comments as $comment) { ?>
							    		<li class="list-group-item d-flex comments-box">
							    			<a href="members.php?do=Edit&userid=<?php echo $comment['User_ID'] ?>" class="text-center"> <?php echo $comment['Member'] ?> </a>
							    			<p class="ms-3"> <?php echo $comment['Comment'] ?> </p>
							    		</li>

							    <?php 
									}
							    		}else{
							    			echo "<div class='container mt-3'>
													<div class='alert alert-danger'>Here Not Record To show</div>
												</div>";
							    		}
							    ?>
							</ul>
					</div>
				</div>
			</div>
		</div>



	<?php
	/* End Dashboard Page*/ 

	include $tpl ."footer.php"; 
	}else{
		
		header('Location:index.php');
		exit();
	}
?>