<!--
Get one or more books according to title or ID (depends on the $parameters parameter
-->
<?php
	function getBookREST($parameter,$name_parameter){
		/*Will contain the list of books */
		$array_book=array("id"=>array(),"title"=>array(),"author"=>array(),"release"=>array(),"cover"=>array(),"synopsis"=>array());
		
		if($name_parameter=="title"){
			/*GET request to retrieve books with the desired title */
			$url="http://localhost:8080/BookResearchRest/rest/book/title/".$parameter;
		}
		else{
			/*GET request to retrieve the book with the specific id */
			$url="http://localhost:8080/BookResearchRest/rest/book/id/".$parameter;
		}
		
		$xml = simplexml_load_file($url);
		
		if(isset($xml->book[0]->title) AND (!empty($xml->book[0]->title))){
			for($i=0;$i<count($xml);$i++){
				/*We retrieve the data of the book using its id and the external REST web service "google Book api" */
				$url="https://www.googleapis.com/books/v1/volumes/".$xml->book[$i]->id;
				$raw = @file_get_contents($url);
				//Converting the retrieved data to JSON format 
				$json = json_decode($raw);
				$title=$xml->book[$i]->title;
				$id=$xml->book[$i]->id;
				$author="By ".$xml->book[$i]->author;
				
				if(isset($json->volumeInfo->publishedDate)){
					$release="Released in ".$json->volumeInfo->publishedDate;
				}
				else{
					$release="";
				}
				if(isset($json->volumeInfo->imageLinks->thumbnail) AND count($xml)<2){
					$cover=$json->volumeInfo->imageLinks->thumbnail;
				}
				else{
					$cover="../img/empty_book.png";
				}
				if(isset($json->volumeInfo->description)){
					$synopsis=$json->volumeInfo->description;
				}
				else{
					$synopsis="";
				}
				$array_book['id'][$i]=$id;
				$array_book['title'][$i]=$title;
				$array_book['author'][$i]=$author;
				$array_book['release'][$i]=$release;
				$array_book['cover'][$i]=$cover;
				$array_book['synopsis'][$i]=$synopsis;
			}
			
		}
		else{
			$id="";
			$title="No result for this book";
			$author="";
			$synopsis="";
			$release="";
			$cover="../img/empty_book.png";
			
			$array_book['title'][0]=$title;
			$array_book['author'][0]=$author;
			$array_book['release'][0]=$release;
			$array_book['cover'][0]=$cover;
			$array_book['synopsis'][0]=$synopsis;
		}
		
		return $array_book;
	}
?>

<!--
Add a book to the database 
-->
<?php
	function addBookREST($title_book,$name_author,$id_book){
		/*Creation of the xml file that will contain the book to add */
		$xml=simplexml_load_file("../xml/bookREST.xml");
		$xml->id = $id_book; // Inscription des propriétés
		$xml->title=$title_book;
		$xml->author=$name_author;
		$xml->asXML("../xml/bookREST.xml");
		$xml = file_get_contents('../xml/bookREST.xml');
		
		/*POST request*/
		$url = 'http://localhost:8080/BookResearchRest/rest/book';
		
		//Initiate cURL
		$curl = curl_init($url);
		
		//Set the Content-Type to application/xml.
		curl_setopt ($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/xml"));

		//Set CURLOPT_POST to true to send a POST request.
		curl_setopt($curl, CURLOPT_POST, true);
	
		//Attach the XML string to the body of our request.
		curl_setopt($curl, CURLOPT_POSTFIELDS, $xml);

		//Tell cURL that we want the response to be returned as
		//a string instead of being dumped to the output.
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

		//Execute the POST request and send our XML.
		$result = curl_exec($curl);

		//Do some basic error checking.
		if(curl_errno($curl)){
			throw new Exception(curl_error($curl));
		}
		
		//Close the cURL handle.
		curl_close($curl);
	}
?>
