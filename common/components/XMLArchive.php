<?php

/**
 * XML Archive Hanlder: Can create XML Archives (gzips) of multiple files, binary and ascii
 */

class XMLArchive
{
	/**
	* XML Object
	*
	* @access	private
	* @var 		object
	*/
	private $_xml				= null;
	
	/**
	* File array
	*
	* @access	private
	* @var 		array
	*/
	public $_fileArray		= array();
	
	/**
	* Error number
	*
	* @access	public
	* @var 		integer
	*/
	public $error_number  	= 0;
	
	/**
	* Error message
	*
	* @access	public
	* @var 		string
	*/
	public $error_message 	= "";
	
	/**
	* Work files
	*
	* @access	private
	* @var 		array
	*/
	public $_workFiles	= array();
	
	/**
	* Non binary file extensions
	*
	* @access	public
	* @var 		string
	*/
	public $non_binary		= 'txt htm html xml css js cgi php php3';
	
	/**
	* Strip path
	*
	* @access	public
	* @var 		string
	*/
	public $_stripPathString		= "";
	
	/**
	* Allow hidden files
	*
	* @access	private
	* @var		boolean
	*/
	private $_allowHiddenFiles = FALSE;

	/**
	* Constructor
	*
	* @access	public
	* @param	string		Script root path
	* @return	void
	*/
	public function __construct()
	{
		//-----------------------------------
		// Get the XML class
		//-----------------------------------
		
		Yii::import('ClassXML');
		$this->_xml = new ClassXML( DEFAULT_CHAR_SET );
		
		$this->error_number = 0;
	}
	
	/**
	* Allow hidden files to be added when using addDir
	*
	* @access	public
	* @return	null
	*/
	public function allowHiddenFiles()
	{
		$this->_allowHiddenFiles = TRUE;
	}
	
	/**
	* Set the path to 'strip' from the path data
	*
	* @access	public
	* @param	string		Full path to remove
	*/
	public function setStripPath( $path )
	{
		/* Ensure that there is no trailing slash */
		$path = rtrim( $path, '/' );
		
		$this->_stripPathString = $path;
	}
	
	/**
	* Return data as array
	*
	* @access	public
	* @return	array
	*/
	public function asArray()
	{
		return $this->_fileArray;
	}
	
	/**
	* Return a file from the fileArray
	*
	* @access	public
	* @return	string
	*/
	public function getFile( $key )
	{
		if ( isset( $this->_fileArray[ $key ] ) )
		{
			return $this->_fileArray[ $key ]['content'];
		}
		else
		{
			return '';
		}
	}
	
	/**
	* Return number of files contained in the expanded archive
	*
	* @access	public
	* @return	array
	*/
	public function countFileArray()
	{
		return intval( count( $this->_fileArray ) );
	}
	
	/**
	* Read an XML document from disk
	*
	* @access	public
	* @param	string	File name
	* @return	void
	*/
	public function read( $filename )
	{
		if ( file_exists( $filename ) )
		{
			if ( strstr( $filename, '.gz' ) )
			{
				if ( $FH = @gzopen( $filename, 'r' ) )
				{
					$data = @gzread( $FH, $filename );
					@gzclose( $FH );
				}
				else
				{
					throw new Exception( "COULD_NOT_LOAD_XML_DATA" );
				}
			}
			else
			{
				if ( $FH = @fopen( $filename, 'r' ) )
				{
					$data = @fread( $FH, filesize( $filename ) );
					@fclose( $FH );
				}
				else
				{
					throw new Exception( "COULD_NOT_LOAD_XML_DATA" );
				}
			}
			
			$this->readXML( $data );
		}
		else
		{
			throw new Exception( "FILE_OR_DIR_NOT_EXISTS" );
		}
	}
	
	/**
	* Read an XML document from passed data
	*
	* @access	public
	* @param	string	Raw XML Data
	* @return	void
	*/
	public function readXML( $data )
	{
		if ( $data )
		{
			$this->_xml->loadXML( $data );
			
			foreach( $this->_xml->fetchElements('file') as $file )
			{
				$_file = $this->_xml->fetchElementsFromRecord( $file );
				$_file['content'] = base64_decode( preg_replace( "/\s/", "", $_file['content'] ) );
				
				$this->_fileArray[ ltrim( $_file['path'] . '/' . $_file['filename'], '/' ) ] = $_file;
			}
		}
	}
	
	/**
	* Write out the XML document as a GZIP file
	*
	* @access	public
	* @param	string	Filename
	* @return	void
	*/
	public function saveGZIP( $filename )
	{
		$this->_create();
		
		if ( $this->_xml->fetchDocument() )
		{
			if ( $FH = @gzopen( $filename, 'wb' ) )
			{
				@gzwrite( $FH, $this->_xml->fetchDocument() );
				@gzclose( $FH );
			}
			else
			{
				throw new Exception( "CANNOT_WRITE_TO_DISK" );
			}
		}
		else
		{
			throw new Exception( "NOTHING_TO_SAVE" );
		}
	}
	
