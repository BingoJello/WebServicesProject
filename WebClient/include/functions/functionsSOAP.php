<!--
Get one or more books according to title or ID (depends on the $parameters)
-->
<?php
	function getBookSOAP($parameter,$name_parameter){
		/*Will contain the list of books */
		$array_book=array("id"=>array(),"title"=>array(),"author"=>array(),"release"=>array(),"cover"=>array(),"synopsis"=>array());
		$nbr_books=0;
		$bool=0;
		
		try{
			/*Connection to the soap service */
			$soap_client = new SoapClient("http://localhost:8080/BookResearchSoap/services/BookManagementPort?wsdl");   
			$query=array($name_parameter=>$parameter);
			if($name_parameter=="title"){
				/*Calling the getBookBytitle java method*/
				$result=$soap_client->getBookByTitle($query);
			}
			else{
				/*Calling the getBookById java method*/
				$result=$soap_client->getBookById($query);
			}
		}
		catch(SoapFault $exception){
			echo $exception->getMessage();
		}
		
		/*Case where the service did not return any book, i.e. no book was found*/
		if(count((array)$result)<1){
			$array_book['title'][0]="No result for this book";
			$array_book['author'][0]="";
			$array_book['release'][0]="";
			$array_book['cover'][0]="../img/empty_book.png";
			$array_book['synopsis'][0]="";
			
			return $array_book;	
		}
		
		/*Cases where multiple books were found */
		if(is_array($result->return)){
			if(!empty($result->return[0])){
				for($i=0;$i<count($result->return);$i++){
					$nbr_books++;
				}
			}
		}
		/*Case where only one book was found */
		else if(isset($result->return)){
			$nbr_books++;
			$bool=1;
		}
		
		/*Cases where no book was found */
		else{
			$array_book['title'][0]="No result for this book";
			$array_book['author'][0]="";
			$array_book['release'][0]="";
			$array_book['cover'][0]="../img/empty_book.png";
			$array_book['synopsis'][0]="";
			
			return $array_book;
		}
		
		/*We retrieve the characteristics of each book*/
		for($i=0;$i<$nbr_books;$i++){
			if($bool==1){
				$id_book=$result->return->id;
				$title=$result->return->title;
				$author="By ".$result->return->author;
			}
			else{
				$id_book=$result->return[$i]->id;
				$title=$result->return[$i]->title;
				$author="By ".$result->return[$i]->author;
			}
			/*We retrieve the data (cover, published date and synopsis) of the book using its id and the external REST web service "google Book api" */
			$url="https://www.googleapis.com/books/v1/volumes/".$id_book;
			$raw = @file_get_contents($url);
			//Converting the retrieved data to JSON format 
			$json = json_decode($raw);

			//get the published date of the book
			if(isset($json->volumeInfo->publishedDate)){
				$release="Released in ".$json->volumeInfo->publishedDate;
			}
			else{
				$release="";
			}
			
			//get the cover of the book
			if(isset($json->volumeInfo->imageLinks->thumbnail) AND $nbr_books<2){
				$cover=$json->volumeInfo->imageLinks->thumbnail;
			}
			else{
				$cover="../img/empty_book.png";
			}
			
			//get the synopsis of the book
			if(isset($json->volumeInfo->description)){
				$synopsis=$json->volumeInfo->description;
			}
			else{
				$synopsis="";
			}
			$array_book['id'][$i]=$id_book;
			$array_book['title'][$i]=$title;
			$array_book['author'][$i]=$author;
			$array_book['release'][$i]=$release;
			$array_book['cover'][$i]=$cover;
			$array_book['synopsis'][$i]=$synopsis;
		}
		return $array_book;
	}
?>

<!--
Add a book to the database 
-->
<?php
	function addBookSOAP($title_book,$name_author,$id_book){
		/*SOAP envelope containing the book to be added */
		$post_string='<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:q0="http://service.bookresearchsoap.etu/" 
						xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
						<soapenv:Body>
							<q0:addBook>
								<book>
									<author>'.$name_author.'</author>
									<id>'.$id_book.'</id>
									<title>'.$title_book.'</title>
								</book>
							</q0:addBook>
						</soapenv:Body>
					</soapenv:Envelope>';
		
		//SOAP headers containing additional information
		$headers = array("Content-type: text/xml;charset=\"utf-8\"", 
						 "Accept: text/xml", 
						 "Cache-Control: no-cache", 
						 "Pragma: no-cache", 
						 "SOAPAction: urn:AddBook", 
						 "Content-length: ".strlen($post_string)); 
		//Initiate cURL
		$soap_do = curl_init(); 
		curl_setopt($soap_do, CURLOPT_URL,"http://localhost:8080/BookResearchSoap/services/BookManagementPort?wsdl");   
		curl_setopt($soap_do, CURLOPT_CONNECTTIMEOUT,10); 
		curl_setopt($soap_do, CURLOPT_TIMEOUT,10); 
		curl_setopt($soap_do, CURLOPT_RETURNTRANSFER,true );
		curl_setopt($soap_do, CURLOPT_SSL_VERIFYPEER,false);  
		curl_setopt($soap_do, CURLOPT_SSL_VERIFYHOST,false); 
		//Set CURLOPT_POST to true to send a POST request.
		curl_setopt($soap_do, CURLOPT_POST,true ); 
		//Attach the soap envelope to the body of our request.
		curl_setopt($soap_do, CURLOPT_POSTFIELDS,$post_string); 
		//Attach the soap headers
		curl_setopt($soap_do, CURLOPT_HTTPHEADER,$headers); 
		//Execute the POST request 
		$result = curl_exec($soap_do);
		$err = curl_error($soap_do); 
	}
?>
