package etu.bookresearchsoap.database;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.ArrayList;

import etu.bookresearchsoap.model.Book;

public class DatabaseHandler {
	private String url;
	private String login;
	private String password;
	private static Connection conn = null;
	private static PreparedStatement prepareStat = null;
	
	public DatabaseHandler(String url, String login, String password) {
		this.url=url;
		this.login=login;
		this.password=password;
	}
	
	/* Get a book(s) from the database that contains the requested title */
	public ArrayList<Book> getBookByTitle(String title) {
		ArrayList<Book> books = new ArrayList<Book>();
		int i=0;

		try {
			Class.forName("com.mysql.cj.jdbc.Driver");
			//Class.forName("com.mysql.jdbc.Driver");
			conn = DriverManager.getConnection(url, login, password);
			String insertQueryStatement = "	SELECT book.id_book, title, name_author FROM book\r\n" + 
											" INNER JOIN writing ON writing.id_book=book.id_book\r\n" + 
											" INNER JOIN author ON author.id_author=writing.id_author\r\n" + 
											" WHERE title LIKE ?";
	 
			prepareStat = conn.prepareStatement(insertQueryStatement);
			prepareStat.setString(1, "%"+title+"%");
				
			// Execute insert SQL statement
			ResultSet rs=prepareStat.executeQuery();
			while ( rs.next()){
				// All books found are stored in our ArrayList
				Book book = new Book();
				book.setId(rs.getString("id_book"));
				book.setTitle(rs.getString("title"));
				book.setAuthor(rs.getString("name_author"));
				books.add(i,book);
				i++;
			}
			rs.close();
				prepareStat.close();
				conn.close();	
			
			} catch (ClassNotFoundException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			} catch (SQLException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			}
		
		return books;
	}
	
	/* Get a book from the database using its ID */
	public ArrayList<Book> getBookById(String idBook) {
		ArrayList<Book> books = new ArrayList<Book>();
		Book book = new Book();
		int i=0; 
	
			try {
				Class.forName("com.mysql.cj.jdbc.Driver");
				conn = DriverManager.getConnection(url, login, password);
				String selectQueryStatement = "	SELECT book.id_book, title, name_author FROM book\r\n" + 
												" INNER JOIN writing ON writing.id_book=book.id_book\r\n" + 
												" INNER JOIN author ON author.id_author=writing.id_author\r\n" + 
												" WHERE book.id_book=?";  
				prepareStat = conn.prepareStatement(selectQueryStatement);
				prepareStat.setString(1, idBook);
				
				// Execute insert SQL statement
				ResultSet rs=prepareStat.executeQuery();
				while ( rs.next() )
			    {
					// The book found is stored in our ArrayList
					book.setId(rs.getString("id_book"));
					book.setTitle(rs.getString("title"));
					book.setAuthor(rs.getString("name_author"));
					books.add(i,book);
					i++;
			    }
				rs.close();
				prepareStat.close();
				conn.close();	
			} catch (ClassNotFoundException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			} catch (SQLException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			}
		return books;
	}
	
	/* Get a new key for a specific table in the database */
	private int getNewKey(String table,String column) {
		int key=0;
	
			try {
				Class.forName("com.mysql.cj.jdbc.Driver");
				conn = DriverManager.getConnection(url, login, password);
				String selectQueryStatement = "SELECT "+column+" FROM "+table+" ORDER BY "+column+" DESC";
	 
				prepareStat = conn.prepareStatement(selectQueryStatement);
				
				// execute insert SQL statement
				ResultSet rs=prepareStat.executeQuery();
				while ( rs.next() )
			    {
					key++;
			    }
				rs.close();
			} catch (ClassNotFoundException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			} catch (SQLException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			}
		return key;
	}
	
	/*Get the ID of the author thanks to his name */
	private int getIdAuthor(String nameAuthor ) {
			try {
				Class.forName("com.mysql.cj.jdbc.Driver");
				conn = DriverManager.getConnection(url, login, password);
				String selectQueryStatement = "SELECT id_author FROM author WHERE name_author = ?";
	 
				prepareStat = conn.prepareStatement(selectQueryStatement);
				prepareStat.setString(1, nameAuthor);
				// Execute insert SQL statement
				ResultSet rs=prepareStat.executeQuery();
				while ( rs.next() )
			    {
					return rs.getInt("id_author");
			    }
				rs.close();
			} catch (ClassNotFoundException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			} catch (SQLException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			}
		// Case where the author does not exist 
		return -1;
	}
	
	/* Inserting a book into the database */
	public Book insertBook(Book book) throws SQLException, ClassNotFoundException {
		ArrayList<Book> books = getBookById(book.getId());
		
		//If the book already exists in the database, we return null
		if(books.size()>0) {
			return new Book();
		}
		try {
			Class.forName("com.mysql.cj.jdbc.Driver");
			// DriverManager: The basic service for managing a set of JDBC drivers.
			conn = DriverManager.getConnection(url, login, password);
			String insertQueryStatement = "INSERT INTO book (id_book, title) VALUES (?,?)";
			 
			prepareStat = conn.prepareStatement(insertQueryStatement);
			prepareStat.setString(1, book.getId());
			prepareStat.setString(2, book.getTitle());
 
			// Execute insert SQL statement
			prepareStat.executeUpdate();
			
			int idAuthor=getIdAuthor(book.getAuthor());
			
			// If the author of the book to insert does not exist in the database, then we retrieve a new key and add this new author 
			if(idAuthor==-1) {
				idAuthor=getNewKey("author","id_author");
				
				insertQueryStatement = "INSERT INTO author (id_author, name_author) VALUES (?,?)";
				prepareStat = conn.prepareStatement(insertQueryStatement);
				prepareStat.setInt(1, idAuthor);
				prepareStat.setString(2, book.getAuthor());
	 
				// Execute insert SQL statement
				prepareStat.executeUpdate();
			}
			
			insertQueryStatement = "INSERT INTO writing (id_book, id_author) VALUES (?,?)";
			prepareStat = conn.prepareStatement(insertQueryStatement);
			prepareStat.setString(1, book.getId());
			prepareStat.setInt(2, idAuthor);
 
			// Execute insert SQL statement
			prepareStat.executeUpdate();
			
			prepareStat.close();
			conn.close();
		} catch (SQLException e) {
			e.printStackTrace();
		}
		return book;
	}
}
