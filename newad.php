<?php
	session_start();

	$pagetitle = 'Creat New Item';

	include "init.php";

	if (isset($_SESSION['client'])) {

		// print_r($_SESSION);

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {

			$formErrors = array();

			$name 		= filter_var($_POST['nameitem'],FILTER_SANITIZE_STRING);
			$desc 		= filter_var($_POST['description'],FILTER_SANITIZE_STRING);
			$price 		= filter_var($_POST['price'],FILTER_SANITIZE_NUMBER_INT);
			$country 	= filter_var($_POST['country'],FILTER_SANITIZE_STRING);
			$tags 		= filter_var($_POST['tags'],FILTER_SANITIZE_STRING);
			$status 	= filter_var($_POST['status'],FILTER_SANITIZE_NUMBER_INT);
			$cat 		= filter_var($_POST['category'],FILTER_SANITIZE_NUMBER_INT);

			if (strlen($name) < 4) {
				$formErrors[] = 'Item Name must be more than 4 caracters';
			}

			if (strlen($desc) < 10) {
				$formErrors[] = 'Item Description must be more than 10 caracters';
			}

			if (strlen($country) < 2) {
				$formErrors[] = 'Item Country must be more than 2 caracters';
			}

			if (empty($price)) {
				$formErrors[] = 'Item Price must be Not empty';
			}

			if (empty($status)) {
				$formErrors[] = 'Item Status must be Not empty';
			}

			if (empty($cat)) {
				$formErrors[] = 'Item Category must be Not empty';
			}

				if (empty($formErrors)) {

					$stmt = $conn->prepare("INSERT INTO items(Name,Description,Price,Country_Made,Tags,Status,Add_Date,Cat_ID,Member_ID)
					 VALUES(:zname, :zdescription, :zprice, :zcountry, :ztags , :zstatus, now(),:zcat,:zmembers)");

					$stmt->bindParam(':zname',$name);
					$stmt->bindParam(':zdescription',$desc);
					$stmt->bindParam(':zprice',$price);
					$stmt->bindParam(':zcountry',$country);
					$stmt->bindParam(':ztags',$tags);
					$stmt->bindParam(':zstatus',$status);
					$stmt->bindParam(':zcat',$cat);
					$stmt->bindParam(':zmembers',$_SESSION['userid']);

					$stmt->execute();

					if ($stmt) {
						$success = "Item Added";
					}
				}
		}
		
	?>
	<h1 class="text-center text-secondary mt-4 display-3">Creat New Ad</h1>
	<div class="information mt-5">
		<div class="container">

			<?php if (!empty($formErrors)) { ?>
                <ul class="list-group mb-3" role="alert">
                    <?php
                        foreach ($formErrors as $error) {
                        echo "<li class='list-group-item bg-danger text-white mt-2'>". $error . "</li>";
                        }
                    ?>
                </ul>
            <?php }
            if (isset($success)) {
            	echo "<div class='mt-3 alert alert-success'>".$success."</div>";
            }

            ?>

			<ul class="list-group">
			  	<li class="list-group-item list-group-item-primary">Creat New Ad</li>
			  	<li class="list-group-item">
			  		<div class="row">
			  			<div class="col-md-8">
			  					<form class="form-horizontal" action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">
							<!-- Name -->
							<div class="form-group">
								<label class="col-sm-2 col-form-label">Name</label>
								<div class="col-sm-9">
									<input type="text" name="nameitem" class="form-control live" data-class=".live-name" />
								</div>
							</div>
							<!-- Description -->
							<div class="form-group">
								<label class="col-sm-2 col-form-label">Description</label>
								<div class="col-sm-9">
									<input type="text" name="description" class="form-control live" data-class=".live-desc"/>
								</div>
							</div>
							<!-- Price -->
							<div class="form-group live">
								<label class="col-sm-2 col-form-label">Price</label>
								<div class="col-sm-9">
									<input type="text" name="price" class="form-control live" data-class=".live-price" />
								</div>
							</div>
							<!-- Country Made -->
							<div class="form-group">
								<label class="col-sm-3 col-form-label">Country Made</label>
								<div class="col-sm-9">
									<input type="text" name="country" class="form-control" />
								</div>
							</div>
							<!-- Tags -->
							<div class="form-group">
								<label class="col-sm-3 col-form-label">Tags</label>
								<div class="col-sm-9">
									<input type="text" name="tags" class="form-control" />
								</div>
							</div>
							<!-- Status -->
							<div class="form-group">
								<label class="col-sm-2 col-form-label">Status</label>
								<div class="col-sm-9">
									<select name="status" class="form-control select-status">
										<option value="0">...</option>
										<option value="1">New</option>
										<option value="2">Like New</option>
										<option value="3">Used</option>
										<option value="4">Old</option>
									</select>
								</div>
							</div>
							<!-- Categories -->
							<div class="form-group">
								<label class="col-sm-2 col-form-label">Category</label>
								<div class="col-sm-9">
									<select name="category" class="form-control select-status">
										<option value="0">...</option>
										<?php
											$categories = getAll('*','categories','','','ID','ASC');
										
											foreach ($categories as $cat) {

												echo "<option value='". $cat['ID'] ."'> " .$cat['Name']. "</option>";
											}
										?>
									</select>
								</div>
							</div>
							
							<!-- submit -->
							<div class="form-group mt-4">
								<div class="col-sm-9">
									<input type="submit" value="Add Item" class="btn btn-primary">
								</div>
							</div>
						</form>


			  			</div>
			  			<div class="col-md-4">
			  				<div class="card">
								<img src="iphone-13.jpeg" class="card-img-top img-fluid" alt="...">
								<div class="card-body live-preview">
								    <h5 class="card-title live-name">Title</h5>
								    <p class="card-text live-desc">Description</p>
								    <strong class="fst-italic text-secondary live-price">0</strong>
								</div>
							</div>
			  			</div>
			  		</div>
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