<?php
/**
*
* @package Show First Post Only To Guest
* @copyright (c) 2022 Rich McGirr (RMcGirr83)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace rmcgirr83\sfpo\migrations;

class sfpo_v5 extends \phpbb\db\migration\migration
{
	static public function depends_on()
	{
		return ['\rmcgirr83\sfpo\migrations\sfpo_v4'];
	}

	public function update_schema()
	{
		return [
			'add_columns'	=> [
				$this->table_prefix . 'forums'	=> [
					'sfpo_posts_to_show'	=> ['BOOL', 1],
				],
			],
		];
	}

	public function revert_schema()
	{
		return [
			'drop_columns' => [
				$this->table_prefix . 'forums' => [
					'sfpo_posts_to_show'
				],
			],
		];
	}
}
