<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');	
	
/**
 * Comment Model
 *
 * @author 		Boris Strahija <boris@creolab.hr>
 * @copyright 	Copyright (c) 2011, Boris Strahija, Creo
 * @version 	0.2
 */

class Comment_m extends MY_Model {
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function get_latest($entry_id = null, $limit = 10)
	{
		if ($entry_id)
		{
			$latest_comments = $this->comment_m->order_by('created_at', 'DESC')->limit((int) $limit)->get_by(array('entry_id'=>$entry_id, 'status'=>'published'));
		}
		else
		{
			$latest_comments = $this->comment_m->order_by('created_at', 'DESC')->limit((int) $limit)->get_by('status', 'published');
		}
		
		return $latest_comments;
		
	} // get_latest()
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function get_children($entry_id = null, $parent_id = null)
	{
		$comments = $this->get_many_by(array(
			'entry_id'  => $entry_id,
			'parent_id' => $parent_id,
		));
		
		return $comments;
		
	} // get_children()
	
	/* ------------------------------------------------------------------------------------------ */
	
	
} //end Comment_m


/* End of file comment_m.php */