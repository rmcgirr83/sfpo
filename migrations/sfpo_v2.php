<?php
/**
*
* @package Show First Post Only To Guest
* @copyright (c) 2016 Rich McGirr (RMcGirr83)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace rmcgirr83\sfpo\migrations;

class sfpo_v2 extends \phpbb\db\migration\migration
{
	static public function depends_on()
	{
		return array('\rmcgirr83\sfpo\migrations\sfpo_v1');
	}

	public function update_schema()
	{
		return array(
			'add_columns'	=> array(
				$this->table_prefix . 'forums'	=> array(
					'sfpo_characters'	=> array('UINT', 150),
				),
			),
		);
	}

	public function revert_schema()
	{
		return array(
			'drop_columns' => array(
				$this->table_prefix . 'forums'	=> array(
					'sfpo_characters',
				),
			),
		);
	}
}
