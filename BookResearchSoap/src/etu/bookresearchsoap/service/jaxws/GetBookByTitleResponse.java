
package etu.bookresearchsoap.service.jaxws;

import javax.xml.bind.annotation.XmlAccessType;
import javax.xml.bind.annotation.XmlAccessorType;
import javax.xml.bind.annotation.XmlElement;
import javax.xml.bind.annotation.XmlRootElement;
import javax.xml.bind.annotation.XmlType;

/**
 * This class was generated by Apache CXF 2.7.18
 * Fri Mar 26 14:07:08 CET 2021
 * Generated source version: 2.7.18
 */

@XmlRootElement(name = "getBookByTitleResponse", namespace = "http://service.bookresearchsoap.etu/")
@XmlAccessorType(XmlAccessType.FIELD)
@XmlType(name = "getBookByTitleResponse", namespace = "http://service.bookresearchsoap.etu/")

public class GetBookByTitleResponse {

    @XmlElement(name = "return")
    private java.util.ArrayList<etu.bookresearchsoap.model.Book> _return;

    public java.util.ArrayList<etu.bookresearchsoap.model.Book> getReturn() {
        return this._return;
    }

    public void setReturn(java.util.ArrayList<etu.bookresearchsoap.model.Book> new_return)  {
        this._return = new_return;
    }

}

