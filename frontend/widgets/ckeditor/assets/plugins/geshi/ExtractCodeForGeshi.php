<?php  
/*-------------------------------------------------------------------------*/
/* A PHP code to extract <pre source code block from your document 
/* and manage it with Geshi
/* Created by PkLab.net 
/*
/* For more information about GeSHi plugin for CkEditor 3.x installation and 
/* how to manage the code see http://www.pklab.net/index.php?id=350
/*
/* Suppose you have in HTML page in $Content var
/*
/* $Content = '
/* <p> This code will print "Hello World"
/* <pre class="geshi:php;line_num:false;"> 
/* echo "Hello World
/* </pre>
/* The content will continue as you want';
/*
/* The following code replace all <pre class="geshi:...</pre> from a content with 
/* syntax highlighted version, leaving untouched remain content 
/*
/* More code block and or more language are admitted for single content
/*
*/

  
  // <pre class="geshi:php;line_num:false;"> source code </pre>
  // ^START                                                   ^STOP
  $codekey = 'geshi';
  $codetag = '<pre class="';
  $fullcodetag = $codetag.$codekey;
  $START = strpos($Content,$fullcodetag) ;
  $langs = array();
  while($START!==false)
  {
    include_once '/geshi/geshi.php'; 
    
    //take tag option string
    $start = $START+strlen($codetag);
    $stop = strpos($Content,'"',$start) ;
    $tagopt = substr($Content,$start,$stop-$start);
    
    //parse option string into array
    $options  = String2KeyedArray($tagopt,';',':');
    $lang = $options['geshi'];
    $line_num = ($options['line_num'] != 'false');
    
    //look for source code
    $start = 1+strpos($Content,'>',$start) ;
    $stop  = strpos($Content,'</pre>',$start) ;    
    
    //if close tag is not found goto untile EndOfFIle
    if($stop!==false)
    {
      $STOP = $stop+strlen('</pre>');
    }  
    else 
    {
      $stop = strlen ($Content);
      $STOP = $stop;
    }
    
    //get the source code
    $source = substr ($Content,$start,$stop-$start) ;
    $source = html_entity_decode($source,ENT_QUOTES);
    
    
    if(end($langs)==$lang)
    {
      if($line_num == true)
        $geshi->enable_line_numbers(GESHI_NORMAL_LINE_NUMBERS);
      else
        $geshi->enable_line_numbers(GESHI_NO_LINE_NUMBERS);

      //if lang is same of a last code block then recycle geshi obj and css
      $geshi->set_source($source);
    }
    else  
    { 
      //new source language has been found in the content
      
      //create a new geshi obj
      $geshi = new GeSHi($source, $lang);
      
      // And echo the result!//
      $geshi->set_header_type(GESHI_HEADER_PRE);
      $geshi->enable_classes();
      $geshi->set_overall_style('font-size:10pt;', true);
      $geshi->set_line_style('font-size:10pt;','font-size:10pt;');
      //$geshi->set_line_style("font-size:10pt;background: #f0f0f0;", "font-size:10pt;background: #fcfcfc;");

      
      // MISTAKE: for right formatting, this must be out before outing css 
      if($line_num == true)
        $geshi->enable_line_numbers(GESHI_NORMAL_LINE_NUMBERS,2);
      else
        $geshi->enable_line_numbers(GESHI_NO_LINE_NUMBERS);

      // Echo out the stylesheet for this code block
      // chek if css lang has already loaded 
      if(!in_array($lang,$langs))
      {
        echo '<style type="text/css"><!--'.
          $geshi->get_stylesheet().
          '--></style>';
      }
      // stores languase already found in current page
      array_push($langs,$lang);
    }
    
      
    $newcode = $geshi->parse_code();
    $Content = 
      substr($Content,0,$START).
      $newcode.
      substr($Content,$STOP);
    
    //search for next loop  
    $START = strpos($Content,$fullcodetag) ;
  }
  // END GESHI
?>