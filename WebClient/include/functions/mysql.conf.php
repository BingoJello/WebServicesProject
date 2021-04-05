<!--
Connexion to database
-->
<?php
	try{
		$myPDO = new PDO('mysql:host=localhost;dbname=books_research;charset=utf8', 'root', 'A123456*');
		return $myPDO;
	}catch(PDOException $e){
		echo $e->getMessage();
	}
?>
	