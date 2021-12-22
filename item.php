<?php
	session_start();

	$pagetitle = 'Items';

	include "init.php";
	// check userid
	$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0 ;

	$stmt  = $conn->prepare("SELECT items.* , categories.Name AS Category_Name , users.Username
		FROM items 
		INNER JOIN categories ON categories.ID = items.Cat_ID
		INNER JOIN users ON users.UserID = items.Member_ID
		WHERE ItemID = ? AND Approve = 1");

	$stmt->execute(array($itemid));

	$count = $stmt->rowCount();
	if ($count > 0) {

	$item  = $stmt->fetch();
		
	?>
	<h1 class="text-center text-secondary mt-4 display-3"><?php echo $item['Name']; ?></h1>
	<div class="container">
		<div class="row">
			<div class="col-md-2">
				<img src="profile.jpeg" class="img-fluid" />
			</div>
			<div class="col-md-10">
				<ul class="list-group">
			  		<li class="list-group-item list-group-item-action list-group-item-info">
			  			<h2><?php echo $item['Name']; ?></h2>
			  		</li>
					<li class="list-group-item">
						<i class="bi bi-card-text"></i>
						<?php echo $item['Description']; ?>
					</li>
					<li class="list-group-item">
						<i class="bi bi-cash-coin"></i>
						<?php echo $item['Price']; ?>
					</li>
					<li class="list-group-item">
						<i class="bi bi-calendar3"></i>
						<?php echo $item['Add_Date']; ?>
					</li>
					<li class="list-group-item">
						<i class="bi bi-house-door"></i>
						<strong>Made In : </strong>
						<?php echo $item['Country_Made']; ?>
					</li>
					<li class="list-group-item">
						<i class="bi bi-tags-fill"></i>
						<strong>Category Name : </strong>
						<a href="categories.php?pageid=<?php echo $item['Cat_ID'] ?>">
						<?php echo $item['Category_Name']; ?>
						</a>
					</li>
					<li class="list-group-item">
						<i class="bi bi-file-person"></i>
						<strong>Add By : </strong>
						<?php echo $item['Username']; ?>
					</li>
					<li class="list-group-item">
						<i class="bi bi-hash"></i>
						<strong>Tags : </strong>
						<?php 
						$allTags = explode(",", $item['Tags']);

						foreach ($allTags as $tag) {
							$tag   = str_replace(" ", "", $tag);
							$lower = strtolower($tag);
							if (!empty($tag)) {
							
							echo "<a class='tag bg-dark text-warning me-1 px-1 rounded' href='tags.php?name={$lower}'>". $tag ."</a>";
								}
							}



						?>
					</li>
				</ul>
				
			</div>
		</div>

		<!-- start Add-comments -->
	<?php if (isset($_SESSION['client'])) { ?>

		<div class="mt-4 offset-sm-2">
			<h3 class="text-center text-secondary">Add Comment</h3>
			<form method="POST" action="<?php $_SERVER['PHP_SELF'].'itemid='. $item['ItemID']?>">

				<div class="form-floating">
					<textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea" name="comment" required></textarea>
					<label for="floatingTextarea ps-1">Comments</label>
				</div>
				<input type="submit" name="add-comment" value="Add Comment" class="btn btn-primary mt-2 w-100" />

			</form>

	<?php 

			if ($_SERVER['REQUEST_METHOD'] == "POST") {

				$comment = filter_var($_POST['comment'],FILTER_SANITIZE_STRING);
				$itemId  = $item['ItemID'];
				$userId  = $_SESSION['userid'];

				if (!empty($comment)) {
					$stmt = $conn->prepare("INSERT INTO comments(Comment,Status,Comment_Date,Item_ID,User_ID)
						VALUES(:xcomment,0,now(),:xitemid,:xuserid)");
					$stmt->bindParam(":xcomment",$comment);
					$stmt->bindParam(":xitemid",$itemId);
					$stmt->bindParam(":xuserid",$userId);
					$stmt->execute();
					if ($stmt) {
						echo "<div class='alert alert-success mt-2'>Add Comment</div>";
					}
				}else{
					echo "<div class='alert alert-danger mt-2'>Your Comment Empty</div>";
				}
			}

		} else{ echo "<p><a href='login.php'>Login</a> To Add Comment</p>"; }

	?>
		</div>
		<!-- End Add-comments -->

		<div class="row mt-5">
		<?php

				$stmt = $conn->prepare("SELECT comments.* , users.Username As Member 
				FROM comments
				INNER JOIN users ON users.UserID = comments.User_ID
				WHERE Item_ID  = ? AND Status = 1
				ORDER BY C_ID DESC");

				$stmt->execute(array($item['ItemID']));
				$commentss = $stmt->fetchAll();

				foreach ($commentss as $commen) { 
		?>

				<div class="col-md-2 text-center pb-3">
					<img src="profile.jpeg" class="img-fluid rounded-circle w-75" />
					<p class="pt-2"><?php echo $commen['Member']?></p>
				</div>

				<div class="col-md-10">
					<p class="pt-3 ps-3"><?php echo $commen['Comment']?></p>
				</div>
		</div>
	</div> <!-- container -->

	

	
<?php
	}
	}else{
		
		echo "<div class='container mt-5 alert alert-danger'>Wait Approve</div>";
	}
	include $tpl ."footer.php";

?>