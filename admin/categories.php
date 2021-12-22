<?php
ob_start();

session_start();
$pagetitle = 'Categories';

if (isset($_SESSION['Username'])) {
	include "init.php";

	$do = isset($_GET['do']) ? $_GET['do'] : "Manage" ;

	if ($do == "Manage") { // Manage Page

		$sort = 'ASC';
		$sort_array = array("ASC","DESC");

		if (isset($_GET['sort']) && in_array($_GET['sort'], $sort_array)) {

			$sort = $_GET['sort'];
		}

		$stmt = $conn->prepare("SELECT * FROM categories WHERE Parent = 0 ORDER BY Ordaring $sort");
		$stmt->execute();
		$categories = $stmt->fetchAll();
		if(!empty($categories)){
		?> 

		<h1 class="text-center my-4 text-secondary">Manage Categories</h1>
		<div class="container">
			<div class="card">
				<div class="card-header d-flex justify-content-between">
				    Manage Categories
				    <div class="option">
				    	<p class="d-inline fw-bold">Ordering :</p>
				    	<a class="<?php if($sort == 'ASC'){ echo 'active';} ?>" href="?sort=ASC"> ASC</a>
				    	<a class="<?php if($sort == 'DESC'){ echo 'active';} ?>" href="?sort=DESC"> DESC</a>

				    	<p class="d-inline fw-bold">View :</p>
				    	<span class="active" data-view="full">Full</span>
				    	<span>Classic</span>

				    </div>
				</div>
				<ul class="list-group list-group-flush">

				    	<?php foreach ($categories as $cat) { ?>

				    			<li class='list-group-item pb-3 position-relative overflow-hidden categories'>
				    				<div class="hidden-buttons">
				    					<a href="categories.php?do=Edit&catid=<?php echo $cat['ID'] ?>" class="btn btn-success btn-sm"><i class="far fa-edit"></i> Edit</a>
					    				<a href="categories.php?do=Delete&catid=<?php echo $cat['ID'] ?>" class="btn btn-danger btn-sm confirm"><i class="fas fa-times"></i> Delete</a>
				    				</div>

				    				<h3 class="text-secondary"> <?php echo $cat["Name"]; ?> </h3>
				    				<div class="full-view">
					    				<p class="text-black-50">
					    					<?php if ($cat["Description"] == '') {

					    						echo "This Is Empty";

					    					}else{ echo $cat["Description"]; } ?>
					    						
					    				</p>

					    				<?php

					    					if ($cat["Visibilty"] == 1) {
					    						echo "<span class='text-danger p-2 rounded'>Hidden</span>";

					    					}

					    					if($cat["Allow_Comment"] == 1) {
					    						echo "<span class='text-dark p-2 mx-3 rounded'>Comment Disabled</span>";
					    					}

					    					if($cat["Allow_Ads"] == 1) {
					    						echo "<span class='text-primary p-2 rounded'>Ads Disabled</span>";
					    					}

					    					// child category

					    					$childCat = getAll("*","categories","WHERE Parent = {$cat['ID']}","","ID","ASC");

					    					foreach ($childCat as $c) { ?>
					    						<h5 class="text-success">Child Categories</h5>
					    				<div class="child-link">
							            	<a class="bg-dark text-warning p-1" href="categories.php?do=Edit&catid=<?php echo $c['ID'] ?>"><?php echo $c['Name'] ?></a>

							            	<a href="categories.php?do=Delete&catid=<?php echo $c['ID'] ?>" class="confirm text-danger ms-2 show-delete">Delete</a>
							           	</div>

							            	<?php } ?>

				    				</div>
				    		
				    			</li>
				    	<?php } ?>
				</ul>
			</div>
			<a href="categories.php?do=Add" class="btn btn-primary my-4"><i class="fas fa-plus"></i> Add Category</a>
		</div>

	<?php
			}else{
				echo "<div class='container mt-3'>
							<div class='alert alert-danger'>Here Not Record To show</div>
							<a href='categories.php?do=Add' class='btn btn-primary btn-sm'><i class='fas fa-plus'></i> Add Category</a>
					</div>";
			}
		
		}elseif ($do == "Add") { // Add page ?>
		
		<h1 class="text-center my-4 text-secondary">Add New Categories</h1>
					
					<div class="container pb-5">
						<form class="form-horizontal offset-md-2" action="?do=Insert" method="POST">
							<!-- Name -->
							<div class="form-group">
								<label class="col-sm-2 col-form-label">Name</label>
								<div class="col-sm-10 col-md-6">
									<input type="text" name="name" class="form-control" autocomplete="off" required="required" placeholder="Name of the Category" />
								</div>
							</div>
							<!-- Description -->
							<div class="form-group">
								<label class="col-sm-2 col-form-label">Description</label>
								<div class="col-sm-10 col-md-6">
									<input type="text" name="description" class="form-control" />
								</div>
							</div>
							<!-- Ordaring -->
							<div class="form-group">
								<label class="col-sm-2 col-form-label">Ordaring</label>
								<div class="col-sm-10 col-md-6">
									<input type="text" name="ordaring" class="form-control" />
								</div>
							</div>
							<!-- Parents -->
							<div class="form-group mb-3">
								<label class="col-sm-2 col-form-label">Parent</label>
								<div class="col-sm-10 col-md-6">
									<select name="parent" class="form-control select-status">
										<option value="0">None</option>
										<?php
											$allcats = getAll("*","categories","WHERE Parent = 0","","ID");

											foreach ($allcats as $cat) {
												echo "<option value='".$cat['ID']."'>". $cat['Name'] ."</option>";
											}

										?>
									</select>
								</div>
							</div>
							<!-- visibility -->
							<div class="form-group d-flex justify-content-between w-25 mb-3">
								<label class="col-sm-2 col-form-label">visible</label>
								<div class="col-sm-10 col-md-6">
									<div>
										<input id="visibli-yes" type="radio" name="visibility" value="0" checked />
										<label for="visibli-yes">Yes</label>
									</div>
									<div>
										<input id="visibli-no" type="radio" name="visibility" value="1" />
										<label for="visibli-no">No</label>
									</div>
								</div>
							</div>
							<!-- Commenting -->
							<div class="form-group d-flex justify-content-between w-25  mb-3">
								<label class="col-sm-2 col-form-label">Commenting</label>
								<div class="col-sm-10 col-md-6">
									<div>
										<input id="comment-yes" type="radio" name="commenting" value="0" checked />
										<label for="comment-yes">Yes</label>
									</div>
									<div>
										<input id="comment-no" type="radio" name="commenting" value="1" />
										<label for="comment-no">No</label>
									</div>
								</div>
							</div>
							
							<!-- Ads -->
							<div class="form-group d-flex justify-content-between w-25  mb-4">
								<label class="col-sm-2 col-form-label">Ads</label>
								<div class="col-sm-10 col-md-6">
									<div>
										<input id="ads-yes" type="radio" name="ads" value="0" checked />
										<label for="ads-yes">Yes</label>
									</div>
									<div>
										<input id="ads-no" type="radio" name="ads" value="1" />
										<label for="ads-no">No</label>
									</div>
								</div>
							</div>
							
							
							<!-- submit -->
							<div class="form-group">
								<div class="col-sm-10">
									<input type="submit" value="Add Category" class="btn btn-primary">
								</div>
							</div>
						</form>
					</div>
	<?php

	}elseif ($do == "Insert") { // Insert page
		
		if ($_SERVER['REQUEST_METHOD']== 'POST') {

				echo "<h1 class='text-center my-4 text-secondary'>Insert Categories</h1>";
				echo "<div class='container'>";

				$nameCategory	= $_POST['name'];
				$desc 			= $_POST['description'];
				$order			= $_POST['ordaring'];
				$parent			= $_POST['parent'];
				$visible 		= $_POST['visibility'];
				$comment		= $_POST['commenting'];
				$ads 			= $_POST['ads'];


					// check if User exist in database
					$check = checkItem("Name","categories",$nameCategory);

					if ($check == 1) {

							$theMsg = "<div class='alert alert-danger'>this Category is Exist</div>";
							redirectHome($theMsg);

						} else { // tmam

						$stmt = $conn->prepare("INSERT INTO categories(Name,Description,Ordaring,Parent,Visibilty,Allow_Comment,Allow_Ads) VALUES(:name,:description,:order,:parent,:visible,:comment,:ads)");

						$stmt->bindParam(':name',$nameCategory);
						$stmt->bindParam(':description',$desc);
						$stmt->bindParam(':order',$order);
						$stmt->bindParam(':parent',$parent);
						$stmt->bindParam(':visible',$visible);
						$stmt->bindParam(':comment',$comment);
						$stmt->bindParam(':ads',$ads);
						$stmt->execute();

						$theMsg = "<div class='alert alert-success'>" .$stmt->rowCount() . " Inserted</div>";

						redirectHome($theMsg,"Back");
					}


			}else{
				echo "<div class='container mt-5'>";
				$theMsg = "<div class='alert alert-danger'> you cant browse this bage directly </div>";

				redirectHome($theMsg,"Back");
				echo "</div>";
			}
			echo "</div>";

	}elseif ($do == "Edit") { // Edit Page
		
		// check userid
			$catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0 ;

			$stmt = $conn->prepare("SELECT * FROM categories WHERE ID = ?");
			$stmt->execute(array($catid));
			$cat = $stmt->fetch();
			$count = $stmt->rowCount();

			if ($count > 0) { ?>
		
				<h1 class="text-center my-4 text-secondary">Edit Categories</h1>
					
					<div class="container">
						<form class="form-horizontal offset-md-2" action="?do=Update" method="POST">
							<input type="hidden" name="catid" value="<?php echo $catid ?>" />
							<!-- Name -->
							<div class="form-group">
								<label class="col-sm-2 col-form-label">Name</label>
								<div class="col-sm-10 col-md-6">
									<input type="text" name="name" class="form-control" required="required" value="<?php echo $cat['Name'] ?>" />
								</div>
							</div>
							<!-- Description -->
							<div class="form-group">
								<label class="col-sm-2 col-form-label">Description</label>
								<div class="col-sm-10 col-md-6">
									<input type="text" name="description" class="form-control" value="<?php echo $cat['Description'] ?>" />
								</div>
							</div>
							<!-- Ordaring -->
							<div class="form-group">
								<label class="col-sm-2 col-form-label">Ordaring</label>
								<div class="col-sm-10 col-md-6">
									<input type="text" name="ordaring" class="form-control" value="<?php echo $cat['Ordaring'] ?>" />
								</div>
							</div>
							<!-- Parents -->
							<div class="form-group mb-3">
								<label class="col-sm-2 col-form-label">Parent</label>
								<div class="col-sm-10 col-md-6">
									<select name="parent-child" class="form-control select-status">
										<option value="0">None</option>
										<?php
											$parent = getAll("*","categories","WHERE Parent = 0","","ID");

											foreach ($parent as $parentcat) {
												echo "<option value='".$parentcat['ID']."'"; 
												if ($cat['Parent'] == $parentcat['ID']) {
													echo "selected";
												}

												echo ">". $parentcat['Name'] ."</option>";
											}

										?>
									</select>
								</div>
							</div>
							<!-- visibility -->
							<div class="form-group d-flex justify-content-between w-25 mb-3">
								<label class="col-sm-2 col-form-label">visible</label>
								<div class="col-sm-10 col-md-6">
									<div>
										<input id="visibli-yes" type="radio" name="visibility" value="0" <?php if($cat['Visibilty'] == 0){echo "checked";} ?> />
										<label for="visibli-yes">Yes</label>
									</div>
									<div>
										<input id="visibli-no" type="radio" name="visibility" value="1" <?php if($cat['Visibilty'] == 1){echo "checked";} ?> />
										<label for="visibli-no">No</label>
									</div>
								</div>
							</div>
							<!-- Commenting -->
							<div class="form-group d-flex justify-content-between w-25  mb-3">
								<label class="col-sm-2 col-form-label">Commenting</label>
								<div class="col-sm-10 col-md-6">
									<div>
										<input id="comment-yes" type="radio" name="commenting" value="0" <?php if($cat['Allow_Comment'] == 0){echo "checked";} ?> />
										<label for="comment-yes">Yes</label>
									</div>
									<div>
										<input id="comment-no" type="radio" name="commenting" value="1" <?php if($cat['Allow_Comment'] == 1){echo "checked";} ?> />
										<label for="comment-no">No</label>
									</div>
								</div>
							</div>
							
							<!-- Ads -->
							<div class="form-group d-flex justify-content-between w-25  mb-4">
								<label class="col-sm-2 col-form-label">Ads</label>
								<div class="col-sm-10 col-md-6">
									<div>
										<input id="ads-yes" type="radio" name="ads" value="0" <?php if($cat['Allow_Ads'] == 0){echo "checked";} ?> />
										<label for="ads-yes">Yes</label>
									</div>
									<div>
										<input id="ads-no" type="radio" name="ads" value="1" <?php if($cat['Allow_Ads'] == 1){echo "checked";} ?> />
										<label for="ads-no">No</label>
									</div>
								</div>
							</div>
							
							
							<!-- submit -->
							<div class="form-group">
								<div class="col-sm-10">
									<input type="submit" value="Add Category" class="btn btn-primary">
								</div>
							</div>
						</form>
					</div>		

		<?php
			}else{
				echo "<div class='container mt-5'>";
				$theMsg = "<div class='alert alert-danger'> there's not such ID</div>";

				redirectHome($theMsg);
			}

	}elseif ($do == "Update") { // Update Page
		
		echo "<h1 class='text-center my-4 text-secondary'>Update Categories</h1>";
			echo "<div class='container'>";

			if ($_SERVER['REQUEST_METHOD']== 'POST') {

				$id 			= $_POST['catid'];
				$name 	 		= $_POST['name'];
				$description 	= $_POST['description'];
				$ordaring 		= $_POST['ordaring'];
				$parent			= $_POST['parent-child'];
				$visible 		= $_POST['visibility'];
				$comment		= $_POST['commenting'];
				$ads 			= $_POST['ads'];



				$stmt = $conn->prepare("UPDATE categories SET Name = ?, Description = ?, Ordaring = ?, Parent = ?, Visibilty = ? , Allow_Comment = ?, Allow_Ads = ? WHERE ID = ?");
				$stmt->execute(array($name,$description,$ordaring,$parent,$visible,$comment,$ads,$id));

				$theMsg = "<div class='alert alert-success'>" .$stmt->rowCount() . "Update</div>";

				redirectHome($theMsg,"back");


			}else{
				$theMsg = "<div class='alert alert-danger'> you cant browse this bage directly </div>";
				redirectHome($theMsg);

			}
			echo "</div>";
			

	}elseif ($do == "Delete") {
		
		echo "<h1 class='text-center my-4 text-secondary'>Deleted Category</h1>";
			echo "<div class='container'>";
				// check userid
				$catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0 ;

				$checkitem = checkItem("ID","categories",$catid);

				if ($checkitem > 0) {

					$stmt = $conn->prepare("DELETE FROM categories WHERE ID = ?");
					$stmt->execute(array($catid));

					$theMsg = "<div class='alert alert-success'>" .$stmt->rowCount() . "Deleted</div>";
					redirectHome($theMsg ,"back");
				}else{
					$theMsg = "<div class='alert alert-danger'> this's ID Not Exist</div>";
					redirectHome($theMsg);
				}
			echo "</div>";
	}
	include $tpl . "footer.php";

}else{
	header("Location:index.php");
	exit();
}



ob_end_flush();

?>