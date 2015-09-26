<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// include config file
include(PATH_THIRD.'peenterest/config.php');

/**
 * Peenterest Update class
 *
 * @package        pEEnterest
 * @author         Rene Merino <rmerino@amayamedia.com>
 * @link           https://github.com/Alxmerino/pEEnterest
 * @copyright      Copyright (c) 2015, Rene Merino
 */

class ClassName extends AnotherClass
{

	/*----------------------------------------------------*/
	/*	Properties
	/*----------------------------------------------------*/

	/**
	 * This version
	 *
	 * @access 		public
	 * @var 		string
	 */
	public $version = AM_PEENTEREST_VERSION;
	
	/**
	 * EE Super object
	 *
	 * @access 		private
	 * @var 		object
	 */
	private $EE;

	/**
	 * Class name
	 *
	 * @access      private
	 * @var         array
	 */
	private $class_name;

	/**
	 * Actions used
	 *
	 * @access      private
	 * @var         array
	 * @todo 		Does it need any acctions?
	 */
	private $actions = array();

	/**
	 * Extension hooks
	 *
	 * @var        	array
	 * @access     	private
	 * @todo 		Does it need any extension hooks?
	 */
	private $hooks = array();

	/**
	 * Constructor
	 *
	 * @access 		public
	 * @return  	void
	 */
	function __construct()
	{
		// Get global object
		$this->EE =& get_instance();

		// Set class name
		$this->class_name = ucfirst(AM_PEENTEREST_PACKAGE);
	}

	/**
	 * Install the module
	 *
	 * @access 		public
	 * @return  	bool
	 */
	public function install()
	{
		// Install tables
	}

	/**
	 * Uninstall the module
	 *
	 * @return	bool
	 */
	public function uninstall()
	{
		// Remove tables
	}

	/**
	 * Update the module
	 *
	 * @access	public
	 * @param	string
	 * @return	bool
	 */
	public function update($current = '')
	{
		// Same version?
		if ($current == '' || version_compare($current, $this->version) === 0)
		{
			return false;
		}

		// Return TRUE to update version number in DB
		return true;
	}
}
