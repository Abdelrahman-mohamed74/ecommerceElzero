<?php

$do = isset($_GET['do']) ? $_GET['do'] : "manage";


if ($do == "manage") {

	echo "here manage";

}elseif ($do == "Add") {

	echo "here Add";
}else{

	echo "Error";
}




?>