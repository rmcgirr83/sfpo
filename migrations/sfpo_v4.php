<?php
/**
*
* @package Show First Post Only To Guest
* @copyright (c) 2022 Rich McGirr (RMcGirr83)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace rmcgirr83\sfpo\migrations;

class sfpo_v4 extends \phpbb\db\migration\migration
{
	static public function depends_on()
	{
		return ['\rmcgirr83\sfpo\migrations\sfpo_v3'];
	}

	public function update_schema()
	{
		return [
			'add_index' => [
				$this->table_prefix . 'forums' => [
					'sfpo_guest_enable' => ['sfpo_guest_enable'],
				],
			],
		];
	}

	public function revert_schema()
	{
		return [
			'drop_keys' => [
				$this->table_prefix . 'forums' => [
					'sfpo_guest_enable'
				],
			],
		];
	}
}
