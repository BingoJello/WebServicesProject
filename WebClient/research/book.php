<?php
	include('../include/functions/functions.inc.php');
	include('../include/functions/functionsREST.php');
	include('../include/functions/functionsSOAP.php');
	
	$service="";
	
	/*If the user has requested the addition of a book then we add this book in the database and we display this book using the REST or SOAP method */
	if((isset($_POST['add-book'])) AND ((!empty($_POST['add-book'])))){
		$title_book=str_replace("%", "'",$_POST['title_book']);
		$name_author=str_replace("%", "'",$_POST['name_author']);
		$id_book=$_POST['id_book'];
		if((isset($_POST['service'])) AND ((!empty($_POST['service'])))){
			$service=$_POST['service'];
			if($service=="rest"){
				addBookREST($title_book,$name_author,$id_book);
				$array_book=getBookREST($id_book,"id");
			}
			else{
				addBookSOAP($title_book,$name_author,$id_book);
				$array_book=getBookSOAP($id_book,"id");
			}
		}
	}
	/*Display of the book thanks to its id*/
	else if((isset($_GET['id'])) AND ((!empty($_GET['id'])))){
		if((isset($_GET['service'])) AND ((!empty($_GET['service'])))){
			$service=$_GET['service'];
			if($service=="rest"){
				$array_book=getBookREST($_GET['id'],"id");
			}
			else{
				$array_book=getBookSOAP($_GET['id'],"id");
			}
		}
	}
	
	/*Display of the book thanks to its title*/
	else if((isset($_POST['title_book'])) AND ((!empty($_POST['title_book'])))){
		if((isset($_POST['service'])) AND ((!empty($_POST['service'])))){
			$service=$_POST['service'];
			if($service=="rest"){
				$array_book=getBookREST($_POST['title_book'],"title");
			}
			else{
				$array_book=getBookSOAP($_POST['title_book'],"title");
			}
		}
	}
	else{
		$array_book=array("id"=>array(),"title"=>array(),"author"=>array(),"release"=>array(),"cover"=>array(),"synopsis"=>array());
		
		$array_book['title'][0]="No result for this book";
		$array_book['author'][0]="";
		$array_book['release'][0]="";
		$array_book['cover'][0]="../img/empty_book.png";
		$array_book['synopsis'][0]="";
	}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
	<title>Research-BooksResearch</title>
	<meta charset="utf-8"/>
	<meta name="author" content="arthur mimouni" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
	<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
	<link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
	<link rel="stylesheet" type ="text/css" href="../css/style_book.css"/>
	<link rel="stylesheet" type ="text/css" href="../css/style_header.css"/>
	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css2?family=Open+Sans+Condensed:wght@300&display=swap" rel="stylesheet"> 
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.10.2/css/all.css">
</head>

<body>	
<div class="content">
	<?php include("../include/headers/header_book.html")?>
		
	<div class="main-container">
		<div class="research-book-block">
			<h1>Research</h1>
			<form action="./book.php" method="POST">
				<div class="Wrapper">
					<div class="Input">
						<table style="width:100%;margin-top:-10px">
							<tr>
								<td>
									<input type="text" id="input" name="title_book" class="Input-text" placeholder="Title of the book">
								</td>
								<td style="padding-top:5.5px">
									<label>
										<input id='service' name='service' type='hidden' value=<?php echo $service;?>>
										<button class="btn btn-secondary button-research" type="submit">
											<i class="fa fa-search"></i>
										</button>	
									</label>
								</td>
							</tr>
						</table>
					</div>
				</div>
			</form>
			<p style="height:2em"></p>
		</div>
		
		<div class="book-block">
			<div class="cover-book-block">
				<img width="200px" height="260px" src=<?php echo $array_book['cover'][0];?> alt="cover-book">
			</div>
			<div class="detail-book-block">
				<?php
					/*Book display */
					if(count($array_book['title'])>1){
						$nbr=count($array_book['title']);
						echo "<h3>".count($array_book['title'])." results find";
						for($i=0;$i<$nbr;$i++){
							$id=$array_book['id'][$i];
							$title=$array_book['title'][$i];
							$author=$array_book['author'][$i];
							echo "<h4><a href='./book.php?id=$id&amp;service=$service'>".$title."</a> ".$author."</h4>";
						}
					}
					else{
						$title=$array_book['title'][0];
						$author=$array_book['author'][0];
						$release=$array_book['release'][0];
						$synopsis=$array_book['synopsis'][0];
						
						echo "<h3>".$title."</h3>
							  <h4>".$author."</h4>
							  <h4>".$release."</h4>
							  <div style='word-wrap: break-word;width:80%'>".
								$synopsis."
							  </div>";
					}
				?>
			</div>		
		</div>
	</div>
	<p style="height:2em"></p>
</div>
	<?php include("../include/footers/footer_index.html") ?>
	
</body>
</html>