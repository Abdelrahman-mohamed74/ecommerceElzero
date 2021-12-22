<?php
	session_start();
	$pagetitle = 'members';

	if (isset($_SESSION['Username'])) {
		include "init.php";

		$do = isset($_GET['do']) ? $_GET['do'] : "Manage";

		//start manage page

		if ($do == "Manage") { // manage page

			$query = '';
			if (isset($_GET['page']) && $_GET['page'] == 'pending') {
				
				$query = 'AND RegStatus = 0';
			}
		
			$stmt = $conn->prepare("SELECT * FROM users WHERE GroupID != 1 $query ORDER BY UserID DESC");
			$stmt->execute();

			$rows = $stmt->fetchAll();
			if (!empty($rows)) {
				
			?>
			<h1 class="text-center my-4 text-secondary">manage Members</h1>
			<div class="container">
				<div class="table-responsive">
				  <table class="main-table text-center table table-bordered">
				    <tr class="table-dark">
				    	<td>ID</td>
				    	<td>Avatar</td>
				    	<td>Username</td>
				    	<td>Fullname</td>
				    	<td>Email</td>
				    	<td>Registerd Data</td>
				    	<td>Control</td>
				    </tr>
				    <?php
						foreach ($rows as $row) {
							echo "<tr>";
								echo "<td>" . $row['UserID'] . "</td>";
								echo "<td>";
								if (empty($row['Images'])) {
									echo "no avatar";
								}else{
									echo "<img src='" . $row['Images'] . "'/>";
								}
								
								echo "</td>";
								echo "<td>" . $row['Username'] . "</td>";
								echo "<td>" . $row['Fullname'] . "</td>";
								echo "<td>" . $row['Email'] . "</td>";
								echo "<td>" . $row["Date"] ."</td>";
								echo "<td>
										<a href='members.php?do=Edit&userid=".$row['UserID']."' class='btn btn-success'><i class='far fa-edit'></i>Edit</a>
										<a href='members.php?do=Delete&userid=".$row['UserID']."' class='btn btn-danger confirm'><i class='fas fa-user-times'></i>Delete</a>";

										if ($row['RegStatus'] == 0) {
											echo "<a href='members.php?do=Activate&userid=".$row['UserID']."' class='btn btn-info activate'><i class='fas fa-check'></i>Activate</a>";
										}
									 echo "</td>";
							echo "</tr>";
						}
				    ?>
				    
				  </table>
				</div>

				<a href='members.php?do=Add' class="btn btn-primary"><i class="fa fa-plus"></i>New Member</a>
			</div>

			
		<?php
		}else{
			echo "<div class='container mt-5'>
					<div class='alert alert-danger'>Here Not Record To show</div>
					<a href='members.php?do=Add' class='btn btn-primary'><i class='fa fa-plus'></i>New Member</a>
				</div>";
		}

		}elseif ($do == "Add") { // Add page ?>

			<h1 class="text-center my-4 text-secondary">Add New Member</h1>
					
					<div class="container">
						<form class="form-horizontal" action="?do=Insert" method="POST" enctype="multipart/form-data">
							<!-- username -->
							<div class="form-group offset-md-2">
								<label class="col-sm-2 col-form-label">Username</label>
								<div class="col-sm-10 col-md-6">
									<input type="text" name="username" class="form-control" autocomplete="off" required="required" />
								</div>
							</div>
							<!-- fullname -->
							<div class="form-group offset-md-2">
								<label class="col-sm-2 col-form-label">Full Name</label>
								<div class="col-sm-10 col-md-6">
									<input type="text" name="fullname" class="form-control" required="required" />
								</div>
							</div>
							<!-- password -->
							<div class="form-group offset-md-2">
								<label class="col-sm-2 col-form-label">Password</label>
								<div class="col-sm-10 col-md-6">
									<input type="password" name="password"  class="form-control" id="input-eye" autocomplete="new-password" 
									required="required" />
									<i class="fas fa-eye" id="show-hide"></i>
								</div>
							</div>
							<!-- email -->
							<div class="form-group offset-md-2">
								<label class="col-sm-2 col-form-label">Email</label>
								<div class="col-sm-10 col-md-6">
									<input type="email" name="email" class="form-control" required="required"/>
								</div>
							</div>
							<!-- avatar -->
							<div class="form-group mb-3 offset-md-2">
								<label class="col-sm-2 col-form-label">Avatar</label>
								<div class="col-sm-10 col-md-6">
									<input type="file" name="avatar" class="form-control" required="required"/>
								</div>
							</div>
							
							<!-- submit -->
							<div class="form-group offset-md-2">
								<div class="col-sm-10">
									<input type="submit" value="Add Member" class="btn btn-primary">
								</div>
							</div>
						</form>
					</div>

		<?php 
		}elseif ($do == 'Insert') { // Insert Member Page

			if ($_SERVER['REQUEST_METHOD']== 'POST') {

				echo "<h1 class='text-center my-4 text-secondary'>Insert</h1>";
				echo "<div class='container'>";

				// apload Avatar
				$avatarName  = rand(0,100000) . "_" . $_FILES['avatar']['name'];
				$avatarType  = $_FILES['avatar']['type'];
				$avatarTmp   = $_FILES['avatar']['tmp_name'];
				$avatarSize  = $_FILES['avatar']['size'];

				$allowedExtention = array("jpg","jpeg","png","gif");
				$avatarExtention  = strtolower(end(explode('.', $avatarName)));
				
				/////////////////////////////////////

				$user 		= $_POST['username'];
				$name 		= $_POST['fullname'];
				$pass		= $_POST['password'];
				$passhiden	= md5($_POST['password']);
				$email 		= $_POST['email'];

				//validation form

				$formErrors =array();

				if (strlen($user) < 4 || strlen($user) > 20) {
					$formErrors[] = "Username can't be less than <strong> 4 caracters </strong> OR more than 20";
				}

				if (empty($user)) {
					$formErrors[] = "Username can't be <strong> Empty </strong>";
				}

				if (empty($name)) {
					$formErrors[] = "Fullname can't be <strong> Empty </strong>";
				}

				if (empty($pass)) {
					$formErrors[] = "password can't be <strong> Empty </strong>";
				}

				if (empty($email)) {
					$formErrors[] = "Email can't be <strong> Empty </strong>";
				}

				if (!empty($avatarName) && !in_array($avatarExtention , $allowedExtention)) {
					$formErrors[] = "This's Extenion is Not <strong> Allowed </strong>";
				}

				if (empty($avatarName)) {
					$formErrors[] = "This's Avatar is <strong> Required </strong>";
				}

				if ($avatarSize > 4000000) {
					$formErrors[] = "Avatar Cant Be Larger Than <strong>4 MB</strong>";
				}

				foreach ($formErrors as $error) {
					echo "<div class='alert alert-danger'>". $error . "</div>";
				}

				// // check if there no error 

				if (empty($formErrors)) {
					 
					move_uploaded_file($avatarTmp , $avatarName);

				// check if User exist in database
					
					$check = checkItem("Username","users",$user);
					if ($check == 1) {

							$theMsg = "<div class='alert alert-danger'>this User is Exist</div>";
							redirectHome($theMsg);

						} else {
						$stmt = $conn->prepare("INSERT INTO users(Username,Fullname,Password,Email,RegStatus,Date,Images) VALUES(:username,:fullname,:password,:email,1,now(),:img)");
						$stmt->bindParam(':username',$user);
						$stmt->bindParam(':fullname',$name);
						$stmt->bindParam(':password',$passhiden);
						$stmt->bindParam(':email',$email);
						$stmt->bindParam(':img',$avatarName);
						$stmt->execute();

						$theMsg = "<div class='alert alert-success'>" .$stmt->rowCount() . " Inserted</div>";

						redirectHome($theMsg,"back");
					}
				 }

			}else{
				echo "<div class='container mt-5'>";
				$theMsg = "<div class='alert alert-danger'> you cant browse this bage directly </div>";

				redirectHome($theMsg,"Back");
				echo "</div>";
			}
			echo "</div>";

		}elseif($do == "Edit"){ // Edit page

			// check userid
			$userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0 ;

			$stmt = $conn->prepare("SELECT * FROM users WHERE UserID = ? LIMIT 1");
			$stmt->execute(array($userid));
			$row = $stmt->fetch();
			$count = $stmt->rowCount();

			if ($count > 0) { ?>
		
					<h1 class="text-center my-4 text-secondary">Edit Member</h1>
					
					<div class="container">
						<form class="form-horizontal" action="?do=Update" method="POST">
							<input type="hidden" name="userid" value="<?php echo $userid ?>" />
							<!-- username -->
							<div class="form-group offset-md-2">
								<label class="col-sm-2 col-form-label">Username</label>
								<div class="col-sm-10 col-md-6">
									<input type="text" name="username" value="<?php echo $row['Username']?>" class="form-control" autocomplete="off" required="required" />
								</div>
							</div>
							<!-- fullname -->
							<div class="form-group offset-md-2">
								<label class="col-sm-2 col-form-label">Full Name</label>
								<div class="col-sm-10 col-md-6">
									<input type="text" name="fullname" value="<?php echo $row['Fullname']?>" class="form-control" required="required" />
								</div>
							</div>
							<!-- password -->
							<div class="form-group offset-md-2">
								<label class="col-sm-2 col-form-label">Password</label>
								<div class="col-sm-10 col-md-6">
									<input type="hidden" name="oldpassword" value="<?php echo $row['Password']?>" />
									<input type="password" name="newpassword" class="form-control" autocomplete="new-password" />
								</div>
							</div>
							<!-- email -->
							<div class="form-group mb-3 offset-md-2">
								<label class="col-sm-2 col-form-label">Email</label>
								<div class="col-sm-10 col-md-6">
									<input type="email" name="email" value="<?php echo $row['Email']?>" class="form-control" required="required"/>
								</div>
							</div>
							
							<!-- submit -->
							<div class="form-group offset-md-2">
								<div class="col-sm-10">
									<input type="submit" value="Save" class="btn btn-primary">
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

		}elseif ($do == "Update") { //Update page

			echo "<h1 class='text-center my-4 text-secondary'>Update</h1>";
			echo "<div class='container'>";

			if ($_SERVER['REQUEST_METHOD']== 'POST') {

				$id 	= $_POST['userid'];
				$user 	= $_POST['username'];
				$name 	= $_POST['fullname'];
				$email 	= $_POST['email'];

				//password
				$PAssword = empty($_POST['newpassword']) ? $_POST['oldpassword'] : md5($_POST['newpassword']);
				//validation form

				$formErrors =array();

				if (strlen($user) < 4 || strlen($user) > 20) {
					$formErrors[] = "Username can't be less than <strong> 4 caracters </strong> OR more than 20";
				}

				if (empty($user)) {
					$formErrors[] = "Username can't be <strong> Empty </strong>";
				}
				if (empty($name)) {
					$formErrors[] = "Fullname can't be <strong> Empty </strong>";
				}
				if (empty($email)) {
					$formErrors[] = "Email can't be <strong> Empty </strong>";
				}
				foreach ($formErrors as $error) {
					echo "<div class='alert alert-danger'>". $error . "</div>";
				}

				// check if there no error

				if (empty($formErrors)) {

					$stmt = $conn->prepare("SELECT * FROM users WHERE Username = ? AND UserID != ?");
					$stmt->execute(array($user,$id));
					$count = $stmt->rowCount();
					if ($count == 1) {
						echo "<div class='alert alert-danger'> this's User Is Exist </div>";
						redirectHome($theMsg,"back");
					}else{
					
						$stmt = $conn->prepare("UPDATE users SET Username = ?, Fullname = ?, Email = ?, Password = ? WHERE UserID = ?");
						$stmt->execute(array($user,$name,$email,$PAssword,$id));

						$theMsg = "<div class='alert alert-success'>" .$stmt->rowCount() . "Update</div>";

						redirectHome($theMsg,"back");
					}
				}


			}else{
				$theMsg = "<div class='alert alert-danger'> you cant browse this bage directly </div>";
				redirectHome($theMsg);

			}
			echo "</div>";
			
		}elseif ($do == "Delete") { // Delete Page

			echo "<h1 class='text-center my-4 text-secondary'>Deleted</h1>";
			echo "<div class='container'>";
				// check userid
				$userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0 ;

				$checkitem = checkItem("UserID","users",$userid);

				if ($checkitem > 0) {

					$stmt = $conn->prepare("DELETE FROM users WHERE UserID = :userid");
					$stmt->bindParam(":userid",$userid);
					$stmt->execute();

					$theMsg = "<div class='alert alert-success'>" .$stmt->rowCount() . "Deleted</div>";
					redirectHome($theMsg,"back");
				}else{
					$theMsg = "<div class='alert alert-danger'> this's ID Not Exist</div>";
					redirectHome($theMsg);
				}
			echo "</div>";
			
		}elseif ($do == "Activate") { // Activate Page

			echo "<h1 class='text-center my-4 text-secondary'>Activate</h1>";
			echo "<div class='container'>";
				// check userid
				$userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0 ;

				$checkitem = checkItem("UserID","users",$userid);

				if ($checkitem > 0) {

					$stmt = $conn->prepare("UPDATE users SET RegStatus = 1 WHERE UserID = ?");

					$stmt->execute(array($userid));

					$theMsg = "<div class='alert alert-success'>" .$stmt->rowCount() . "Updated</div>";
					redirectHome($theMsg);
				}else{
					$theMsg = "<div class='alert alert-danger'> this's ID Not Exist</div>";
					redirectHome($theMsg);
				}
			echo "</div>";
		}

		include $tpl ."footer.php"; 
	}else{
		
		header('Location:index.php');
		exit();
	}

?>

