<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');	
	
/**
 * Entry Model
 *
 * @author 		Boris Strahija <boris@creolab.hr>
 * @copyright 	Copyright (c) 2010, Boris Strahija, Creo
 * @version 	0.3
 */

class Entry_m extends MY_Model {
	
	private $_cached = array();
	public  $lang;
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function get_extended($condition = null)
	{
		$this->set_lang();
		
		if ($condition) {
			// By status
			//$this->db->where("{$this->_table}.status !=", 'trashed');
			
			// ID
			if (is_numeric($condition)) {
				$this->db->where("{$this->_table}.id", $condition);
			} // end if
			
			// Other
			elseif (is_array($condition)) {
				foreach ($condition as $key=>$c) {
					$this->db->where("{$this->_table}.".$key, $c);
				} // end foreach
			} // end if
			
			
			// Select from DB
			$this->db->select("{$this->_table}.id, {$this->_table}.parent_id, {$this->_table}.title, {$this->_table}.body, {$this->_table}.summary, {$this->_table}.created_at, {$this->_table}.updated_at
							, {$this->_table}.published_at, {$this->_table}.user_id, {$this->_table}.user_change_id, {$this->_table}.status, {$this->_table}.channel, {$this->_table}.channel_id, {$this->_table}.slug, {$this->_table}.order_key, edit_format
							, u.username AS user_username, um.first_name AS user_first_name, um.last_name AS user_last_name, u.email AS user_email
							, mt.title AS meta_title, mt.description AS meta_description, mt.keywords AS meta_keywords, mt.redirect AS meta_redirect
							, ch.id AS trigger_id, ch.slug AS trigger_slug, ch.slug AS trigger_slug, ch.url_trigger AS trigger_url, ch.type AS trigger_type
							")
						->order_by($this->_table.".published_at", "DESC")
						->order_by($this->_table.".created_at", "DESC")
						->group_by("{$this->_table}.id")
						->join("users AS u", "u.id = {$this->_table}.user_id", "left")
						->join("user_meta AS um", "um.user_id = u.id", "left")
						->join("entry_meta_tags AS mt", "mt.entry_id = {$this->_table}.id", "left")
						->join("channels AS ch", "ch.id = {$this->_table}.channel_id", "left")
						;
			
			// Get it
			$query = $this->db->get($this->_table);
			
			// Return it
			$entry = $query->row();
			
			if ($entry) {
				$this->_cached[$entry->id] = $entry;
				return $entry;
			} // end if
			
			return null;
			
		} // end if
		
		return null;
		
	} // end get_extended()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function get_extended_with_fields($condition = null)
	{
		$this->set_lang();
		
		$entry  = $this->get_extended($condition);
		$fields = $this->fields($entry);
		
		if ($fields) {
			// Add prefix to all fields
			$pfields = array();
			foreach ($fields as $key=>$field) {
				$pfields['f_'.$key] = $field;
			} // end foreach
			
			// Combine and return
			$entry  = array_to_object(array_merge(object_to_array($entry), $pfields));
			
		} // end if
		
		return $entry;
		
	} // end get_extended_with_fields()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function get_many_extended($condition = null, $add_categories = false)
	{
		$this->set_lang();
		
		if ($condition) {
			// By status
			//$this->db->where("{$this->_table}.status !=", 'trashed');
			
			// Set conditions
			if (is_array($condition)) {
				foreach ($condition as $key=>$c) {
					$this->db->where("{$this->_table}.".$key, $c);
				} // end foreach
			} // end if
			
			
			// Select from DB
			$this->db->select("{$this->_table}.id, {$this->_table}.parent_id, {$this->_table}.title, {$this->_table}.body, {$this->_table}.summary, {$this->_table}.created_at, {$this->_table}.updated_at
							, {$this->_table}.published_at, {$this->_table}.user_id, {$this->_table}.user_change_id, {$this->_table}.status, {$this->_table}.channel, {$this->_table}.channel_id, {$this->_table}.slug, {$this->_table}.order_key, edit_format
							, u.username AS user_username, um.first_name AS user_first_name, um.last_name AS user_last_name, u.email AS user_email
							, mt.title AS meta_title, mt.description AS meta_description, mt.keywords AS meta_keywords, mt.redirect AS meta_redirect
							, ch.id AS trigger_id, ch.slug AS trigger_slug, ch.slug AS trigger_slug, ch.url_trigger AS trigger_url, ch.type AS trigger_type
							")
						->order_by($this->_table.".published_at", "DESC")
						->order_by($this->_table.".created_at", "DESC")
						->group_by("{$this->_table}.id")
						->join("users AS u", "u.id = {$this->_table}.user_id", "left")
						->join("user_meta AS um", "um.user_id = u.id", "left")
						->join("entry_meta_tags AS mt", "mt.entry_id = {$this->_table}.id", "left")
						->join("channels AS ch", "ch.id = {$this->_table}.channel_id", "left")
						
						;
			
			// Get it
			$query = $this->db->get($this->_table);
			
			// Return it
			$entries = $query->result();
			
			if ($entries) {
				foreach ($entries as $key=>$entry) {
					$this->_cached[$entry->id] = $entry;
				} // end foreach
				
				if ($add_categories) $entries = $this->add_categories($entries);
				
				return $entries;
			} // end if
			
			return null;
			
		} // end if
		
		return null;
		
	} // end get_many_extended()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 * Adds category data to entry records
	 */
	function add_categories($entries = null)
	{
		$this->set_lang();
		
		if ($entries) {
			foreach ($entries as $key=>$entry) {
				$categories = array();
				$relations = $this->entry_category_m->get_many_by('entry_id', $entry->id);
				
				if ($relations) {
					foreach ($relations as $relation) {
						$categories[] = $this->category_m->get($relation->category_id);
					} // end foreach
				} // end if
				
				// Category ID's and names as strings
				$category_names = array();
				$category_name_links = array();
				$category_ids = array();
				if ($categories) {
					foreach ($categories as $category) {
						$category_names[]     = $category->title;
						$category_ids[]       = $category->id;
						$category_name_links[] = admin_anchor('categories/edit/'.$category->id, $category->title);
					} // end foreach
				} // end if
				
				$entries[$key]->categories     = $categories;
				$entries[$key]->category_names = implode(", ", $category_names);
				$entries[$key]->category_name_links = implode(", ", $category_name_links);
				$entries[$key]->category_ids   = implode(", ", $category_ids);
			} // end foreach
		} // end if
		
		return $entries;
		
	} // end add_categories()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function fields($entry = null, $entry_id = null, $lang = null)
	{
		$this->set_lang($lang);
		
		if ($entry or $entry_id)
		{
			if ( ! $entry) $this->get_extended($entry_id);
			
			if ($entry) :
				$fields = array();
				$tmp_fields = array();
				
				// Lang
				if ( ! $this->config->item('multilang')) $lang = $this->config->item('default_lang');
				else                                     $lang = $this->lang;
				
				// Get the fields from the database
				$tmp_fields = $this->db->query("SELECT
						 fc.field_id
						,fc.entry_id
						,fc.body
						,f.title AS title
						,f.slug AS slug
					FROM ".$this->db->dbprefix('field_content')." AS fc
					INNER JOIN ".$this->db->dbprefix('fields')." AS f ON f.id = fc.field_id
					WHERE fc.entry_id = ".$entry->id."
					AND (fc.lang = '".$lang."' OR fc.lang = NULL OR fc.lang = '')")->result();
				
				// Prepare field data
				foreach ($tmp_fields as $key=>$field) :
					$fields[$field->slug] = $field->body;
				endforeach;
				
				return $fields;
			endif;
		}
		
		return null;
		
	} // end fields()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function set_lang($lang = null)
	{
		if ($lang) $this->lang = $lang;
		
		if ($this->lang) $this->_table = 'entries_'.$this->lang;
		else             $this->_table = 'entries';
		
		return $this;
		
	} // end _set_lang()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function clear_lang()
	{
		$this->lang = null;
		$this->set_lang();
		
		return $this;
		
	} // end clear_lang()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
} //end Entry_m


/* End of file entry_m.php */