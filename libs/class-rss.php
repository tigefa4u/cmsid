<?php 
/**
 * @fileName: class-rss-reader.php
 * @dir: libs/
 */
if(!defined('_iEXEC')) exit;

/**
 * Rss Feed Reader
 *
 * After two years, I decided to rewrite and fix this
 * class. Thanks to all of you who sent me bug reports
 * and sorry that I didn't replied. I'm lazy when it
 * comes to talking.
 *
 * Changelog v2.2:
 * - probably fixed xml_set_object()
 * - added cURL support
 * - added switch between fopen()/file_get_contents()/cURL
 * - added category tag support
 * - changed string configuration values to constants (see example.php)
 * - some comments were removed, some new added
 * - minified version of the file is now available
 * - added few exceptions and changed example.php to use them
 * - cURL sends it's own UserAgent
 *
 * Changelog v2.1:
 * - added Text parser support. Got this idea today
 * while working on something else. Maybe help in
 * some cases where XML and SimpleXML fails. To be
 * honest, it's not really the finest solution but
 * it works and I was too tired to think more about
 * it...
 *
 * Changelog v2.0:
 * - new version, hopefully without stupid bugs
 * I left in first version :)
 * - added SimpleXML parser support
 *
 * Bugs:
 * 	- Opening local files won't work for some reason,
 * 	probably because of headers or something. Using
 * SXML or TXT instead of XML may help.
 *
 * Issues:
 * - Because of some PHP configurations deny to open
 * remote files with fopen(), I used file_get_contents()
 * but you still can switch to fopen(). See code for
 * more informations.
 *
 * Usage:
 * - See example.php
 *
 * Notes:
 * - If you study this code a little, you can see that
 * SXML is just a little wrapper around very few lines
 * of code. SimpleXML is really nice and simple way to
 * parse XML, but it has problems with special chars
 * entities.
 * - Even that this class is released under GPL licence,
 * I'm not responsible for anything that happens to you
 * (e.g. you die by reading these dull lines of text)
 * or your computer/website/whatever.
 *
 * Copyright 2007-2010, Daniel Tlach
 *
 * Licensed under GNU GPL
 *
 * @copyright		Copyright 2007-2010, Daniel Tlach
 * @link			http://www.danaketh.com
 * @version			2.2
 * @license			http://www.gnu.org/licenses/gpl.txt
 */
class Rss
{
	/* Private variables */
	private $parser;
	private $feed_url;
	private $item;
	private $tag;
	private $output;
	private $counter = 0;

	/* Private variables for RSS */
	private $title 			= false;
	private $description 	= false;
	private $link 			= false;
	private $category		= false;
	private $author 		= false;
	private $pubDate 		= false;

	/* Setup constants */
	const XML 	= 'XML'; // for XML parser
	const SXML 	= 'SXML'; // for SimpleXML parser
	const TXT 	= 'TXT'; // for text parser using regular expressions

	// {{{ construct
	/**
	 * Constructor
	 */
	function __construct(  )
	{
	}
	// }}}

	// {{{ getFeed
	/**
	 * Get RSS feed from given URL, parse it and return
	 * as classic array. You can switch between XML,
	 * SimpleXML and text (regex) method of reading.
	 *
	 * @access 		public
	 * @param		<string> $url Feed URL
	 * @param		<constant> $method Reading method
	 */
	public function getFeed($url, $method = self::SXML)
	{
		/* Set counter to zero */
		$this->counter = 0;

		/* Method switch */
		switch($method)	{
			case 'TXT': // Rss::TXT
				try {
					return $this->txtParser($url);
				}
				catch (Exception $e) {
					throw $e;
				}
				break;
			case 'SXML': // Rss::SXML
				try {
					return $this->sXmlParser($url);
				}
				catch (Exception $e) {
					throw $e;
				}
				break;
			default:
			case 'XML': // Rss::XML
				try {
					return $this->xmlParser($url);
				}
				catch (Exception $e) {
					throw $e;
				}
				break;
		}
	}
	// }}}

