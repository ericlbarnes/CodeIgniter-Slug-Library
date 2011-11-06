<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter Slug Library
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the Academic Free License version 3.0
 *
 * This source file is subject to the Academic Free License (AFL 3.0) that is
 * bundled with this package in the files license_afl.txt / license_afl.rst.
 * It is also available through the world wide web at this URL:
 * http://opensource.org/licenses/AFL-3.0
 *
 * @package     CodeIgniter
 * @author      Eric Barnes
 * @copyright   Copyright (c) Eric Barnes (http://ericlbarnes.com)
 * @license     http://opensource.org/licenses/AFL-3.0 Academic Free License (AFL 3.0)
 * @link        http://code.ericlbarnes.com
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Slug Library
 *
 * Nothing but legless, boneless creatures who are responsible for creating
 * magic "friendly urls" in your CodeIgniter application. Slugs are nocturnal
 * feeders, hiding during daylight hours and should only be exposed in the uri.
 *
 * @subpackage Libraries
 */
class Slug
{
	/**
	 * Global ci
	 *
	 * @var object
	 **/
	protected $_ci = '';

	/**
	 * URI Field in the table
	 *
	 * @var string
	 */
	public $field = 'uri';

	/**
	 * The table
	 *
	 * @var string
	 */
	public $table = '';

	/**
	 * The primary id of the table
	 *
	 * @var string
	 */
	public $id = 'id';

	/**
	 * The title field
	 *
	 * @var string
	 */
	public $title = 'title';

	/**
	 * The replacement (Either underscore or dash)
	 *
	 * @var string
	 */
	public $replacement = 'dash';

	// ------------------------------------------------------------------------

	/**
	 * Setup all vars
	 *
	 * @param array $config
	 * @return void
	 */
	public function __construct($config = array())
	{
		$this->_ci =& get_instance();
		$this->set_config($config);
		log_message('debug', 'Slug Class Initialized');
	}

	// ------------------------------------------------------------------------

	/**
	 * Manually Set Config
	 *
	 * Pass an array of config vars to override previous setup
	 *
	 * @param   array
	 * @return  void
	 */
	public function set_config($config = array())
	{
		if ( ! empty($config))
		{
			foreach ($config as $key => $value)
			{
				$this->{$key} = $value;
			}
		}
	}

	// ------------------------------------------------------------------------

	/**
	 * Create a uri string
	 *
	 * This wraps into the _check_uri method to take a character
	 * string and convert into ascii characters.
	 *
	 * @param   mixed (string or array)
	 * @param   int
	 * @uses    Slug::_check_uri()
	 * @uses    Slug::create_slug()
	 * @return  string
	 */
	public function create_uri($data = '', $id = '')
	{
		if (empty($data))
		{
			return FALSE;
		}

		if (is_array($data))
		{
			if (isset($data[$this->field]) && ! empty($data[$this->field]))
			{
				return $this->_check_uri($this->create_slug($data[$this->field]), $id);
			}
			elseif (isset($data[$this->title]))
			{
				return $this->_check_uri($this->create_slug($data[$this->title]), $id);
			}
			else
			{
				return FALSE;
			}
		}
		else
		{
			return $this->_check_uri($this->create_slug($data), $id);
		}
	}

	// ------------------------------------------------------------------------

	/**
	 * Returns a string with all spaces converted to underscores (by default), accented
	 * characters converted to non-accented characters, and non word characters removed.
	 *
	 * @param   string $string the string you want to slug
	 * @param   string $replacement will replace keys in map
	 * @return  string
	 */
	public function create_slug($string)
	{
		$this->_ci->load->helper(array('url', 'text', 'string'));
		$string = convert_accented_characters($string);
		$string = strtolower(url_title($string, $this->replacement));
		return reduce_multiples($string, $this->_get_replacement(), TRUE);
	}

	// ------------------------------------------------------------------------

	/**
	 * Check URI
	 *
	 * Checks other items for the same uri and if something else has it
	 * change the name to "name_1".
	 *
	 * @param   string $uri
	 * @param   int $id
	 * @param   int $count
	 * @return  string
	 */
	private function _check_uri($uri, $id = FALSE, $count = 0)
	{
		$new_uri = ($count > 0) ? $uri.$this->_get_replacement().$count : $uri;

		// Setup the query
		$this->_ci->db->select($this->field)
			->from($this->table)
			->where($this->field, $new_uri);

		if ($id)
		{
			$this->_ci->db->where($this->id.' !=', $id);
		}

		$query = $this->_ci->db->get();

		if ($query->num_rows() > 0)
		{
			return $this->_check_uri($uri, $id, ++$count);
		}
		else
		{
			return $new_uri;
		}
	}

	// ------------------------------------------------------------------------

	/**
	 * Get the replacement type
	 *
	 * Either a dash or underscore generated off the term.
	 *
	 * @return string
	 */
	private function _get_replacement()
	{
		return ($this->replacement === 'dash') ? '-' : '_';
	}
}