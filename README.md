phppdftk
========

Unofficial PHP Wrapper for PdfTk Server toold. 

Prerequists
============
Before starting work with this wrapper, you should download and install PdfTk server.
https://www.pdflabs.com/tools/pdftk-server/


Usage
=====

1. Initiating PdfTk class with "Server Path"
	
	$pdftk=new PdfTk($serverPath);

	Initiating PdfTk class with "Server Path" and "PDF File"

	$pdftk=new PdfTk($serverPath,$pdfFilePath);

2. Get Bookmarks
	
		$bookmarks=$pdftk->get_bookmarks();
	
