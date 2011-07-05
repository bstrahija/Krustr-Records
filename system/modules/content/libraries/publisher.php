<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');	
	
/**
 * Publisher library
 *
 * @author 		Boris Strahija <boris@creolab.hr>
 * @copyright 	Copyright (c) 2011, Boris Strahija, Creo
 * @version 	0.3
 */

class Publisher extends CMS {
	
	// How long to keep entries in cache
	private $_cache_expiration = 86400; // 1 day
	
		
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function __construct()
	{
		// Get the DB methods
		$this->load->dbforge();
		$this->load->dbutil();
		$this->load->helper('date');
		
		// Get the theme setup
		@include_once(reduce_double_slashes(CMS::$current_theme_abs_path.'/core/setup.php'));
		
	} //end __contruct()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function publish($id = null, $lang = null, $clean_cache = true)
	{
		// We clean the entire cache for now
		if ($clean_cache) $this->_clean_cache($id);
		
		// Get language to publish to
		if ( ! $lang)
		{
			$lang            = KR_LANG;
			$publish_default = true;
			if ($this->config->item('multilang')) $this->entry_m->lang = KR_LANG;
		}
		else
		{
			if ($this->config->item('multilang')) $this->entry_m->lang = $lang;
		}
		
		
		// Get the content
		$entry = $this->entry_m->get_extended_with_fields($id);
		
		if ($entry)
		{
			$channel    = $this->channel_m->get($entry->channel_id);
			$fields     = $this->field_m->order_by('order_key')->get_many_by('channel_id', $channel->id);
			$table_name = 'ch_'.$lang.'_'.str_replace("-", "_", $channel->slug);
			
			// Set entry status
			$this->entry_m->update($entry->id, array('status'=>'published'));
			
			// Prepare summary
			if ( ! trim($entry->summary))
			{
				$entry->summary = character_limiter(strip_tags($entry->body), 150);
			}
			
			// Prepare default data
			$default_data = array
			(
				'id'          => $id,
				'title'       => $entry->title,
				'slug'        => $entry->slug,
				'body'        => $entry->body,
				'summary'     => $entry->summary,
				'parent_id'   => $entry->parent_id,
				'order_key'   => $entry->order_key,
				'edit_format' => $entry->edit_format,
			);
			
			// Channel data
			$default_data['channel']               = $channel->title;
			$default_data['channel_slug']          = $channel->slug;
			$default_data['channel_slug_singular'] = $channel->slug_singular;
			$default_data['channel_url_trigger']   = $channel->url_trigger;
			$default_data['channel_id']            = $channel->id;
			
			// Permalink
			if ($channel->data_type == 'page') $default_data['permalink'] = $entry->slug;
			else                               $default_data['permalink'] = $channel->slug.'/'.$entry->slug;
			
			// Meta data
			$default_data['meta_title']       = $entry->meta_title;
			$default_data['meta_description'] = $entry->meta_description;
			$default_data['meta_keywords']    = $entry->meta_keywords;
			$default_data['meta_redirect']    = $entry->meta_redirect;
			
			// Author data
			if ($entry->user_id)
			{
				$author = get_user((int) $entry->user_id);
				
				if ($author)
				{
					$default_data['author_id']           = $author->id;
					$default_data['author_username']     = $author->username;
					$default_data['author_email']        = $author->email;
					$default_data['author_display_name'] = $author->display_name;
				}
			}
			
			// User data
			if ($entry->user_change_id)
			{
				$user = get_user((int) $entry->user_change_id);
				
				if ($user)
				{
					$default_data['user_id']             = $user->id;
					$default_data['user_username']       = $user->username;
					$default_data['user_email']          = $user->email;
					$default_data['user_display_name']   = $user->display_name;
				}
			}
			
			// Prepare field data
			$field_data = array();
			
			foreach ($fields as $key=>$field)
			{
				// Slightly different for relations
				if ($field->type == 'relation')
				{
					$relation = $this->entry_relation_m->get_by(array(
						'entry_id' => $entry->id,
						'field_id' => $field->id,
					));
					$field_data['f_'.str_replace("-", "_", $field->slug)] = @$relation->related_id;
				}
				else
				{
					$field_data['f_'.str_replace("-", "_", $field->slug)] = @$entry->{'f_'.$field->slug};
				}
			}
			
			// Merge data
			$data = array_merge($default_data, $field_data);
			$data['published_at'] = $entry->published_at;
			
			// Get existing
			$existing = $this->db->where('id', $id)->get($table_name)->row();
			
			// Save it
			if ($existing)
			{
				$this->db->where('id', $id)->update($table_name, $data);
			}
			else
			{
				$this->db->insert($table_name, $data);
			}
			
			// Update cache if necessary
			$to_cache = $this->db->where('id', (int) $id)->get($table_name)->row();
			
			if ($to_cache)
			{
				// First delete the cached data
				@$this->cache->delete('entry_'.$lang.'_'.$id);
				@$this->cache->delete('entry_'.$lang.'_'.$entry->slug);
				
				// Then save it again
				$this->cache->save('entry_'.$lang.'_'.$id,          $to_cache, $this->_cache_expiration);
				$this->cache->save('entry_'.$lang.'_'.$entry->slug, $to_cache, $this->_cache_expiration);
			}
			
		} // end if($entry)
		
		
		// And now publish to the other langs
		if (@$publish_default and $this->config->item('multilang'))
		{
			foreach ($this->config->item('langs') as $key=>$l)
			{
				$this->publish($id, $key);
			}
		}
		
	} //end publish_entry()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function get_lang()
	{
		$lang = $this->session->userdata('kreditlang');
		if ( ! $lang) $lang = KR_LANG;
		return $lang;
		
	} // end get_lang()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function unpublish($id = null, $lang = null)
	{
		$this->_clean_cache();
		
		// Get language to publish to
		if ( ! $lang) {
			$lang            = KR_LANG;
			$unpublish_default = true;
			$this->entry_m->lang = KR_LANG;
		}
		else {
			$this->entry_m->lang = $lang;
		} // end if
		
		
		// Get the entry
		if ( ! $this->config->item('multilang')) $this->entry_m->clear_lang();
		$entry = $this->entry_m->get_extended_with_fields($id);
		
		if ($entry) {
			$channel    = $this->channel_m->get($entry->channel_id);
			$table_name = 'ch_'.$lang.'_'.str_replace("-", "_", $channel->slug);
			
			// Set entry status
			$this->entry_m->update($entry->id, array('status'=>'draft'));
			
			// Delete from DB
			$this->db->where('id', $id)->delete($table_name);
			
			// Delete from cache
			@$this->cache->delete('entry_'.$lang.'_'.$entry->id);
			@$this->cache->delete('entry_'.$lang.'_'.$entry->slug);
			
			
			// And now unpublish to the other langs
			if (@$unpublish_default and $this->config->item('multilang')) {
				foreach ($this->config->item('langs') as $key=>$l) {
					$this->unpublish($id, $key);
				} // end foreach
			} // end if
		} // end if
		
	} // end unpublish()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function get($id = null, $lang = null)
	{
		// Get language
		if ( ! $lang) $lang = SITE_LANG;
		
		// By ID
		if ($id and is_numeric($id))
		{
			// First try the cache
			if ($entry = $this->cache->get('entry_'.$lang.'_'.$id))
			{
				if ($entry->published_at <= now()) return $entry;
			}
			
			// The the DB
			$entry   = $this->entry_m->get_by(array(
				'id'              => (int) $id,
				'published_at <=' => now(),
			));
			
			if ( ! $entry) return null;
			
			$entry   = $this->db->where('id', (int) $entry->id)->get('ch_'.$lang.'_'.$entry->channel)->row();
			
			if ($entry)
			{
				$this->cache->save('entry_'.$lang.'_'.$id,          $entry, $this->_cache_expiration);
				$this->cache->save('entry_'.$lang.'_'.$entry->slug, $entry, $this->_cache_expiration);
				
				return $entry;
			}
		}
		
		// By slug
		elseif ($id and is_string($id))
		{
			// First try the cache
			if ($entry = $this->cache->get('entry_'.$lang.'_'.$id))
			{
				if ($entry->published_at <= now()) return $entry;
			}
			
			// The the DB
			$entry   = $this->entry_m->get_by(array(
				'slug'            => (string) $id,
				'published_at <=' => now(),
			));
			
			if ( ! $entry) return null;
			
			$entry   = $this->db->where('id', (int) $entry->id)->get('ch_'.$lang.'_'.str_replace("-", "_", $entry->channel))->row();
			
			if ($entry)
			{
				$this->cache->save('entry_'.$lang.'_'.$id,          $entry, $this->_cache_expiration);
				$this->cache->save('entry_'.$lang.'_'.$entry->slug, $entry, $this->_cache_expiration);
				
				return $entry;
			}
		}
		
	} // end get()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 * Get entry in specific category
	 * ---
	 * It is possible to get by many categories, then the $explicit parameter comes to play
	 * If it's set to true, the entry must be in all categories, when it's false it can be in any
	 *
	 */
	public function get_by_category($channel = 'pages', $categories = null, $lang = null, $explicit = false, $many = false)
	{
		// Get language
		if ( ! $lang) $lang = SITE_LANG;
		
		if ($categories)
		{
			// Prepare categories
			if ( ! is_array($categories)) $categories = array($categories);
			$categories = implode(',', $categories);
			
			// The table
			$table = 'ch_'.$lang.'_'.str_replace("-", "_", $channel);
			
			// Get the entry
			if ( ! $explicit)
			{
				$this->db->from($table.' AS e')
				         ->join('entry_category AS ec', 'ec.entry_id = e.id', 'left')
				         ->where_in('ec.category_id', $categories)
				         ->order_by('published_at', 'DESC')
				         ;
				$entry = $this->db->get()->row();
			}
			else
			{
				$query = "SELECT
						     e.*
						    ,GROUP_CONCAT(ec.category_id ORDER BY category_id ASC) AS entry_categories 
						    ,COUNT(ec.entry_id) AS category_count
						FROM ".$this->db->dbprefix($table)." AS e
						INNER JOIN ".$this->db->dbprefix('entry_category')." AS ec ON ec.entry_id = e.id
						WHERE ec.category_id IN (".$categories.")
						";
				
				// End of query
				$query .= "GROUP BY ec.entry_id
						HAVING entry_categories = '".$categories."'
						ORDER BY e.published_at DESC
						";
				
				// Return one or many
				if ($many) $entry = $this->db->query($query)->result();
				else       $entry = $this->db->query($query)->row();
			}
			
			return $entry;
		}
		
		return null;
		
	} // end get_by_category()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function get_many_by_category($channel = 'pages', $categories = null, $lang = null, $explicit = false)
	{
		return $this->get_by_category($channel, $categories, $lang, $explicit, true);
		
	} // get_many_by_category()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function update_tables($lang = null)
	{
		// Set language
		if ( ! $lang) $table_lang = KR_LANG;
		else          $table_lang = $lang;
		
		foreach (CMS::$channels as $channel) {
			$table_name = 'ch_'.$table_lang.'_'.str_replace("-", "_", $channel->slug);
			
			// Add the fields
			$this->dbforge->add_field('id');
			$db_fields = array(
				 'title'                 => array('type' => 'VARCHAR','constraint' => '255')
				,'slug'                  => array('type' => 'VARCHAR','constraint' => '255')
				,'parent_id'             => array('type' =>'INT','constraint' => '10')
				,'body'                  => array('type' =>'TEXT')
				,'edit_format'           => array('type' => 'VARCHAR','constraint' => '50')
				,'permalink'             => array('type' =>'TEXT')
				,'meta_title'            => array('type' =>'TEXT')
				,'meta_description'      => array('type' =>'TEXT')
				,'meta_keywords'         => array('type' =>'TEXT')
				,'meta_redirect'         => array('type' =>'TEXT')
				,'summary'               => array('type' =>'TEXT')
				,'channel'               => array('type' =>'TEXT')
				,'channel_slug'          => array('type' => 'VARCHAR','constraint' => '100')
				,'channel_slug_singular' => array('type' => 'VARCHAR','constraint' => '100')
				,'channel_url_trigger'   => array('type' => 'VARCHAR','constraint' => '100')
				,'channel_id'            => array('type' =>'INT','constraint' => '10')
				,'author_username'       => array('type' => 'VARCHAR','constraint' => '255')
				,'author_email'          => array('type' => 'VARCHAR','constraint' => '255')
				,'author_display_name'   => array('type' => 'VARCHAR','constraint' => '255')
				,'author_id'             => array('type' =>'INT','constraint' => '10')
				,'user_username'         => array('type' => 'VARCHAR','constraint' => '255')
				,'user_email'            => array('type' => 'VARCHAR','constraint' => '255')
				,'user_display_name'     => array('type' => 'VARCHAR','constraint' => '255')
				,'user_id'               => array('type' =>'INT','constraint' => '10')
				,'order_key'             => array('type' =>'INT','constraint' => '10')
				,'created_at'            => array('type' =>'INT','constraint' => '10')
				,'updated_at'            => array('type' =>'INT','constraint' => '10')
				,'published_at'          => array('type' =>'INT','constraint' => '10')
			);
			$this->dbforge->add_field($db_fields);
			
			// Create the table
			$this->dbforge->create_table($table_name, TRUE);
			
			// Add channel specific fields
			$ch_fields = $this->field_m->order_by('order_key')->get_many_by('channel_id', $channel->id);
			foreach ($ch_fields as $field) {
				$field_name = str_replace("-", "_", 'f_'.$field->slug);
				if ( ! $this->db->field_exists($field_name, $table_name)) {
					$this->dbforge->add_column($table_name, array($field_name=>array('type'=>'TEXT')));
				} // end if
			} // end foreach
			
		} // end foreach
		
		
		// Update tables for other languages if multilang is set
		if ( ! $lang and $this->config->item('multilang')) {
			foreach ($this->config->item('langs') as $key=>$l) {
				if ($key != $table_lang) $this->update_tables($key);
			} // end foreach
		} // end if
		
		
		// Update admin tables
		$this->update_admin_tables();
		
	} // end _update_tables()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 * Creates 'entries' tables for each language
	 *
	 */
	function update_admin_tables()
	{
		if ($this->config->item('multilang')) {
			foreach ($this->config->item('langs') as $key=>$l) {
				$table_name = 'entries_'.$key;
				
				// Add the fields
				$this->dbforge->add_field('id');
				$db_fields = array(
					 'parent_id'           => array('type' =>'INT','constraint' => '11')
					,'user_id'             => array('type' =>'INT','constraint' => '11')
					,'channel_id'          => array('type' =>'INT','constraint' => '11')
					,'channel'             => array('type' =>'VARCHAR','constraint' => '255')
					,'user_change_id'      => array('type' =>'INT','constraint' => '11')
					,'field_group_id'      => array('type' =>'INT','constraint' => '11')
					,'title'               => array('type' => 'VARCHAR','constraint' => '255')
					,'slug'                => array('type' => 'VARCHAR','constraint' => '255')
					,'body'                => array('type' =>'TEXT')
					,'edit_format'         => array('type' => 'VARCHAR','constraint' => '50')
					,'summary'             => array('type' =>'TEXT')
					,'image'               => array('type' =>'TEXT')
					,'order_key'           => array('type' =>'INT','constraint' => '11')
					,'lang'                => array('type' => 'VARCHAR','constraint' => '2')
					,'status'              => array('type' => 'VARCHAR','constraint' => '64')
					,'user_id'             => array('type' =>'INT','constraint' => '10')
					,'order_key'           => array('type' =>'INT','constraint' => '10')
					,'created_at'          => array('type' =>'INT','constraint' => '10')
					,'updated_at'          => array('type' =>'INT','constraint' => '10')
					,'published_at'        => array('type' =>'INT','constraint' => '10')
				);
				$this->dbforge->add_field($db_fields);
				
				// Create the table
				$this->dbforge->create_table($table_name, TRUE);
				
			} // end foreach
		} // end if
		
	} // end update_admin_tables()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 * Copy all published entries from admin tables to publish tables
	 *
	 */
	public function sync_published()
	{
		// First we flush the cache
		$this->cache->clean();
		
		// Get the entries and publish them
		$entries = $this->entry_m->get_many_by('status', 'published');
		foreach ($entries as $entry) {
			$this->publish($entry->id, null, false);
		} // end foreach
		
	} // end sync_published()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	private function _clean_cache($entry_id = null)
	{
		@Theme_setup::clean_cache($entry_id);
		
	} // end _clean_cache()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
} //end Publisher


/* End of file publisher.php */