phppdftk
========

Unofficial PHP Wrapper for PdfTk Server toold. 

Prerequists
============
Before starting work with this wrapper, you should download and install PdfTk server.
https://www.pdflabs.com/tools/pdftk-server/


Usage
=====

-1. Initiating PdfTk class 
	
	**// With Server Path**<br />
	**$pdftk=new PdfTk($serverPath);**<br />
	**//With Server Path and PDF File path**<br />
	**$pdftk=new PdfTk($serverPath,$pdfFilePath);**<br />

-2. Get Bookmarks

	
		**$bookmarks=$pdftk->get_bookmarks();**<br />
	
