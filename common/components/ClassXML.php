<?php

/**
 * XML Handler
 */
 
class ClassXML
{
	/**
	 * Document character set
	 *
	 * @access	private
	 * @var		string
	 */
	private $_docCharSet = 'UTF-8';
	
	/**
	 * Current document object
	 *
	 * @access	private
	 * @var		object
	 */
	private $_dom;
	
	/**
	 * Array of DOM objects
	 *
	 * @access	private
	 * @var		array
	 */
	private $_domObjects = array();
	
	/**
	 * XML array
	 *
	 * @access	private
	 * @var		array
	 */
	private $_xmlArray = array();
	
	/**
	 * Conversion class
	 *
	 * @access	private
	 * @var		object
	 */
	private static $classConvertCharset;
	
	/**
	 * Constructor
	 *
	 * @access	public
	 * @param	string	Character Set
	 * @return	void
	 */
	public function __construct( $charSet=DEFAULT_CHAR_SET )
	{
		$this->_docCharSet = strtolower( $charSet );
	}
	
	/**
	 * Create new document
	 *
	 * @access	public
	 * @return	void
	 */
	public function newXMLDocument()
	{
		$this->_dom = new DOMDocument( '1.0', 'utf-8' );
	}
	
	/**
	 * Fetch the document
	 *
	 * @access	public
	 * @return	XML data
	 */
	public function fetchDocument()
	{
		$this->_dom->formatOutput = TRUE;
		return $this->_dom->saveXML();
	}
	
	/**
	 * Add element into the document
	 *
	 * @access	public
	 * @param	string		Name of tag to create
	 * @param	string		[Name of parent tag (optional)]
	 * @param	array		[Attributes]
	 * @return	void
	 */
	public function addElement( $tag, $parentTag='', $attributes=array() )
	{
		$this->_domObjects[ $tag ] = $this->_node( $parentTag )->appendChild( new DOMElement( $tag ) );
		$this->addAttributes( $tag, $attributes );
	}
	
	/**
	 * Add element into the document as a record row
	 * You can pass $tag as either a string or an array
	 *
	 * $xml->addElementAsRecord( 'parentTag', 'myTag', $data );
	 * $xml->addElementAsRecord( 'parentTag', array( 'myTag', array( 'attr' => 'value' ) ), $data );
	 *
	 * @access	public
	 * @param	string		Name of parent tag
	 * @param	mixed		Tag wrapper
	 * @param	array 		Array of data to add
	 * @return	void
	 */
	public function addElementAsRecord( $parentTag, $tag, $data )
	{
		/* A little set up if you please... */
		$_tag      = $tag;
		$_tag_attr = array();
		
		if ( is_array( $tag ) )
		{
			$_tag      = $tag[0];
			$_tag_attr = $tag[1];
		}
		
		$record = $this->_node( $parentTag )->appendChild( new DOMElement( $_tag ) );
		
		if ( is_array( $_tag_attr ) AND count( $_tag_attr ) )
		{
			foreach( $_tag_attr as $k => $v )
			{
				$record->appendChild( new DOMAttr( $k, $v ) );
			}
		}
			
		/* Now to add the data */
		if ( is_array( $data ) AND count( $data ) )
		{
			foreach( $data as $rowTag => $rowData )
			{
				/* You can pass an array.. or not if you don't need attributes */
				if ( ! is_array( $rowData ) )
				{
					$rowData = array( 0 => $rowData );
				}
				
				if ( preg_match( "/['\"\[\]<>&]/", $rowData[0] ) )
				{
					$_child = $record->appendChild( new DOMElement( $rowTag ) ); 
					$_child->appendChild( new DOMCDATASection( $this->_inputToXml( $rowData[0] ) ) );
				}
				else
				{
					$_child = $record->appendChild( new DOMElement( $rowTag, $this->_inputToXml( $rowData[0] ) ) );
				}
				
				if ( isset($rowData[1]) && $rowData[1] )
				{
					foreach( $rowData[1] as $_k => $_v )
					{
						$_child->appendChild( new DOMAttr( $_k, $_v ) );
					}
				}
				
				unset( $_child );
			}
		}
	}
	
	/**
	 * Add attributes to a node
	 *
	 * @access	public
	 * @param	string		Name of tag
	 * @param	array 		Array of attributes in key => value format
	 * @return	void
	 */
	public function addAttributes( $tag, $data )
	{
		if ( is_array( $data ) AND count( $data ) )
		{
			foreach( $data as $k => $v )
			{
				$this->_node( $tag )->appendChild( new DOMAttr( $k, $v ) );
			}
		}
	}
	
	/**
	 * Load a document from a file
	 *
	 * @access	public
	 * @param	string 		File name
	 * @return	void
	 */
	public function load( $filename )
	{
		$this->_dom = new DOMDocument;
		$this->_dom->load( $filename );
	}
	