	// {{{ sXmlParser
	/**
	 * Parser for the SimpleXML way.
	 *
	 * @access 		private
	 * @param		<string> $url Feed URL
	 * @return		<array> $feed Array of items
	 */
	private function sXmlParser($url)
	{
		/* Call SimpleXML on file */
		$xml = simplexml_load_file($url);

		/* Iterate */
		foreach($xml->channel->item as $item)	{
			$this->output[$this->counter]['title'] 			= $item->title;
			$this->output[$this->counter]['description'] 	= $item->description;
			$this->output[$this->counter]['link'] 			= $item->link;
			$this->output[$this->counter]['category'] 		= isset($item->category) ? $item->category : false;
			$this->output[$this->counter]['author'] 		= $item->author;
			$this->output[$this->counter]['date'] 			= $item->pubDate;
			$this->counter++;
		}

		/* Return data */
		return $this->output;
	}
	// }}}

	// {{{ xmlParser
	/**
	 * Parser for the XML way.
	 *
	 * @access 		private
	 * @param		<string> $url Feed URL
	 * @return		<array> $feed Array of items
	 */
	private function xmlParser($url)
	{
		/* Create XML parser */
		$this->parser = xml_parser_create();

		/* Set options (skip white spaces) */
		xml_parser_set_option($this->parser, XML_OPTION_SKIP_WHITE, 1);

		/* Put $url to internal storage */
		$this->feed_url = $url;

		/* Use parser within our object */
		xml_set_object($this->parser, $this);

		/* Set element handlers */
		xml_set_element_handler($this->parser, "xmlStartElement", "xmlEndElement");

		/* Set data handler */
		xml_set_character_data_handler($this->parser, "xmlCharacterData");

		/* Open feed */
		try {
			$this->xmlOpenFeed();
		}
		catch (Exception $e) {
			throw $e;
		}

		/* Return data */
		return $this->output;
	}
	// }}}

