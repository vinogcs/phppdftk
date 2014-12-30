<?php
	
	class PdfTk{
		// Default Empty.
		// Empty String = Current DIrectory 
		private $_SERVER_PATH="";
		
		// Command to Dump Data
		private $_CMD_DUMP_DATA="dump_data";
		
		// Absolute Path for the PDF File
		private $_PDF_FILE="";
		
		// Extension of PDFTK executable. 
		// "exe" for Windows
		// "so" for Linux
		private $_SERVER_EXTENSION="exe";
		
		
		private $KEY_BEGIN="BookmarkBegin";
		private $KEY_TITLE="BookmarkTitle";
		private $KEY_LEVEL="BookmarkLevel";
		private $KEY_PAGE_NUMBER="BookmarkPageNumber";
		
		
		private $_LEVEL_MIN=1;
		private $_LEVEL_MAX=9;
				
		function __construct($serverPath="",$pdfFilePath=""){
			$this->_SERVER_PATH=$serverPath;
			$this->_PDF_FILE=$pdfFilePath;
		}
		
		
		/**
		 * DECORATORS
		 */
		 public function serverPath($serverPath=""){
		 	if(empty($serverPath))
				return $this->_SERVER_PATH;
			else
				$this->_SERVER_PATH=$serverPath;
		 }
		 public function pdfFile($pdfFilePath=""){
		 	if(empty($pdfFilePath))
				return $this->_PDF_FILE;
			else
				$this->_PDF_FILE=$pdfFilePath;
		 }
		 
		 
		 /**
		  * @method 
		  * 	Decrypt the  PDF File
		  */
		  public function  decrypt(){
		  	if(!$this->server_exists())
				throw new Exception("Invalid Server Path.");
			
			$cmd="\"{$this->_SERVER_PATH}pdftk\" {$this->_PDF_FILE} {$this->_CMD_DUMP_DATA}";
			$output=shell_exec($cmd);
			
			if(empty($output))
				throw new Exception("Empty Response from Server. Perhaps you have out dated Server files.");
			return $output;
		  }
		  
		  public function get_bookmarks(){
		  	$output=$this->decrypt();
			$arr_output=explode("\n",$output);
			
			//Remove Non Bookmarks items
			foreach($arr_output as $index=>$line){
				if(substr(strtolower($line),0,8)!=="bookmark"){
					unset($arr_output[$index]);
				}
			}
			
			$bookmarks=array();
			$key_begin="BookmarkBegin";
			
			$b_index=-1;
			foreach($arr_output as $index=>$line){
				if(substr($line,0,strlen($this->KEY_BEGIN))==$this->KEY_BEGIN){
					$b_index++;
				}else if(substr($line,0,strlen($this->KEY_TITLE))==$this->KEY_TITLE){
					$bookmarks[$b_index]["title"]=trim(substr($line,strpos($line, ":")+1));
				}else if(substr($line,0,strlen($this->KEY_PAGE_NUMBER))==$this->KEY_PAGE_NUMBER){
					$bookmarks[$b_index]["page_number"]=intval(trim(substr($line,strpos($line, ":")+1)));
				}else if(substr($line,0,strlen($this->KEY_LEVEL))==$this->KEY_LEVEL){
					$bookmarks[$b_index]["level"]=intval(trim(substr($line,strpos($line, ":")+1)));
				}
			}
			
			
			// Rearranging Bookmarks for different levels.
			// Nesting Bookmarks to its parent
			
			for($l_index=$this->_LEVEL_MIN;$l_index<=$this->_LEVEL_MAX;$l_index++){
				if($l_index>1){
					$current_up=-1;
					foreach($bookmarks as $index=>$bookmark){
						if($bookmark["level"]==$l_index-1){
							$bookmark["bookmarks"]=array();
							$curent_up=$index;
						}
						if($bookmark["level"]==$l_index){
							$bookmarks[$curent_up]["bookmarks"][]=$bookmark;
							unset($bookmarks[$index]);
						}
						
					}
				}
			}
			
			return $bookmarks;
		  }
		  
		  private function server_exists(){
		  	return file_exists($this->_SERVER_PATH . "pdftk." . $this->_SERVER_EXTENSION);
		  }
		  
	}
?>
