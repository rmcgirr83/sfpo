<?php
/**
*
* @package Show First Post Only To Guest
* @copyright (c) 2016 Rich McGirr (RMcGirr83)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace rmcgirr83\sfpo\event;

use phpbb\config\config;
use phpbb\content_visibility;
use phpbb\db\driver\driver_interface as db;
use phpbb\language\language;
use phpbb\request\request;
use phpbb\template\template;
use phpbb\user;
use rmcgirr83\sfpo\core\sfpo_trim;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
* Event listener
*/
class listener implements EventSubscriberInterface
{
	/** @var config */
	protected $config;

	/** @var content_visibility */
	protected $content_visibility;

	/** @var db */
	protected $db;

	/** @var language */
	protected $language;

	/** @var request */
	protected $request;

	/** @var template */
	protected $template;

	/** @var user */
	protected $user;

	/* @var sfpo_trim */
	protected $sfpo_trim;

	/** @var string phpBB root path */
	protected $root_path;

	/** @var string phpEx */
	protected $php_ext;

	/** @var int maxposts guests see */
	protected $maxposts;

	public function __construct(
		config $config,
		content_visibility $content_visibility,
		db $db,
		language $language,
		request $request,
		template $template,
		user $user,
		sfpo_trim $sfpo_trim,
		$root_path,
		$php_ext)
	{
		$this->config = $config;
		$this->content_visibility = $content_visibility;
		$this->db = $db;
		$this->language = $language;
		$this->request = $request;
		$this->template = $template;
		$this->user = $user;
		$this->sfpo_trim = $sfpo_trim;
		$this->root_path = $root_path;
		$this->php_ext = $php_ext;
		$this->maxposts = (int) 1;
	}

	/**
	* Assign functions defined in this class to event listeners in the core
	*
	* @return array
	* @static
	* @access public
	*/
	static public function getSubscribedEvents()
	{
		return array(
			'core.user_setup_after'						=> 'user_setup_after',
			// ACP activities
			'core.acp_extensions_run_action_after'	=>	'acp_extensions_run_action_after',
			'core.acp_manage_forums_request_data'		=> 'acp_manage_forums_request_data',
			'core.acp_manage_forums_initialise_data'	=> 'acp_manage_forums_initialise_data',
			'core.acp_manage_forums_display_form'		=> 'acp_manage_forums_display_form',
			// Viewing a topic
			'core.viewtopic_assign_template_vars_before'	=> 'viewtopic_assign_template_vars_before',
			'core.viewtopic_get_post_data'			=> 'viewtopic_get_post_data',
			'core.viewtopic_modify_post_row'		=> 'viewtopic_modify_post_row',
			// searching
			'core.search_get_posts_data'			=> 'search_get_posts_data',
			'core.search_modify_tpl_ary'			=> 'search_modify_tpl_ary',
		);
	}

	/**
	 * Add user lang stuff needed
	 *
	 * @param object $event The event object
	 * @return null
	 * @access public
	 */
	public function user_setup_after($event)
	{
		$this->language->add_lang('common', 'rmcgirr83/sfpo');
	}

	/* Display additional metadata in extension details
	*
	* @param $event			event object
	* @param return null
	* @access public
	*/
	public function acp_extensions_run_action_after($event)
	{
		if ($event['ext_name'] == 'rmcgirr83/sfpo' && $event['action'] == 'details')
		{
			$this->template->assign_var('S_BUY_ME_A_BEER_SFPO', true);
		}
	}

	// Submit form (add/update)
	public function acp_manage_forums_request_data($event)
	{
		$sfpo_array = $event['forum_data'];
		$sfpo_array['sfpo_guest_enable'] = $this->request->variable('sfpo_guest_enable', 0);
		$sfpo_array['sfpo_posts_to_show'] = $this->request->variable('sfpo_posts_to_show', 0);
		$sfpo_array['sfpo_characters'] = $this->request->variable('sfpo_characters', 0);
		$sfpo_array['sfpo_bots_allowed'] = $this->request->variable('sfpo_bots_allowed', 0);
		$event['forum_data'] = $sfpo_array;
	}

	// Default settings for new forums
	public function acp_manage_forums_initialise_data($event)
	{
		if ($event['action'] == 'add')
		{
			$sfpo_array = $event['forum_data'];
			$sfpo_array['sfpo_guest_enable'] = (int) 0;
			$sfpo_array['sfpo_posts_to_show'] = (int) 1;
			$sfpo_array['sfpo_characters'] = (int) 150;
			$sfpo_array['sfpo_bots_allowed'] = (int) 1;
			$event['forum_data'] = $sfpo_array;
		}
	}

	// ACP forums template output
	public function acp_manage_forums_display_form($event)
	{
		$sfpo_array = $event['template_data'];
		$sfpo_array['S_SFPO_ENABLED'] = true;
		$sfpo_array['S_SFPO_GUEST_ENABLE'] = $event['forum_data']['sfpo_guest_enable'];
		$sfpo_array['S_SFPO_POSTS_TO_SHOW'] = $event['forum_data']['sfpo_posts_to_show'];
		$sfpo_array['S_SFPO_CHARACTERS'] = $event['forum_data']['sfpo_characters'];
		$sfpo_array['S_SFPO_BOTS_ALLOWED'] = $event['forum_data']['sfpo_bots_allowed'];
		$event['template_data'] = $sfpo_array;
	}

