<?php
	session_start();
	$pagetitle = 'Login';

	if (isset($_SESSION['client'])) {
		header('Location:index.php');
	}

 	include "init.php";

 	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

 		if (isset($_POST['login'])) {
 		
			$client   = $_POST['username'];
			$pass 	  = $_POST['password'];
			$hidepass = md5($pass);

			$stmt = $conn->prepare("SELECT UserID,Username,Password FROM users WHERE Username = ? AND Password = ?");
			$stmt->execute(array($client,$hidepass));

			$get = $stmt->fetch();

			$count = $stmt->rowCount();

			if ($count > 0) {

				$_SESSION['client'] = $client;
				$_SESSION['userid']	= $get['UserID'];

				header('Location:index.php');
				exit();
			}

		}else{

			$arrayError = array();

			$username 	= $_POST['username'];
			$password 	= $_POST['password'];
			$password2 	= $_POST['password2'];
			$email 		= $_POST['email'];

			// Username Validate

			if (isset($username )) {

				$filterUsername = filter_var($username ,FILTER_SANITIZE_STRING);

				if (strlen($filterUsername) < 3) {

                	$arrayError[] = "Username must be larger than <strong>3 </strong>characters";
		        }
			}

			// Password Validate

			if (isset($password) && isset($password2)) {

				if (empty($password)) {
					
					$arrayError[] = "Sorry your password Empty";
				}

				if (md5($password) !== md5($password2)) {
					$arrayError[] = "Sorry your password is Not Match";
				}
			}

			// Email Validate

			if (isset($email)) {

				$filterEmail = filter_var($email,FILTER_SANITIZE_EMAIL);

				if (filter_var($filterEmail,FILTER_VALIDATE_EMAIL) != true) {
					
					$arrayError[] = "Your Email Not Valid";
				}
				
		    }

		    	// check if there no error 

			if (empty($arrayError)) {
				
					
				$check = checkItem("Username","users",$username);
				if ($check == 1) {

					$arrayError[] = "This's User is Exist";
					

				}
				else {
					$stmt = $conn->prepare("INSERT INTO users(Username,Password,Email,RegStatus,Date) VALUES(:username,:password,:email,0,now())");
					$stmt->bindParam(':username',$username);
					$stmt->bindParam(':password',md5($password));
					$stmt->bindParam(':email',$email);
					$stmt->execute();
					echo "<div class='alert alert-success mx-auto'>
					 Success</div>";

				}
			}






		}
	}
 ?>

	<div class="container form">

		<h3 class="text-center py-4">
			<span class="active" data-class="login">Login</span> | 
			<span data-class="signup">Signup</span>
		</h3>
			<!-- login -->
		<form class="login" action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">

			<div class="form-floating">
			  	<input type="text" class="form-control" name="username" id="floatingInput" placeholder="name@example.com" autocomplete="off" />
			  	<label for="floatingInput">UserName</label>
			</div>

			<div class="form-floating my-3">
			  	<input type="password" class="form-control" name="password" id="floatingPassword" placeholder="Password" autocomplete="new-password" />
			  	<label for="floatingPassword">Password</label>
			</div>

			<input class="btn btn-primary w-100" name="login" type="submit" value="login" />

		</form>

			<!-- Signup -->
		<form class="signup" action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">

			
			<div class="form-floating">
			  	<input type="text" class="form-control" name="username" id="floatingInput" placeholder="Username" autocomplete="off" />
			  	<label for="floatingInput">UserName</label>
			</div>

			<div class="form-floating my-3">
			  	<input type="password" class="form-control" name="password" id="floatingPassword" placeholder="Password" autocomplete="new-password" />
			  	<label for="floatingPassword">Password</label>
			</div>

			<div class="form-floating">
			  	<input type="password" class="form-control" name="password2" id="floatingPassword" placeholder="Confirm Password" autocomplete="new-password" />
			  	<label for="floatingPassword">Confirm Password</label>
			</div>

			<div class="form-floating">
			  	<input type="email" class="form-control my-3" name="email" id="floatingInput" placeholder="Email" autocomplete="off" />
			  	<label for="floatingInput">Email Address</label>
			</div>
			

			<input class="btn btn-primary w-100" name="signup" type="submit" value="signup" />

			<?php if (!empty($arrayError)) { ?>

                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php
                        foreach ($arrayError as $error) {
                        echo "<p class='mt-3'>". $error . "</p>";
                        }
                    ?>
                </div>
            <?php }?>

		</form>
			
	</div>







<?php 
	include $tpl ."footer.php";

 ?>