package etu.bookresearchrest.resource;

import java.util.ArrayList;
import javax.ws.rs.Consumes;
import javax.ws.rs.GET;
import javax.ws.rs.POST;
import javax.ws.rs.Path;
import javax.ws.rs.PathParam;
import javax.ws.rs.Produces;
import javax.ws.rs.core.MediaType;
import etu.bookresearchrest.model.Book;
import etu.bookresearchrest.service.BookService;

@Path("/book")
public class BookResource {	
	
	private BookService bookService = new BookService();
	
	@GET
	@Path("/title/{title}/")
	@Produces(MediaType.APPLICATION_XML)
	public ArrayList<Book> getBookByTitle(@PathParam("title") String title) {
		return bookService.getBookByTitle(title);
	}
	
	@GET
	@Path("/id/{idBook}/")
	@Produces(MediaType.APPLICATION_XML)
	public ArrayList<Book> getBookById(@PathParam("idBook") String idBook) {
		return bookService.getBookById(idBook);
	}
	
	@POST
	@Consumes(MediaType.APPLICATION_XML)
	@Produces(MediaType.APPLICATION_XML)
	public Book addBook(Book book) {
		return bookService.addBook(book);
	}
}
