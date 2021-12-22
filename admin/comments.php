<?php
	session_start();
	$pagetitle = 'comments';

	if (isset($_SESSION['Username'])) {
		include "init.php";

		$do = isset($_GET['do']) ? $_GET['do'] : "Manage"; 
		//start manage page

		if ($do == "Manage") { // manage page

			$stmt = $conn->prepare("SELECT comments.*,items.Name AS Item_Name,
				users.Username As Member FROM comments
				INNER JOIN items ON items.ItemID = comments.Item_ID
				INNER JOIN users ON users.UserID = comments.User_ID
				ORDER BY C_ID DESC");

			$stmt->execute();

			$comments = $stmt->fetchAll();
			if(!empty($comments)){
			?>
			<h1 class="text-center my-4 text-secondary">manage Members</h1>
			<div class="container">
				<div class="table-responsive">
				  <table class="main-table text-center table table-bordered">
				    <tr class="table-dark">
				    	<td>ID</td>
				    	<td>Comment</td>
				    	<td>Item Name</td>
				    	<td>User Name</td>
				    	<td>Add Data</td>
				    	<td>Control</td>
				    </tr>
				    <?php
						foreach ($comments as $comment) {
							echo "<tr>";
								echo "<td>" . $comment['C_ID'] . "</td>";
								echo "<td>" . $comment['Comment'] . "</td>";
								echo "<td>" . $comment['Item_Name'] . "</td>";
								echo "<td>" . $comment['Member'] . "</td>";
								echo "<td>" . $comment["Comment_Date"] ."</td>";
								echo "<td>
										<a href='comments.php?do=Edit&comid=".$comment['C_ID']."' class='btn btn-success'><i class='far fa-edit'></i>Edit</a>
										<a href='comments.php?do=Delete&comid=".$comment['C_ID']."' class='btn btn-danger confirm'><i class='fas fa-user-times'></i>Delete</a>";

										if ($comment['Status'] == 0) {
											echo "<a href='comments.php?do=Approve&comid=".$comment['C_ID']."' class='btn btn-info activate'><i class='fas fa-check'></i>Approve</a>";
										}
									 echo "</td>";
							echo "</tr>";
						}
				    ?>
				    
				  </table>
				</div>
			</div>

		<?php
			}else{
				echo "<div class='container mt-3'>
						<div class='alert alert-danger'>Here Not Record To show</div>
					</div>";
			}
		}elseif($do == "Edit"){ // Edit page

			// check comid
			$comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0 ;

			$stmt = $conn->prepare("SELECT * FROM comments WHERE C_ID = ?");
			$stmt->execute(array($comid));
			$row = $stmt->fetch();
			$count = $stmt->rowCount();

			if ($count > 0) { ?>
		
					<h1 class="text-center my-4 text-secondary">Edit Comment</h1>
					
					<div class="container">
						<form class="form-horizontal" action="?do=Update" method="POST">
							<input type="hidden" name="comid" value="<?php echo $comid ?>" />
							<!-- username -->
							<div class="form-group offset-md-2">
								<label class="col-sm-2 col-form-label">Comment</label>
								<div class="col-sm-10 col-md-6">
									<textarea class="form-control" name="comment"><?php echo $row['Comment']?></textarea>
								</div>
							</div>
							
							<!-- submit -->
							<div class="form-group offset-md-2">
								<div class="col-sm-10">
									<input type="submit" value="Save" class="btn btn-primary mt-3">
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

			echo "<h1 class='text-center my-4 text-secondary'>Update Comment</h1>";
			echo "<div class='container'>";

			if ($_SERVER['REQUEST_METHOD']== 'POST') {

				$id 		= $_POST['comid'];
				$comment 	= $_POST['comment'];

				$stmt = $conn->prepare("UPDATE comments SET Comment = ? WHERE C_ID = ?");
				$stmt->execute(array($comment,$id));

				$theMsg = "<div class='alert alert-success'>" .$stmt->rowCount() . " Update</div>";

				redirectHome($theMsg,"back");
				
			}else{
				$theMsg = "<div class='alert alert-danger'> you cant browse this bage directly </div>";
				redirectHome($theMsg);

			}
			echo "</div>";
			
		}elseif ($do == "Delete") { // Delete Page

			echo "<h1 class='text-center my-4 text-secondary'>Deleted Comment</h1>";
			echo "<div class='container'>";
				// check userid
				$comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0 ;

				$checkitem = checkItem("C_ID","comments",$comid);

				if ($checkitem > 0) {

					$stmt = $conn->prepare("DELETE FROM comments WHERE C_ID = :comid");
					$stmt->bindParam(":comid",$comid);
					$stmt->execute();

					$theMsg = "<div class='alert alert-success'>" .$stmt->rowCount() . "Deleted</div>";
					redirectHome($theMsg,"back");
				}else{
					$theMsg = "<div class='alert alert-danger'> this's ID Not Exist</div>";
					redirectHome($theMsg);
				}
			echo "</div>";
			
		}elseif ($do == "Approve") { // Approve Page

			echo "<h1 class='text-center my-4 text-secondary'>Approve Comment</h1>";
			echo "<div class='container'>";
				// check userid
				$comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0 ;

				$checkitem = checkItem("C_ID","comments",$comid);

				if ($checkitem > 0) {

					$stmt = $conn->prepare("UPDATE comments SET Status = 1 WHERE C_ID = ?");

					$stmt->execute(array($comid));

					$theMsg = "<div class='alert alert-success'>" .$stmt->rowCount() . " Approved</div>";
					redirectHome($theMsg,"back");
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