	/**
	* Write out the XML document as a normal file
	*
	* @access	public
	* @param	string	FIlename
	* @return	void
	*/
	public function save( $filename )
	{
		$this->_create();
		
		$_doc = $this->_xml->fetchDocument();
		
		if ( $_doc )
		{
			if ( $FH = @fopen( $filename, 'wb' ) )
			{
				@fwrite( $FH, $_doc );
				@fclose( $FH );
			}
			else
			{
				throw new Exception( "CANNOT_WRITE_TO_DISK" );
			}
		}
		else
		{
			throw new Exception( "NOTHING_TO_SAVE" );
		}
	}
	
	/**
	* Method of getting XML document from this class
	*
	* @access	public
	* @return	string	XML document
	*/
	public function getArchiveContents()
	{
		$this->_create();
		return $this->_xml->fetchDocument();
	}
	
	/**
	* Write the archive back to disk
	*
	* Note: Ensure that if you intend to make XMLArchives available for others
	* that you create it with setStripPath otherwise you may get unexpected results!
	*
	* @access	public
	* @param	mixed		Filename (must end with .xml or .gz) or raw data
	* @param	string		Base path to write to
	* @return	boolean		Or exceptions
	*
	* Exception Codes:
	* WRITE_NOT_VALID_INPUT		The input (XML or filename) is not valid
	* NO_FILES_TO_WRITE			The archive contains nothing to write back
	*/
	public function write( $input, $basePath='/' )
	{
		/* Gather the data */
		if ( substr( $input, -3 ) == '.gz' OR substr( $input, -3 ) == '.xml' )
		{
			try
			{
				$this->read( $input );
			}
			catch( Exception $e )
			{
				return $e->getMessage();
			}
		}
		else if ( strstr( $input, '<xmlarchive' ) )
		{
			$this->readXML( $input );
		}
		else
		{
			throw new Exception( "WRITE_NOT_VALID_INPUT" );
		}
		
		/* Did we get anything? */
		if ( ! $this->countFileArray() )
		{
			throw new Exception( "NO_FILES_TO_WRITE" );
		}
		
		$basePath = rtrim( $basePath, '/' );
		
		/* So, write 'em! */
		$files = $this->asArray();
		
		foreach( $files as $path => $data )
		{
			$path = trim( $path, '/' );
			
			if ( $this->_writeContents( $basePath . '/' . $path, $data['content'] ) !== TRUE )
			{
				return FALSE;
			}
		}
		
		/* Done */
		return TRUE;
	}
	
	/**
	* Main "Add" function  handles a lot of stuff.
	* It's good like that!
	*
	* @access	public
	* @param	string		Filename or data
	* @param	string		FIle to store as
	* @return	mixed
	*/
	public function add( $data, $saveName='' )
	{
		/* Is this just data? */
		if ( $saveName )
		{
			return $this->addData( $data, $saveName );
		}
		else if ( is_dir( $data ) )
		{
			return $this->addDirectory( $data );
		}
		else
		{
			return $this->addFile( $data );
		}
	}
	
	/**
	* Shorthand function
	*
	* @access	public
	* @return	mixed		addDirectory
	*/
	public function addDir( $dir )
	{
		return $this->addDirectory( $dir );
	}
	
	/**
	* Add directory to archive
	*
	* @access	public
	* @param	string		Directory to add
	* @return	mixed		Boolean on error, void
	*/
	public function addDirectory( $dir )
	{
		//-----------------------------------
		// Got dir?
		//-----------------------------------
		
		if ( ! is_dir($dir) )
		{
			throw new Exception( "FILE_OR_DIR_NOT_EXISTS" );
			return false;
		}
		
		$dir = rtrim( $dir, '/' );
		
		//-----------------------------------
		// Populate this->workfiles
		//-----------------------------------
		
		$this->_workFiles = array();
		$this->_getDirContents( $dir );
		
		//-----------------------------------
		// Add them into the file array
		//-----------------------------------
		
		foreach ( $this->_workFiles as $f )
		{
			$this->addFile( $f );
		}
		
		$this->_workFiles = array();
	}
	
