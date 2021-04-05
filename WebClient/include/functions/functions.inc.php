<!--
Check that the book is a real book before adding it 
-->
<?php
	function ifBookExist($title_book,$name_author){
		$title_book = str_replace(" ", "%20", $title_book);
		$name_author = str_replace(" ", "%20", $name_author);
		$url="https://www.googleapis.com/books/v1/volumes?q=intitle:".$title_book."&inauthor:".$name_author."&langRestrict=en&maxResults=1";
		$raw = @file_get_contents($url);
		//La variable $json contient toutes les requêtes sous forme d'un tableau
		$json = json_decode($raw);
		return $json;	
	}
?>
