<?php

session_start();
$pagetitle = 'items';

if (isset($_SESSION['Username'])) {
	include "init.php";

	$do = isset($_GET['do']) ? $_GET['do'] : "Manage" ;

	if ($do == "Manage") { // Manage Page

			$stmt = $conn->prepare("SELECT items.*,categories.Name AS Category_Name,
								users.Username FROM items 
								INNER JOIN categories ON categories.ID = items.Cat_ID
								INNER JOIN users ON users.UserID = items.Member_ID
								ORDER BY ItemID DESC");
			$stmt->execute();
			$items = $stmt->fetchAll();
			if(!empty($items)){

			?>
			<h1 class="text-center my-4 text-secondary">Manage Items</h1>
			<div class="container">
				<div class="table-responsive">
				  <table class="main-table text-center table table-bordered">
				    <tr class="table-dark">
				    	<td>ID</td>
				    	<td>Name</td>
				    	<td>Description</td>
				    	<td>Price</td>
				    	<td>Adding Data</td>
				    	<td>Category</td>
				    	<td>User Name</td>
				    	<td>Control</td>
				    </tr>
				    <?php
						foreach ($items as $item) {
							echo "<tr>";
								echo "<td>" . $item['ItemID'] . "</td>";
								echo "<td>" . $item['Name'] . "</td>";
								echo "<td>" . $item['Description'] . "</td>";
								echo "<td>" . $item['Price'] . "</td>";
								echo "<td>" . $item["Add_Date"] ."</td>";
								echo "<td>" . $item['Category_Name'] . "</td>";
								echo "<td>" . $item['Username'] . "</td>";
								echo "<td>
										<a href='items.php?do=Edit&itemid=".$item['ItemID']."' class='btn btn-success'><i class='far fa-edit'></i>Edit</a>
										<a href='items.php?do=Delete&itemid=".$item['ItemID']."' class='btn btn-danger confirm'><i class='fas fa-user-times'></i>Delete</a>";
										if ($item['Approve'] == 0) {
											echo "<a href='items.php?do=Approve&itemid=".$item['ItemID']."' class='btn btn-info activate'><i class='fas fa-check'></i>Approve</a>";
										}

								echo "</td>";
							echo "</tr>";
						}
				    ?>
				    
				  </table>
				</div>

				<a href='items.php?do=Add' class="btn btn-primary"><i class="fa fa-plus"></i>New Member</a>
			</div>	
	<?php
		}else{
			echo "<div class='container mt-3'>
					<div class='alert alert-danger'>Here Not Record To Show</div>
					<a href='items.php?do=Add' class='btn btn-primary btn-sm'><i class='fa fa-plus'></i> New Member</a>
				</div>";
		}
	}elseif ($do == "Add") { // Add Page ?>
		
		<h1 class="text-center py-4 text-secondary">Add New Items</h1>
					
					<div class="container pb-5">
						<form class="form-horizontal offset-md-2" action="?do=Insert" method="POST">
							<!-- Name -->
							<div class="form-group">
								<label class="col-sm-2 col-form-label">Name</label>
								<div class="col-sm-10 col-md-6">
									<input type="text" name="nameitem" class="form-control" placeholder="Name of the Item" />
								</div>
							</div>
							<!-- Description -->
							<div class="form-group">
								<label class="col-sm-2 col-form-label">Description</label>
								<div class="col-sm-10 col-md-6">
									<input type="text" name="description" class="form-control" />
								</div>
							</div>
							<!-- Price -->
							<div class="form-group">
								<label class="col-sm-2 col-form-label">Price</label>
								<div class="col-sm-10 col-md-6">
									<input type="text" name="price" class="form-control" />
								</div>
							</div>
							<!-- Country Made -->
							<div class="form-group">
								<label class="col-sm-2 col-form-label">Country Made</label>
								<div class="col-sm-10 col-md-6">
									<input type="text" name="country" class="form-control" />
								</div>
							</div>
							<!-- Tags -->
							<div class="form-group">
								<label class="col-sm-2 col-form-label">Tags</label>
								<div class="col-sm-10 col-md-6">
									<input type="text" name="tags" class="form-control" />
								</div>
							</div>
							<!-- Status -->
							<div class="form-group">
								<label class="col-sm-2 col-form-label">Status</label>
								<div class="col-sm-10 col-md-6">
									<select name="status" class="form-control select-status">
										<option value="0">...</option>
										<option value="1">New</option>
										<option value="2">Like New</option>
										<option value="3">Used</option>
										<option value="4">Old</option>
									</select>
								</div>
							</div>
							<!-- members -->
							<div class="form-group">
								<label class="col-sm-2 col-form-label">Member</label>
								<div class="col-sm-10 col-md-6">
									<select name="members" class="form-control select-status">
										<option value="0">...</option>
										<?php
											$allMembers = getAll("*","users","","","UserID");

											foreach ($allMembers as $user) {

												echo "<option value='". $user['UserID'] ."'> " .$user['Username']. "</option>";
											}
										?>
									</select>
								</div>
							</div>
							<!-- Categories -->
							<div class="form-group">
								<label class="col-sm-2 col-form-label">Category</label>
								<div class="col-sm-10 col-md-6">
									<select name="category" class="form-control select-status">
										<option value="0">...</option>
										<?php
											$allCat = getAll("*","categories","WHERE Parent = 0","","ID");

											foreach ($allCat as $cat) {

												echo "<option value='". $cat['ID'] ."'> " .$cat['Name']. "</option>";
												$allChild = getAll("*","categories","WHERE Parent = {$cat['ID']}","","ID");
												foreach ($allChild as $child) {
													echo "<option value='". $child['ID'] ."'> ---" .$child['Name']. "</option>";
												}
												
											}
										?>
									</select>
								</div>
							</div>
							
							<!-- submit -->
							<div class="form-group mt-4">
								<div class="col-sm-10">
									<input type="submit" value="Add Item" class="btn btn-primary">
								</div>
							</div>
						</form>
					</div>

<?php

	}elseif ($do == "Insert") { // Inset Page
		
		if ($_SERVER['REQUEST_METHOD']== 'POST') {

				echo "<h1 class='text-center my-4 text-secondary'>Insert Items</h1>";
				echo "<div class='container'>";

				$nameItem 			= $_POST['nameitem'];
				$descriptionItem	= $_POST['description'];
				$priceItem		    = $_POST['price'];
				$countryItem	 	= $_POST['country'];
				$tags	 			= $_POST['tags'];
				$statusItem			= $_POST['status'];
				$members			= $_POST['members'];
				$category			= $_POST['category'];

				// validation form

				$formErrors =array();

				if (empty($nameItem)) {
					$formErrors[] = "Name can't be <strong> Empty </strong>";
				}
				if (empty($descriptionItem)) {
					$formErrors[] = "Description can't be <strong> Empty </strong>";
				}
				if (empty($priceItem)) {
					$formErrors[] = "Price can't be <strong> Empty </strong>";
				}
				if (empty($countryItem)) {
					$formErrors[] = "Country can't be <strong> Empty </strong>";
				}
				if ($statusItem == 0) {
					$formErrors[] = "This's Status is <strong> Required </strong>";
				}
				if ($members == 0) {
					$formErrors[] = "This's Status is <strong> Required </strong>";
				}
				if ($category == 0) {
					$formErrors[] = "This's Status is <strong> Required </strong>";
				}

				foreach ($formErrors as $error) {
					echo "<div class='alert alert-danger'>". $error . "</div>";
				}

				//check if User exist in database

				if (empty($formErrors)) {

					$stmt = $conn->prepare("INSERT INTO items(Name,Description,Price,Country_Made,Tags,Status,Add_Date,Cat_ID,Member_ID	)
					 VALUES(:zname, :zdescription, :zprice, :zcountry, :tags, :zstatus, now(),:zcat,:zmembers)");

					$stmt->bindParam(':zname',$nameItem);
					$stmt->bindParam(':zdescription',$descriptionItem);
					$stmt->bindParam(':zprice',$priceItem);
					$stmt->bindParam(':zcountry',$countryItem);
					$stmt->bindParam(':tags',$tags);
					$stmt->bindParam(':zstatus',$statusItem);
					$stmt->bindParam(':zcat',$category);
					$stmt->bindParam(':zmembers',$members);

					$stmt->execute();

					$theMsg = "<div class='alert alert-success'>" .$stmt->rowCount() . " Inserted</div>";

					redirectHome($theMsg,'back');
				}

			}else{
				echo "<div class='container mt-5'>";
				$theMsg = "<div class='alert alert-danger'> you cant browse this bage directly </div>";

				redirectHome($theMsg);
				
				echo "</div>";
			}
			echo "</div>";

	}elseif ($do == "Edit") { // Edit Page
		
			// check userid
			$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0 ;

			$stmt = $conn->prepare("SELECT * FROM items WHERE ItemID = ?");
			$stmt->execute(array($itemid));
			$item = $stmt->fetch();
			$count = $stmt->rowCount();

			if ($count > 0) { ?>
		
					<h1 class="text-center my-4 text-secondary">Edit Items</h1>
					
					<div class="container">
						<form class="form-horizontal offset-md-2" action="?do=Update" method="POST">
							<input type="hidden" name="itemsid" value="<?php echo $itemid ?>" />

							<!-- Name -->
							<div class="form-group">
								<label class="col-sm-2 col-form-label">Name</label>
								<div class="col-sm-10 col-md-6">
									<input type="text" name="name" class="form-control" value="<?php echo $item['Name'] ?>" />
								</div>
							</div>
							<!-- Description -->
							<div class="form-group">
								<label class="col-sm-2 col-form-label">Description</label>
								<div class="col-sm-10 col-md-6">
									<input type="text" name="description" class="form-control" value="<?php echo $item['Description'] ?>" />
								</div>
							</div>
							<!-- Price -->
							<div class="form-group">
								<label class="col-sm-2 col-form-label">Price</label>
								<div class="col-sm-10 col-md-6">
									<input type="text" name="price" class="form-control" value="<?php echo $item['Price'] ?>" />
								</div>
							</div>
							<!-- Country Made -->
							<div class="form-group">
								<label class="col-sm-2 col-form-label">Country Made</label>
								<div class="col-sm-10 col-md-6">
									<input type="text" name="country" class="form-control" value="<?php echo $item['Country_Made'] ?>" />
								</div>
							</div>
							<!-- Tags -->
							<div class="form-group">
								<label class="col-sm-2 col-form-label">Tags</label>
								<div class="col-sm-10 col-md-6">
									<input type="text" name="tag" class="form-control" value="<?php echo $item['Tags'] ?>" />
								</div>
							</div>
							<!-- Status -->
							<div class="form-group">
								<label class="col-sm-2 col-form-label">Status</label>
								<div class="col-sm-10 col-md-6">
									<select name="status" class="form-control select-status">
										<option value="1"<?php if( $item['Status'] == 1){echo "selected";} ?> >New</option>
										<option value="2"<?php if( $item['Status'] == 2){echo "selected";} ?> >Like New</option>
										<option value="3"<?php if( $item['Status'] == 3){echo "selected";} ?> >Used</option>
										<option value="4"<?php if( $item['Status'] == 4){echo "selected";} ?> >Old</option>
									</select>
								</div>
							</div>
							<!-- members -->
							<div class="form-group">
								<label class="col-sm-2 col-form-label">Member</label>
								<div class="col-sm-10 col-md-6">
									<select name="members" class="form-control select-status">
										<option value="0">...</option>
										<?php
											$stmt = $conn->prepare("SELECT * FROM users");
											$stmt->execute();
											$users = $stmt->fetchAll();

											foreach ($users as $user) {

											echo "<option value=' ". $user['UserID'] ." ' "; if( $item['Member_ID'] == $user['UserID']){echo "selected";} echo" > " .$user['Username']. "</option>";
											}
										?>
									</select>
								</div>
							</div>
							<!-- Categories -->
							<div class="form-group">
								<label class="col-sm-2 col-form-label">Category</label>
								<div class="col-sm-10 col-md-6">
									<select name="category" class="form-control select-status">
										<?php
											$stmt = $conn->prepare("SELECT * FROM categories");
											$stmt->execute();
											$categories = $stmt->fetchAll();

											foreach ($categories as $cat) {

											echo "<option value='". $cat['ID'] ."' "; if( $item['Cat_ID'] == $cat['ID']){echo "selected";} echo"> " .$cat['Name']. "</option>";
											}
										?>
									</select>
								</div>
							</div>
							
							<!-- submit -->
							<div class="form-group mt-4">
								<div class="col-sm-10">
									<input type="submit" value="Save Item" class="btn btn-primary">
								</div>
							</div>
						</form>

				<?php
				/////////////////////////////////////////////////////////////////

				$stmt = $conn->prepare("SELECT comments.*,users.Username As Member
				FROM comments
				INNER JOIN users ON users.UserID = comments.User_ID  WHERE Item_ID = ?");

				$stmt->execute(array($itemid));
				$rows = $stmt->fetchAll();
				if(!empty($rows)){ ?>

				<h1 class="text-center my-4 text-secondary">Manage <?php echo $item['Name'] ?> Comments</h1>
				<div class="table-responsive mt-5">
				  <table class="main-table text-center table table-bordered">
				    <tr class="table-dark">
				    	<td>Comment</td>
				    	<td>User Name</td>
				    	<td>Add Data</td>
				    	<td>Control</td>
				    </tr>
				    <?php
						foreach ($rows as $row) {
							echo "<tr>";
								echo "<td>" . $row['Comment'] . "</td>";
								echo "<td>" . $row['Member'] . "</td>";
								echo "<td>" . $row["Comment_Date"] ."</td>";
								echo "<td>
										<a href='comments.php?do=Edit&comid=".$row['C_ID']."' class='btn btn-success'><i class='far fa-edit'></i>Edit</a>
										<a href='comments.php?do=Delete&comid=".$row['C_ID']."' class='btn btn-danger confirm'><i class='fas fa-user-times'></i>Delete</a>";

										if ($row['Status'] == 0) {
											echo "<a href='comments.php?do=Approve&comid=".$row['C_ID']."' class='btn btn-info activate'><i class='fas fa-check'></i>Approve</a>";
										}
									 echo "</td>";
							echo "</tr>";
						}
				    ?>
				    
				  </table>
				</div>
			<?php } ?>
			</div>

		<?php
			}else{
				echo "<div class='container mt-5'>";
				$theMsg = "<div class='alert alert-danger'> there's not such ID</div>";

				redirectHome($theMsg);
			}

	}elseif ($do == "Update") { //Update Page
		
			echo "<h1 class='text-center my-4 text-secondary'>Update Item</h1>";
			echo "<div class='container'>";

			if ($_SERVER['REQUEST_METHOD']== 'POST') {

				$id 		= $_POST['itemsid'];
				$name 		= $_POST['name'];
				$desc 		= $_POST['description'];
				$price 		= $_POST['price'];
				$country 	= $_POST['country'];
				$tag 		= $_POST['tag'];
				$status 	= $_POST['status'];
				$members 	= $_POST['members'];
				$category 	= $_POST['category'];

				//validation form

				$formErrors =array();

				if (empty($name)) {
					$formErrors[] = "Name can't be <strong> Empty </strong>";
				}
				if (empty($desc)) {
					$formErrors[] = "Description can't be <strong> Empty </strong>";
				}
				if (empty($price)) {
					$formErrors[] = "Price can't be <strong> Empty </strong>";
				}
				if (empty($country)) {
					$formErrors[] = "Country can't be <strong> Empty </strong>";
				}
				if ($status == 0) {
					$formErrors[] = "This's Status is <strong> Required </strong>";
				}
				if ($members == 0) {
					$formErrors[] = "This's Status is <strong> Required </strong>";
				}
				if ($category == 0) {
					$formErrors[] = "This's Status is <strong> Required </strong>";
				}

				foreach ($formErrors as $error) {
					echo "<div class='alert alert-danger'>". $error . "</div>";
				}


				// check if there no error

				if (empty($formErrors)) {
					$stmt = $conn->prepare("UPDATE items SET
						 Name = ? , Description = ? , Price = ? 
						, Country_Made = ? , Tags = ? , Status = ? , Member_ID = ?
						, Cat_ID = ? WHERE ItemID = ?");

					$stmt->execute(array($name,$desc,$price,$country,$tag,$status,$members,$category,$id));

					$theMsg = "<div class='alert alert-success'>" .$stmt->rowCount() . "Update</div>";

					redirectHome($theMsg,"back");
				}


			}else{
				$theMsg = "<div class='alert alert-danger'> you cant browse this bage directly </div>";
				redirectHome($theMsg);

			}
			echo "</div>";

	}elseif ($do == "Delete") { // Delete Page

			echo "<h1 class='text-center my-4 text-secondary'>Deleted</h1>";
			echo "<div class='container'>";
				// check itemid
				$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0 ;

				$checkitem = checkItem("ItemID","items",$itemid);

				if ($checkitem > 0) {

					$stmt = $conn->prepare("DELETE FROM items WHERE ItemID = :itemid");
					$stmt->bindParam(":itemid",$itemid);
					$stmt->execute();

					$theMsg = "<div class='alert alert-success'>" .$stmt->rowCount() . "Deleted</div>";
					redirectHome($theMsg,"back");
				}else{
					$theMsg = "<div class='alert alert-danger'> this's ID Not Exist</div>";
					redirectHome($theMsg);
				}
			echo "</div>";

	}elseif ($do == "Approve") {

			echo "<h1 class='text-center my-4 text-secondary'>Approve Item</h1>";
			echo "<div class='container'>";
				// check itemid
				$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0 ;

				$checkitem = checkItem("ItemID","items",$itemid);

				if ($checkitem > 0) {

					$stmt = $conn->prepare("UPDATE items SET Approve = 1 WHERE ItemID = ?");

					$stmt->execute(array($itemid));

					$theMsg = "<div class='alert alert-success'>" .$stmt->rowCount() . "Updated</div>";
					redirectHome($theMsg,"back");
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





?>