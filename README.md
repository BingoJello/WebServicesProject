# WebServicesProject

Member names and emails​
-----------------------------------
Arthur MIMOUNI : arthur.mimouni@gmail.com.
Yannick NANA : ianvanyann@gmail.com

Description project "Online Library"
----------------------------------
Create a service in SOAP and REST that provides the possibility to add and demand information of a book

Create a client to add books and then ask information about a book. By retrieving the title of the book, we retrieve a set of informations about it (Date of publication,
author, summary, book cover) using an external web service (Google Books API).

When a user adds a new book, the book must exist. To verify the existence of the book we look in the API if the book exists.

Services’ description 
-----------------------------------
SOAP and REST services make it possible to:
  -Recover one or more books containing the requested title.
  -Recover a book by its ID
  -Add a book to the database. 
  
For the REST service, here are the different possible GET requests:
  -http://localhost:8080/BookResearchRest/rest/book/title/{title}
  -http://localhost:8080/BookResearchRest/rest/book/id/{id} 

These services use a database that contains three tables:
  -BOOK : contains the id and title of the book
  -AUTHOR : contains the id and the name of the author
  -WRITING: contains the id of the book and the id of the author 

Clients’ description
-----------------------------------
The language used for the client is PHP. This is a website that contains two pages:
  -The index page where we can add a book or search
  -The "book" page (./research/book.php) which will contain the details of the book (s) of our research 

When searching for or adding a book, the user can choose which web service he wants to use (SOAP or GET).

The client functions code (SOAP and REST) is located in the "./include/functions" directory :
  -functionsSOAP.php : contains the functions of the client with the SOAP web service
  -functionsREST.php : contains the functions of the client with the REST web service
  -functions.inc.php : contains basic functions 
  -mysql.conf.php : function allowing connection to the database 

Example of the use of web services with the client. 
-----------------------------------
Adding a book in the database with the SOAP method 
![image](https://user-images.githubusercontent.com/60446421/113577839-33614800-9622-11eb-94d3-ffa81a16e02e.png)

Search (with SOAP method) for the previously added book in the database 
![image](https://user-images.githubusercontent.com/60446421/113577948-5e4b9c00-9622-11eb-9214-e68f40c0f1d0.png)

Display of the book with its characteristics (cover, title, author, synopsis) 
![image](https://user-images.githubusercontent.com/60446421/113578045-80ddb500-9622-11eb-9080-223511ed7421.png)