	// {{{ getFile
	/**
	 * Retrieve file contents for usage in Rss::XML
	 * and Rss::TXT
	 *
	 * @access 		private
	 * @param		<string> $url Feed URL
	 * @return		<string> $feed File contents
	 */
	private function getFile($url)
	{
		/* Initialize variables */
		$feed = false;

		/* Use cURL if possible */
		if (function_exists('curl_init')) {
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_HEADER, 0);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_USERAGENT, 'RSS Feed Reader v2.2 (http://www.phpclasses.org/package/3724-PHP-Parse-and-display-items-of-an-RSS-feed.html)');
			$feed = curl_exec($curl);
			curl_close($curl);
		}
		/* Otherwise, try to use file_get_contents() */
		else if (function_exists('file_get_contents')) {
			$feed = file_get_contents($url); // read
		}
		/* And as a last attempt, try to use fopen() */
		else {
			/* Try to open file */
			$fh = fopen($url, 'r');

			/* Iterate */
			while(!feof($fh))	{
				$feed .= fread($fh, 4096); // read
			} // while(!feof($fh))
		}

		/* If we get no results, throw an exception */
		if ($feed === false)
			throw new Exception("<div id=\"error_no_ani\"><strong>HTTP Error: </strong>couldn't connect to host</div>");

		/* Return data */
		return $feed;
	}


	// {{{ txtParser
	/**
	 * Shortcut for regex parsing with Rss::TXT
	 *
	 * @access 		private
	 * @param		<string> $url Feed URL
	 * @return		<array> $feed Array of items
	 */
	private function txtParser($url)
	{
		/* Retrieve feed content */
		try {
			$feed = $this->getFile($url);
		}
		catch (Exception $e) {
			throw $e;
		}

		/* Parse */
		$this->txtParseFeed($feed);

		/* Return data */
		return $this->output;
	}
	// }}}

	// {{{ xmlOpenFeed
	/**
	 * Reads file for usage with Rss::XML
	 *
	 * @access 		private
	 * @return		<void>
	 */
	private function xmlOpenFeed()
	{
		/* Retrieve feed content */
		try {
			$feed = $this->getFile($this->feed_url);
		}
		catch (Exception $e) {
			throw $e;
		}
		
		/* Parse */
		xml_parse($this->parser, $feed, true);

		/* Free parser */
		xml_parser_free($this->parser);
	}
	// }}}

	// {{{ xmlStartElement
	/**
	 * Item start handler for Rss::XML parser
	 *
	 * @access 		private
	 * @param		<object> $parser Parser reference
	 * @param		<string> $tag Tag
	 * @return		<void>
	 */
	private function xmlStartElement($parser, $tag)
	{
		if ($this->item === true)	{
			$this->tag = $tag;
		}
		else if ($tag === "ITEM")	{
			$this->item = true;
		}
	}
	// }}}

	// {{{ xmlCharacterElement
	/**
	 * Item content handler for Rss::XML parser
	 *
	 * @access 		private
	 * @param		<object> $parser Parser reference
	 * @param		<string> $data Content data
	 * @return		<void>
	 */
	private function xmlCharacterData($parser, $data)
	{
		/* Run only if we're inside an item */
		if ($this->item === TRUE)	{
			/* Read content tags */
			switch ($this->tag)	{
				case "TITLE":
					$this->title .= $data;
					break;
				case "CATEGORY":
					$this->category .= $data;
					break;
				case "DESCRIPTION":
					$this->description .= $data;
					break;
				case "LINK":
					$this->link .= $data;
					break;
				case "AUTHOR":
					$this->author .= $data;
					break;
				case "PUBDATE":
					$this->pubDate .= $data;
					break;
			}
		}
	}
	// }}}

	// {{{ xmlEndElement
	/**
	 * Item end handler
	 *
	 * @access 		private
	 * @param		<object> $parser Parser reference
	 * @param		<string> $tag Tag
	 * @return		<void>
	 */
	function xmlEndElement($parser, $tag)
	{
		if ($tag == 'ITEM')	{
			$this->output[$this->counter]['title'] 			= trim($this->title);
			$this->output[$this->counter]['description'] 	= trim($this->description);
			$this->output[$this->counter]['category'] 		= isset($this->category) ? trim($this->category) : false;
			$this->output[$this->counter]['link'] 			= trim($this->link);
			$this->output[$this->counter]['author'] 		= trim($this->author);
			$this->output[$this->counter]['date'] 			= trim($this->pubDate);
			$this->counter++;
			$this->title 		= false;
			$this->description 	= false;
			$this->category 	= false;
			$this->link 		= false;
			$this->author 		= false;
			$this->pubDate 		= false;
			$this->item 		= false;
		}
	}
	// }}}

	// {{{ txtParseFeed
	/**
	 * Parse feed using regexp
	 *
	 * @access 		private
	 * @param		<string> $feed Feed string
	 * @return		<void>
	 */
	private function txtParseFeed($feed)
	{
		$feed = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $feed);
		$feed = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $feed);
		preg_match_all('|<item>(.*)</item>|U', $feed, $m);
		foreach($m[1] as $item)	{
			preg_match('|<title>(.*)</title>|U', $item, $title);
			preg_match('|<link>(.*)</link>|U', $item, $link);
			preg_match('|<category>(.*)</category>|U', $item, $category);
			preg_match('|<description>(.*)</description>|U', $item, $description);
			preg_match('|<author>(.*)</author>|U', $item, $author);
			preg_match('|<pubDate>(.*)</pubDate>|U', $item, $pubdate);
			$this->output[$this->counter]['title'] 		= $title[1];
			$this->output[$this->counter]['description']= $description[1];
			$this->output[$this->counter]['link'] 		= $link[1];
			$this->output[$this->counter]['category'] 	= isset($category[1]) ? $category[1] : false;
			$this->output[$this->counter]['author']		= $author[1];
			$this->output[$this->counter]['date'] 		= $pubdate[1];
			$this->counter++;
		}
	}
	// }}}

	// {{{ destruct
	/**
	 * Destructor
	 */
	function __destruct()
	{
	}
	// }}}

}

