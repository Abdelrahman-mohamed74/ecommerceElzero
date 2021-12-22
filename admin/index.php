<?php
	session_start();
	$nonavbar  = '';
	$pagetitle = 'Login';

	if (isset($_SESSION['Username'])) {
		header('Location:dashboard.php');
	}

	include "init.php";
	
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$username = $_POST['user'];
		$password = $_POST['pass'];
		$hidepass = md5($password);

		$stmt = $conn->prepare("SELECT UserID,Username,Password FROM users WHERE Username = ? AND Password = ? AND GroupID = 1 LIMIT 1");
		$stmt->execute(array($username,$hidepass));
		$row = $stmt->fetch();
		$count = $stmt->rowCount();

		if ($count > 0) {
			$_SESSION['Username'] = $username;
			$_SESSION['ID'] 	  = $row["UserID"];
			header('Location:dashboard.php');
			exit();
		}
	}
?>

<form class="login" action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
	<h3 class="text-center">Admin</h3>
	<input class="form-control" type="text" placeholder="Username" name="user" autocomplete="off" />
	<input class="form-control" type="password" placeholder="Password" name="pass" autocomplete="new-password" />
	<input class="btn btn-primary d-block w-100" type="submit" name="" value="login" />
</form>
<div class="div"></div>


<?php include "includes/templates/footer.php"; ?>