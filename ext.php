<?php
/**
*
* @package Show first post only to guest
* @copyright (c) 2016 Rich McGirr (RMcGirr83)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace rmcgirr83\sfpo;

use phpbb\extension\base;

/**
* Extension class for custom enable/disable/purge actions
*/
class ext extends base
{
	/** @var Extension name */
	const EXT_NAME = 'sfpo';

	/** @var phpBB check version */
	const PHPBB_VERSION = '3.2.0';

	/** @var PHP check version */
	const PHP_VERSION = '7.1';

	/**
	 * Enable extension if phpBB and mbstring version requirement is met
	 *
	 * @return bool
	 * @access public
	 */
	public function is_enableable()
	{
		$config = $this->container->get('config');

		$language = $this->container->get('language');
		$language->add_lang('ext_enable_error', 'rmcgirr83/' . self::EXT_NAME);

		if (!extension_loaded('mbstring'))
		{
			trigger_error($language->lang('EXT_MBSTRING_ERROR'), E_USER_WARNING);
		}

		if (!(phpbb_version_compare($config['version'], self::PHPBB_VERSION, '>=')))
		{
			trigger_error($language->lang('EXT_PHPBB_ERROR', self::PHPBB_VERSION, $config['version']), E_USER_WARNING);
		}

		if (!(phpbb_version_compare(PHP_VERSION, self::PHP_VERSION, '>=')))
		{
			trigger_error($language->lang('EXT_PHP_ERROR', self::PHP_VERSION, PHP_VERSION), E_USER_WARNING);
		}

		return true;
	}
}
