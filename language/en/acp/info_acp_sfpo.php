<?php
/**
*
* @package Show First Post Only To Guest
* @copyright (c) 2016 Rich McGirr (RMcGirr83)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = [];
}

$lang = array_merge($lang, [
	// ACP
	'ENABLE_SFPO' 			=> 'Enable show first post only to guest',
	'ENABLE_SFPO_EXPLAIN' 	=> 'If set to yes unregistered users / guests are able to view only the first post of any topic. The rest of the posts in the topic will ask them to login or register.',
	'SFPO_CHARACTERS'		=> 'Number of characters to display',
	'SFPO_CHARACTERS_EXPLAIN'	=> 'Enter the number of characters to display for the first topic (default is 150). Setting the value to 0 disables this feature.',
	'SFPO_CHARS'			=> 'Characters',
]);