	/**
	 * Load a document from a string
	 *
	 * @access	public
	 * @param	string 		XML Data
	 * @return	void
	 */
	public function loadXML( $xmlData )
	{
		$this->_dom = new DOMDocument;
		$this->_dom->loadXML( $xmlData );
	}
	
	/**
	 * Wrapper function: Fetch elements based on tag name
	 *
	 * @access	public
	 * @param	string		Tag  Name to fetch from the DOM tree
	 * @param	object		Node to start from
	 * @return	array 		Node elements
	 */
	public function fetchElements( $tag, $node=null )
	{
		$start		= $node ? $node : $this->_dom;
		$_elements = $start->getElementsByTagName( $tag );
		
		return ( $_elements->length ) ? $_elements : array();
	}
	
	/**
	 * Wrapper function: Fetch all items within a parent tag
	 *
	 * @access	public
	 * @param	object		DOM object as returned from getElementsByTagName
	 * @param	array 		array of node names to skip
	 * @return	array 		Array of elements
	 */
	public function fetchElementsFromRecord( $dom, $skip=array() )
	{
		$array = array();
		
		foreach( $dom->childNodes as $node )
		{
			if ( $node->nodeType == XML_ELEMENT_NODE )
			{
				if ( is_array( $skip ) )
				{
					if ( in_array( $node->nodeName, $skip ) )
					{
						continue;
					}
				}

				$array[ $node->nodeName ] = $this->_xmlToOutput( $node->nodeValue );
			}
		}
		
		return $array;
	}
	
	/**
	 * Wrapper function: Fetch items from an element node
	 *
	 * @access	public
	 * @param	object		DOM object as returned from getElementsByTagName
	 * @param	string 		[Optional: Tag name if the DOM is a parent]
	 * @return	string		Returned item
	 */
	public function fetchItem( $dom, $tag='' )
	{
		if ( $tag )
		{
			$_child = $dom->getElementsByTagName( $tag );
			return $this->_xmlToOutput( $_child->item(0)->firstChild->nodeValue );
		}
		else
		{
			return $this->_xmlToOutput( $dom->nodeValue );
		}
	}
	
	/**
	 * Wrapper function: Fetch attributes from an element node's item
	 *
	 * @access	public
	 * @param	object		DOM object as returned from getElementsByTagName
	 * @param	string 		Attribute name required...
	 * @param	string 		[Optional: Tag name if the DOM is a parent]
	 * @return	string		Attribute
	 */
	public function fetchAttribute( $dom, $attribute, $tag='' )
	{
		if ( $tag )
		{
			$_child = $dom->getElementsByTagName( $tag );
			return $_child->item(0)->getAttribute( $attribute );
		}
		else
		{
			return $dom->getAttribute( $attribute );
		}
	}
	
	/**
	 * Wrapper function: Fetch all attributes from an element node's item
	 *
	 * @access	public
	 * @param	object		DOM object as returned from getElementsByTagName
	 * @param	string 		Tag name to fetch attribute from
	 * @return	array 		Array of node items
	 */
	public function fetchAttributesAsArray( $dom, $tag )
	{
		$attrs      = array();
		$_child     = $dom->getElementsByTagName( $tag );
		$attributes = $_child->item(0)->attributes;
		
		foreach( $attributes as $val )
		{
			$attrs[ $val->nodeName ] = $val->nodeValue;
		}
		
		return $attrs;
	}
	
	/**
	 * Fetch entire DOM tree into a single array
	 *
	 * @access	public
	 * @return	array
	 */
	public function fetchXMLAsArray()
	{
		return $this->_fetchXMLAsArray( $this->_dom );
	}
	
	/**
	 * Internal function to recurse through and collect nodes and data
	 *
	 * @access	private
	 * @param	DOM object 		Node element
	 * @return	array
	 */
	private function _fetchXMLAsArray( $node )
	{
		$_xmlArray = array();
		
		if ( $node->nodeType == XML_TEXT_NODE )
		{
			$_xmlArray = $node->nodeValue;
		}
		else if ( $node->nodeType == XML_CDATA_SECTION_NODE )
		{
			$_xmlArray = $this->_xmlToOutput( $node->nodeValue );
		}
		else
		{
			if ( $node->hasAttributes() )
			{
				$attributes = $node->attributes;
				
				if ( ! is_null( $attributes ) )
				{
					foreach( $attributes as $index => $attr )
					{
						$_xmlArray['@attributes'][ $attr->name ] = $attr->value;
					}
				}
			}
			
			if ( $node->hasChildNodes() )
			{
				$children  = $node->childNodes;
				$occurance = array();

				foreach( $children as $nc)
			    {
					if ( $nc->nodeName != '#text' AND $nc->nodeName != '#cdata-section' )
					{
			    		$occurance[ $nc->nodeName ]++;
					}
			    }
				
				for( $i = 0 ; $i < $children->length ; $i++ )
				{
					$child = $children->item( $i );
					$_name = $child->nodeName;
					
					if ( $child->nodeName == '#text' OR $child->nodeName == '#cdata-section' )
					{
						$_name = '#alltext';
					}
					
					if ( $occurance[ $child->nodeName ] > 1 )
					{
						$_xmlArray[ $_name ][] = $this->_fetchXMLAsArray( $child, $ignoreDOMTags );
					}
					else
					{
						$_xmlArray[ $_name ] = $this->_fetchXMLAsArray( $child, $ignoreDOMTags );
					}
				}
			}
		}
		
		return $_xmlArray;
	}
	