	/**
	* Adjust viewtopic variables
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function viewtopic_assign_template_vars_before($event)
	{
		$topic_data = $event['topic_data'];
		$post_id = $event['post_id'];
		$start = $event['start'];
		$total_posts = $event['total_posts'];
		$this->maxposts = $this->s_sfpo($topic_data['sfpo_posts_to_show']);

		if ($this->s_sfpo($topic_data['sfpo_guest_enable'], $topic_data['sfpo_bots_allowed']))
		{
			$topic_data['prev_posts'] = $start = 0;
			$total_posts = ($total_posts <= $this->maxposts) ? $total_posts : $this->maxposts;;
			$post_id = $topic_data['topic_first_post_id'];
		}

		$event['total_posts'] = $total_posts;
		$event['topic_data'] = $topic_data;
		$event['post_id'] = $post_id;
		$event['start'] = $start;
	}

	/**
	* Get viewtopic post data and adjust if necessary
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function viewtopic_get_post_data($event)
	{
		$topic_data = $event['topic_data'];
		$sql_ary = $event['sql_ary'];
		$post_list = $event['post_list'];

		// only show the div if post_list is greater than one
		$post_list_count = count($post_list);

		if ($this->s_sfpo($topic_data['sfpo_guest_enable'], $topic_data['sfpo_bots_allowed']))
		{
			$post_list = array((int) $topic_data['topic_first_post_id']);
			$sql_ary['WHERE'] = $this->db->sql_in_set('p.post_id', $post_list) . ' AND u.user_id = p.poster_id';

			$topic_replies = $this->content_visibility->get_count('topic_posts', $topic_data, $event['forum_id']) - $this->maxposts;
			$redirect = '&amp;redirect=' . urlencode(str_replace('&amp;', '&', build_url(array('_f_'))));

			$this->template->assign_vars(array(
				'S_SFPO'	=> ($post_list_count > 1) ? true : false,
				'SFPO_MESSAGE'		=> $topic_replies ? $this->language->lang('SFPO_MSG_REPLY', $topic_replies) : '',
				'U_SFPO_LOGIN'		=> append_sid("{$this->root_path}ucp.$this->php_ext", 'mode=login' . $redirect),
			));
		}

		$event['post_list'] = $post_list;
		$event['sql_ary'] = $sql_ary;
	}

	/**
	* Adjust viewtopic message in post row
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function viewtopic_modify_post_row($event)
	{
		$topic_data = $event['topic_data'];
		$post_template = $event['post_row'];

		if ($this->s_sfpo($topic_data['sfpo_guest_enable'], $topic_data['sfpo_bots_allowed']) && !empty($topic_data['sfpo_characters']))
		{
			if (utf8_strlen($post_template['MESSAGE']) > $topic_data['sfpo_characters'])
			{
				$message = $this->sfpo_trim->trimHtml($post_template['MESSAGE'], $topic_data['sfpo_characters']);

				$redirect = '&amp;redirect=' . urlencode(str_replace('&amp;', '&', build_url(array('_f_'))));
				$link = append_sid("{$this->root_path}ucp.$this->php_ext", 'mode=login' . $redirect);

				$message .= $this->language->lang('ELLIPSIS') . $this->language->lang('SFPO_APPEND_MESSAGE', '<a href="' . $link . '">', '</a>');

				$post_template['MESSAGE'] = $message;
			}
		}

		$event['post_row'] = $post_template;
	}

	/**
	* Modify the sql search
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function search_get_posts_data($event)
	{
		$sql_array = $event['sql_array'];

		$sql_array['SELECT'] .= ', f.sfpo_guest_enable, f.sfpo_characters, f.sfpo_bots_allowed';

		$event['sql_array'] = $sql_array;
	}

	/**
	* Searching trim the message
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function search_modify_tpl_ary($event)
	{
		$sfpo_forum_ids = $this->get_sfpo_forums();
		$tpl_array = $event['tpl_ary'];
		$row = $event['row'];

		// we only care about guests..could add bots by adding
		// || $this->user->data['is_bot'] but don't think bots even search
		if (empty($this->user->data['is_registered']) && in_array((int) $row['forum_id'], $sfpo_forum_ids) && $event['show_results'] == 'posts')
		{
			if ($this->s_sfpo($row['sfpo_guest_enable'], $row['sfpo_bots_allowed']) && !empty($row['sfpo_characters']))
			{
				if (utf8_strlen($tpl_array['MESSAGE']) > $row['sfpo_characters'])
				{
					$message = $this->sfpo_trim->trimHtml($tpl_array['MESSAGE'], $row['sfpo_characters']);

					$link = append_sid("{$this->root_path}ucp.$this->php_ext", 'mode=login');

					$message .= $this->language->lang('ELLIPSIS') . $this->language->lang('SFPO_APPEND_MESSAGE', '<a href="' . $link . '">', '</a>');

					$tpl_array['MESSAGE'] = $message;
				}
			}

			$event['tpl_ary'] = $tpl_array;
		}
	}

	/**
	* Get an array of forums
	* return all forums where the extension is active
	*
	* @return forum id array
	* @access private
	*/
	private function get_sfpo_forums()
	{
		$forum_ids = array();

		$sql = 'SELECT forum_id
			FROM ' . FORUMS_TABLE . '
			WHERE sfpo_guest_enable = 1';
		$result = $this->db->sql_query($sql);
		$forums = $this->db->sql_fetchrowset($result);
		$this->db->sql_freeresult($result);

		foreach ($forums as $forum)
		{
			foreach ($forum as $id)
			{
				$forum_ids[] = $id;
			}
		}

		return $forum_ids;
	}

	/**
	* A simple switch check		just checks to see if we should apply the sfpo to the user
	*
	* @return bool
	* @access private
	*/
	private function s_sfpo($sfpo_guest_enable = false, $sfpo_bots_allowed = true)
	{
		$s_sfpo = ($sfpo_guest_enable && ($this->user->data['user_id'] == ANONYMOUS || (!empty($this->user->data['is_bot']) && !$sfpo_bots_allowed)));

		return $s_sfpo;
	}
}
