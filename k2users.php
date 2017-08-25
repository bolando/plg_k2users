<?php
/**
 * @version    2.7.x
 * @package    K2
 * @author     JoomlaWorks http://www.joomlaworks.net
 * @copyright  Copyright (c) 2006 - 2016 JoomlaWorks Ltd. All rights reserved.
 * @license    GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die ;

jimport('joomla.plugin.plugin');
jimport('joomla.html.parameter');

class plgSearchK2users extends JPlugin
{

    public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}

	// Define a function to return an array of search areas. Replace 'nameofplugin' with the name of your plugin.
	// Note the value of the array key is normally a language string
	function onContentSearchAreas()
	{
		static $areas = array(
			'k2users' => 'K2users'
		);
		return $areas;
	}
	/**
	 * The sql must return the following fields that are used in a common display
	 * routine: href, title, section, created, text, browsernav
	 *
	 * @param string Target search string
	 * @param string mathcing option, exact|any|all
	 * @param string ordering option, newest|oldest|popular|alpha|category
	 * @param mixed An array if the search it to be restricted to areas, null if search all
	 */
	function onContentSearch( $text, $phrase='', $ordering='', $areas=null )
	{
		$user	= JFactory::getUser(); 
		$groups	= implode(',', $user->getAuthorisedViewLevels());

		if (is_array( $areas )) {
			if (!array_intersect( $areas, array_keys( $this->onContentSearchAreas() ) )) {
				return array();
			}
		}
		$text = trim( $text );

		if ($text == '') {
			return array();
		}
		$wheres = array();
		$db = JFactory::getDbo();

		$query	= $db->getQuery(true);
		$query->select('a.userName AS title, "" AS created, a.userID AS id');
		$query->from('#__k2_users AS a');
		$query->where('LOWER(a.userName) LIKE \'%'.$text.'%\'');

		// Set query
		$db->setQuery( $query, 0, $limit );
		$rows = $db->loadObjectList();

		foreach($rows as $key => $row) {
			$rows[$key]->href = 'index.php?option=com_k2&view=itemlist&task=user&id='.$row->id;
		}
	return $rows;
	}

}