	/**
	 * Encode CDATA XML attribute (Make safe for transport)
	 *
	 * @access	private
	 * @param	string		Raw data
	 * @return	string		Converted Data
	 */
	private function _xmlConvertSafecdata( $v )
	{
		$v = str_replace( "<![CDATA[", "<!#^#|CDATA|", $v );
		$v = str_replace( "]]>"      , "|#^#]>"      , $v );
		
		return $v;
	}

	/**
	 * Decode CDATA XML attribute (Make safe for transport)
	 *
	 * @access	private
	 * @param	string		Raw data
	 * @return	string		Converted Data
	 */
	private function _xmlUnconvertSafecdata( $v )
	{
		$v = str_replace( "<!#^#|CDATA|", "<![CDATA[", $v );
		$v = str_replace( "|#^#]>"      , "]]>"      , $v );
		
		return $v;
	}
	
	/**
	 * Return a tag object
	 *
	 * @access	private
	 * @param	string		Name of tag
	 * @return	object		
	 */
	private function _node( $tag )
	{
		if ( isset($this->_domObjects[ $tag ]) )
		{
			return $this->_domObjects[ $tag ];
		}
		else
		{
			return $this->_dom;
		}
	}
	
	/**
	 * Convert from native to UTF-8 for saving XML
	 *
	 * @access	private
	 * @param	string		Input Text
	 * @return	string		Converted Text ready for XML saving
	 */
	private function _inputToXml( $text )
	{
		/* Do we need to make safe on CDATA? */
		if ( preg_match( "/['\"\[\]<>&]/", $text ) )
		{
			$text = $this->_xmlConvertSafecdata( $text );
		}
		
		/* Using UTF-8 */
		if ( $this->_docCharSet == 'utf-8' )
		{
			return $text;
		}
		/* Are we using the most common ISO-8559-1... */
		else if ( $this->_docCharSet == 'iso-8859-1' )
		{
			return utf8_encode( $text );
		}
		else
		{
			return $this->_convertCharsets( $text, $this->_docCharSet, 'utf-8' );
		}
	}
	
	/**
	 * Convert from UTF-8 to native for saving XML
	 *
	 * @access	private
	 * @param	string		Input Text
	 * @return	string		Converted Text ready for returning to app
	 */
	private function _xmlToOutput( $text )
	{
		/* Unconvert cdata */
		$text = $this->_xmlUnconvertSafecdata( $text );
		
		/* Using UTF-8 */
		if ( $this->_docCharSet == 'utf-8' )
		{
			return $text;
		}
		/* Are we using the most common ISO-8559-1... */
		else if ( $this->_docCharSet == 'iso-8859-1' )
		{
			return utf8_decode( $text );
		}
		else
		{
			return $this->_convertCharsets( $text, 'utf-8', $this->_docCharSet );
		}
	}
	
	/**
	 * Convert a string between charsets. XML will always be UTF-8
	 *
	 * @access	private
	 * @param	string		Input String
	 * @param	string		Current char set
	 * @param	string		Destination char set
	 * @return	string		Parsed string
	 * @todo 	[Future] If an error is set in classConvertCharset, show it or log it somehow
	 */
	private function _convertCharsets( $text, $original_cset, $destination_cset="UTF-8" )
	{
		$original_cset    = strtolower($original_cset);
		$destination_cset = strtolower( $destination_cset );
		$t                = $text;

		//-----------------------------------------
		// Not the same?
		//-----------------------------------------

		if ( $destination_cset == $original_cset )
		{
			return $t;
		}

		if ( ! is_object( self::$classConvertCharset ) )
		{
			Yii::import('ConvertCharset');
			self::$classConvertCharset = new ConvertCharset();
			
			//-----------------------------------------
			// Ok, mb functions only support limited number
			// of charsets, so if mb functions are enabled
			// but using e.g. windows-1250, no conversion
			// ends up happening.  Let's force internal.
			//-----------------------------------------
			
			//if ( function_exists( 'mb_convert_encoding' ) )
			//{
			//	self::$classConvertCharset->method = 'mb';
			//}
			//else if ( function_exists( 'iconv' ) )
			//{
			//	self::$classConvertCharset->method = 'iconv';
			//}
			//else if ( function_exists( 'recode_string' ) )
			//{
			//	self::$classConvertCharset->method = 'recode';
			//}
			//else
			//{
				self::$classConvertCharset->method = 'internal';
			//}
		}

		$text = self::$classConvertCharset->convertEncoding( $text, $original_cset, $destination_cset );

		return $text ? $text : $t;
	}
}