	/**
	* Add file to archive
	*
	* @access	public
	* @param	string		File to add
	* @param	array 		Extra tags
	* @return	mixed		Boolean on error, void
	*/
	public function addFile( $filename, $extra_tags=array() )
	{
		//-----------------------------------
		// Kill hidden files
		//-----------------------------------
		
		$_temp = explode( '/', $filename );
		
		$actual_file = array_pop( $_temp );
		
		if ( in_array( strtolower($actual_file), array( '.ds_store', 'thumbs.db' ) ) )
		{
			return false;
		}

		if ( file_exists( $filename ) )
		{
			if ( $FH = @fopen( $filename, 'rb' ) )
			{
				$data = @fread( $FH, filesize( $filename ) );
				@fclose( $FH );
			}
			
			$this->addData( $data, $filename, $extra_tags );
		}
		else
		{
			throw new Exception( "FILE_OR_DIR_NOT_EXISTS" );
		}
	}

	
	/**
	* Add filecontents to archive
	*
	* @access	public
	* @param	string		File data
	* @param	string		File name
	* @param	array 		Extra tags
	* @return	void
	*/
	public function addData( $data, $filename, $extra_tags=array() )
	{
		$ext = preg_replace( "/.*\.(.+?)$/", "\\1", $filename );
		
		$binary = 1;
		
		//-----------------------------------
		// ASCII?
		//-----------------------------------
		
		if ( strstr( ' ' . $this->non_binary . ' ', ' '.$ext.' ' ) )
		{
			$binary = 0;
		}
		
		//-----------------------------------
		// Get dir / filename
		//-----------------------------------
		
		$dir_path = array();
		$dir_path = explode( "/", $filename );
		
		if ( count( $dir_path ) )
		{
			$real_filename = array_pop( $dir_path );
		}
		
		$real_filename = $real_filename ? $real_filename : $filename;
		
		$path = implode( "/", $dir_path );
		
		$path = $this->_stripPath( $path );
		
		$this_array = array(
							'filename'	=> $real_filename,
							'content'	=> $data,
							'path'		=> $path,
							'binary'	=> $binary
						  );
						  
		foreach( $extra_tags as $k => $v )
		{
			if ( $k and ! in_array( $k, array_keys($this_array) ) )
			{
				$this_array[ $k ] = $v;
			}
		}
		
		$this->_fileArray[] = $this_array;
	}
	
	/**
	* Create the XML archive
	*
	* @access	private
	* @return	void
	*/
	private function _create()
	{
		$this->_xml->newXMLDocument();
		$this->_xml->addElement( 'xmlarchive', '', array( 'generator' => 'IPS_KERNEL', 'created' => time() ) );
		$this->_xml->addElement( 'fileset', 'xmlarchive' );
		
		foreach( $this->_fileArray as $f )
		{
			$f['content'] = chunk_split(base64_encode($f['content']));
			
			$this->_xml->addElementAsRecord( 'fileset', 'file', $f );
		}
	}
	
	/**
	* Write contents of a file to disk
	* Creates directories, etc as it goes
	*
	* @access	private
	* @param	string		Path with filename to write to
	* @param	string		Data to write
	* @return	boolean
	*/
	private function _writeContents( $path, $content )
	{
		$path      = $this->_stripPath( $path );
		$_path     = ROOT_PATH;
		$_dirParts = explode( '/', str_replace( ROOT_PATH, '', $path ) );
		$file      = array_pop( $_dirParts );

		foreach( $_dirParts as $_p )
		{
			$_path .= '/' . $_p;
			
			if ( ! is_dir( $_path ) )
			{
				if ( ! @mkdir( $_path, 0777 ) )
				{
					return FALSE;
				}
				else
				{
					@chmod( $_path, 0777 );
				}
			}
		}
		
		if ( ! @file_put_contents( $_path . '/' . $file, $content ) )
		{
			return FALSE;
		}
		
		@chmod( $_path . '/' . $file, 0777 );
		
		return TRUE;
	}
	
	/**
	* Strip path information from the real path
	*
	* @access	private
	* @param	string		Input path
	* @return	string		Converted path
	*/
	private function _stripPath( $path )
	{
		if ( $this->_stripPathString )
		{
			$path = trim( str_ireplace( $this->_stripPathString, '', $path ), '/' );
		}
	
		return $path;
	}
	
	/**
	* Get directory contents
	*
	* @access	private
	* @param	string		Directory
	* @return	boolean		Successful
	*/
	private function _getDirContents( $dir )
	{
		if ( file_exists( $dir ) AND is_dir( $dir ) )
		{
			try
			{
				foreach( new DirectoryIterator( $dir ) as $file )
				{
					if ( ! $file->isDot() )
					{
						$filename = $file->getFileName();
            	
						/* Allow files? */
						if ( $this->_allowHiddenFiles !== TRUE )
						{
							if ( substr( $filename, 0, 1 ) == '.' )
							{
								continue;
							}
						}
            	
						if ( is_dir( $dir."/".$filename ) )
						{
							$this->_getDirContents( $dir . "/" . $filename );
						}
						else
						{
							$this->_workFiles[] = $dir . "/" . $filename;
						}
					}
				}
			} catch ( Exception $e ) {}
				
			return true;
		}
		else
		{
			throw new Exception( "FILE_OR_DIR_NOT_EXISTS" );
			return false;
		}
	}
}



?>