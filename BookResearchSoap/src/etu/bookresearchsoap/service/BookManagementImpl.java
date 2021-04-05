package etu.bookresearchsoap.service;

import java.sql.SQLException;
import java.util.ArrayList;

import javax.jws.WebService;

import etu.bookresearchsoap.database.DatabaseHandler;
import etu.bookresearchsoap.model.Book;

@SuppressWarnings("restriction")
@WebService(endpointInterface="etu.bookresearchsoap.service.BookManagement",portName="BookManagementPort", 
serviceName="BookManagementService",targetNamespace = "http://service.bookresearchsoap.etu/")

public class BookManagementImpl implements BookManagement {
	private String url="jdbc:mysql://localhost/books_research?zeroDateTimeBehavior=CONVERT_TO_NULL&serverTimezone=UTC";
	private String login="root";
	private String password="A123456*";
	private DatabaseHandler db = new DatabaseHandler(url,login,password);

	/* Get a book(s) from the database that contains the requested title */
	public ArrayList<Book> getBookByTitle(String title) {
		ArrayList<Book> books =  db.getBookByTitle(title);
		return books;
	}

	/* Get a book from the database using its ID */
	public ArrayList<Book> getBookById(String idBook) {
		ArrayList<Book> books =  db.getBookById(idBook);
		return books;
	}

	/* Add a book into the database */
	public Book addBook(Book book) {
		try {
			Book bookAdd=db.insertBook(book);
			if(bookAdd != null ) {
				return bookAdd;
			}
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		} catch (ClassNotFoundException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		//If the book already exists in the database, we return null
		return null;
	}

}