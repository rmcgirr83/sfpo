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
	'SFPO'					=> 'Show First Post Only To Guest',
	'ENABLE_SFPO' 			=> 'Enable show first post only to guest',
	'ENABLE_SFPO_EXPLAIN' 	=> 'If set to yes unregistered users / guests are able to view only the first post of any topic. The rest of the posts in the topic will ask them to login or register.',
	'SFPO_CHARACTERS'		=> 'Number of characters to display',
	'SFPO_CHARACTERS_EXPLAIN'	=> 'Enter the number of characters to display for the first topic (default is 150). Setting the value to 0 disables this feature.',
	'SFPO_CHARS'			=> 'Characters',
	'SFPO_BOTS_ALLOWED'		=> 'Allow Bots',
	'SFPO_BOTS_ALLOWED_EXPLAIN'		=> 'If set yes registered bots on the forum will be able to see all posts in a topic.',
	//Donation
	'PAYPAL_IMAGE_URL'          => 'https://www.paypalobjects.com/webstatic/en_US/i/btn/png/silver-pill-paypal-26px.png',
	'PAYPAL_ALT'                => 'Donate using PayPal',
	'BUY_ME_A_BEER_URL'         => 'https://paypal.me/RMcGirr83',
	'BUY_ME_A_BEER'				=> 'Buy me a beer for creating this extension',
	'BUY_ME_A_BEER_SHORT'		=> 'Make a donation for this extension',
	'BUY_ME_A_BEER_EXPLAIN'		=> 'This extension is completely free. It is a project that I spend my time on for the enjoyment and use of the phpBB community. If you enjoy using this extension, or if it has benefited your forum, please consider <a href="https://paypal.me/RMcGirr83" target="_blank" rel="noreferrer noopener">buying me a beer</a>. It would be greatly appreciated. <i class="fa fa-smile-o" style="color:green;font-size:1.5em;" aria-hidden="true"></i>',
]);
