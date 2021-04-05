package etu.bookresearchsoap.service;

import java.util.ArrayList;
import javax.jws.WebMethod;
import javax.jws.WebResult;
import javax.jws.WebService;
import javax.jws.WebParam;

import etu.bookresearchsoap.model.Book;

@SuppressWarnings("restriction")
@WebService(name = "BookManagement", targetNamespace = "http://service.bookresearchsoap.etu/")
public interface BookManagement {
	
	@WebMethod(operationName = "getBookByTitle", action = "urn:GetBookByTitle")
	@WebResult(name = "return")
	public ArrayList<Book> getBookByTitle(@WebParam(name = "title") String title);
	
	@WebMethod(operationName = "getBookById", action = "urn:GetBookById")
	@WebResult(name = "return")
	public ArrayList<Book> getBookById(@WebParam(name = "id") String idBook);

	@WebMethod(operationName = "addBook", action = "urn:AddBook")
	@WebResult(name = "return")
	public Book addBook(@WebParam(name = "book")Book book);
}
