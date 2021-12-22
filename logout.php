<?php

session_start();
session_unset();   //clear data
session_destroy();

header("Location:index.php");
exit();


?>