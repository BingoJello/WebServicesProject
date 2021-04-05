<?php	
	include('./include/functions/functions.inc.php');
	include('./include/functions/functionsREST.php');
	include('./include/functions/functionsSOAP.php');
?>

<!DOCTYPE html>
<html lang="fr">
<head>
	<title>Index-BooksResearch</title>
	<meta charset="utf-8"/>
	<meta name="author" content="arthur mimouni" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
	<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
	<link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
	<link rel="stylesheet" type ="text/css" href="./css/style_index.css"/>
	<link rel="stylesheet" type ="text/css" href="./css/style_header.css"/>
	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css2?family=Open+Sans+Condensed:wght@300&display=swap" rel="stylesheet"> 
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.10.2/css/all.css">
</head>

<body>	
<div class="content">
	<?php include("./include/headers/header_index.html")?>
		
	<div class="main-container">
		<div class="looking-book-block">
			<h1>Find a book</h1>
			<h2 class="description-research">This website contains information on a multitude of books. 
				So try to search for a particular book by typing his title on the following search bar.
			</h2>
			<form action="./research/book.php" method="POST">
				<div class="Wrapper">
					<div class="Input">
						<input type="text" id="input" name="title_book" class="Input-text" placeholder="Title of the book">
						<label for="input" class="Input-label">Title of the book</label>
					</div>
				</div>
				<table style="margin:-40px auto">
					<tr>
						<td><input type="radio" id="rest" name="service" value="rest"checked>
							<label style="color:white" for="rest">REST</label>
						</td>
						<td style="padding-left:40px"><input type="radio" id="soap" name="service" value="soap">
							<label style="color:white" for="soap">SOAP</label>
						</td>
					</tr>
				</table>
				<table style="margin:80px auto">
					<tr>
						<td><button type="submit" class="btn btn-primary btn-lg btn-danger">SEARCH</button></td>
					</tr>
				</table>
			</form>
			<p style="height:5px"></p>
		</div>
		
		<div class="add-book-block">
		<h1>Add new book</h1>
			<h2 class="description-research">Can't find a specific book ? If it does not exist in our database, 
				you can add it by specifying several information.
			</h2>
				<table style="margin:35px auto">
					<tr><td><button id="add-btn" class="btn btn-primary btn-lg">ADD A BOOK</button></td></tr>
				</table>
			<p style="height:2em"></p>
		</div>
	</div>
</div>

	<?php include("./include/footers/footer_index.html") ?>
		
	<?php 	
		if((isset($_POST['validation'])) AND (!empty($_POST['validation']))){
			$json=ifBookExist($_POST['title_book'],$_POST['firstname_author']." ".$_POST['lastname_author']);
			if($json->totalItems>0){
				$title_book=$json->items[0]->volumeInfo->title;
				$title_book_tmp=str_replace("'", "%",$json->items[0]->volumeInfo->title);
				if(isset($json->items[0]->volumeInfo->authors[0])){
					$name_author=str_replace("'", "%",$json->items[0]->volumeInfo->authors[0]);
				}
				else{
					$name_author="No author";
				}
				$id_book=$json->items[0]->id;
				if(isset($json->items[0]->volumeInfo->imageLinks->thumbnail)){
					$cover=$json->items[0]->volumeInfo->imageLinks->thumbnail;
				}
				else{
					$cover="./img/empty_book.png";
				}

				echo "<div class='validation-book-modal'>";
					echo "<div class='validation-book-container'>";
						echo "<div class='close'>+</div>";
							$service=$_POST['service'];
							if($service=="rest"){
								$already_add=getBookREST($id_book,"id");
							}
							else{
								$already_add=getBookSOAP($id_book,"id");
							}
				
							if($already_add['title'][0]!="No result for this book"){
								echo "<h3 style='color:black;margin-top:0.5em;'>This book is already registered in our database</h3>";
								echo "<img src=$cover height='200' width='150' alt='image book'/>";
								echo "<h4 style='padding-top:5px'>".$title_book."</h4>";
								echo "<h5 style='padding-top:5px'>By ".$name_author."</h5>";
							}	
							else{
								echo "<h3 style='color:black;margin-top:0.5em;'>Is it this book ?</h3>";
								echo "<img src=$cover height='200' width='150' alt='image book'/>";
								echo "<form id='myform' method='POST' action='./research/book.php'>";
									echo "<input id='title_book' name='title_book' type='hidden' value='".$title_book_tmp."'>";
									echo "<input id='id_book' name='id_book' type='hidden' value=".$id_book.">";
									echo "<input id='name_author' name='name_author' type='hidden' value='".$name_author."'>";
									echo "<input id='add-book' name='add-book' type='hidden' value='yes'>";
									echo "<input id='service' name='service' type='hidden' value=$service>";
									echo "<h4 style='padding-top:5px'>".$title_book."</h4>";
									echo "<h5 style='padding-top:5px'>By ".$name_author."</h5>";
									echo "<input type='submit' class='button-submit'value='SUBMIT'>";
								echo"</form>";
							}
					echo "</div>";
				echo "</div>";
			}
			else{
				echo "<div class='validation-book-modal'>";
					echo "<div class='validation-book-container'>";
						echo "<div class='close'>+</div>";
							echo "<h3 style='color:black;margin-top:0.5em;'>No books found</h3>";
							echo "<img src='./img/empty_book.png' height='200' width='150' alt='image book'/>";
					echo "</div>";
				echo "</div>";
			}
		}
	?>
	
	<div class="add-book-modal">
		<div class="add-book-container">
			<div class="close">+</div>
			<h3>Add detail of the book</h3>
			<hr style="margin-right:7.5em;margin-left:7.5em;margin-top:0.5em">
			
			<form action="./index.php" method="POST"  enctype="multipart/form-data">				
				<table style="margin:0 auto;width:30em">
					<tr>
						<td style="padding-top:1em"><label id="title-label" for="title">Title*</label></td>
					</tr>
					<tr>
						<td><input class="input-add" type="text" id="title" name="title_book" placeholder="Title book" required /></td>
					</tr>
					<tr>
						<td style="padding-top:2em"><label id="firstname_author-label" for="firstname_author">First name author*</label></td>
					</tr>
					<tr>
						<td><input class="input-add" type="text" id="firstname_author" name="firstname_author" placeholder="First name author" required /></td>
					</tr>
					<tr>
						<td style="padding-top:1em"><label id="lastname_author-label" for="lastname_author">Last name author*</label></td>
					</tr>
					<tr>
						<td><input class="input-add" type="text" id="lastname_author" name="lastname_author" placeholder="Last name author" required /></td>
					</tr>
					<tr>
						<td><input type="radio" id="rest" name="service" value="rest"checked>
							<label for="rest">REST</label>
						</td>
					</tr>
					<tr>
						<td><input type="radio" id="soap" name="service" value="soap">
							<label for="soap">SOAP</label>
						</td>
					</tr>
					<input id='validation' name='validation' type='hidden' value="yes">
					<tr>
						<td><input type="submit" id="validation-btn" class="button-submit" value="SUBMIT"></td>
					</tr>
				</table>
			</form>
		</div>
	</div>
	
	<script>
		if(document.getElementById('add-btn')!=null){
			document.getElementById('add-btn').addEventListener('click',
				function(){
					document.querySelector('.add-book-modal').style.display='flex';
				}
			);
		}
		document.querySelector('.close').addEventListener('click',
			function(){
				document.querySelector('.add-book-modal').style.display='none';
				document.querySelector('.validation-book-modal').style.display='none';
			}
		);
	</script>
	
</body>
</html>