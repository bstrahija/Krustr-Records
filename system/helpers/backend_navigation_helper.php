<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');	

/**
 *  Helper
 *
 * @author 		Boris Strahija <boris@creolab.hr>
 * @copyright 	Copyright (c) 2011, Boris Strahija, Creo
 * @version 	0.2
 */

/* ------------------------------------------------------------------------------------------ */


/**
 * Render a navigation unordered list
 */
function backend_navigation()
{
	// Get markers
	$nav_mark = CMS::$data->nav_mark;
	
	// Get CI instance
	$ci =& get_instance();
	
	// The nav tree
	?><ul>
		<?php
			// Get nav configuration
			$nav = $ci->config->item('tree', 'nav');
			
			// Create navigation from nav config
			if ($nav) :
				foreach ($nav as $n) :
					// The role
					if ( ! isset($n['role'])) $n['role'] = 'author';
					
					// Icon
					$icon = ''; if (isset($n['icon'])) $icon = $n['icon'].' ';
					
					// Build the link
					if (isset($n['url']) && $n['url']) :
						$n['link'] = BACKEND.'/'.$n['url'];
					else :
						$n['link'] = BACKEND.'/'.$n['controller'];
						if (isset($n['method'])) $n['link'] .= '/'.$n['method'];
					endif;
					
					// Check if user has rights
					if (Auth::user_has_access($n['role'])) :
						$n['props']['title'] = $n['label'];
						?><li<?php nav_mark(@$n['mark'], @$nav_mark[1]); ?>><?php echo anchor($n['link'], $icon.$n['label'], @$n['props']); ?><?php
						
						// Check for frop down (only if not current)
						if (@$n['mark'] != @$nav_mark[1]) echo backend_sub_navigation($n['mark'], false);
						
						?></li><?php
					endif;
				endforeach;
			endif;
		?>
	</ul><?php
	
} //end backend_navigation()


/* ------------------------------------------------------------------------------------------ */

/**
 * Render subnavigation block
 *
 */
function backend_sub_navigation($mark_override = NULL, $echo = true)
{
	// Get markers
	$nav_mark = CMS::$data->nav_mark;
	
	// Get CI instance
	$ci =& get_instance();
	
	// Get nav configuration
	$nav = $ci->config->item('tree', 'nav');
	
	// Set the comaparison mark
	$cmp_mark = @$nav_mark[1];
	if ($mark_override) $cmp_mark = $mark_override;
	
	// Output data
	$output = '';
	
	// Check if current section has a sub navigation
	foreach ($nav as $n) :
		if (($cmp_mark == $n['mark'] || $mark_override == $n['mark']) && isset($n['children'])) :
			if ( ! $mark_override) 	$output .= '<div id="subnav">';
			else 					$output .= '<div class="dd">';
			
			// Check for special subnav cases
			$children = backend_special_nav($n['children']);
			
			$output .= '<ul class="clearfix">';
			
			foreach ($children as $key=>$s) :
				// Build the link
				if (isset($s['url']) && $s['url']) :
					$s['link'] = BACKEND.'/'.$s['url'];
				else :
					$s['link'] = BACKEND.'/'.$s['controller'];
					if (isset($s['method'])) $s['link'] .= '/'.$s['method'];
				endif;
				
				// Icon
				$icon = ''; if (isset($s['icon'])) $icon = $s['icon'].' ';
				
				
				$n_role = (isset($n['role'])) ? $n['role'] : 'author';
				$role   = (isset($s['role'])) ? $s['role'] : $n_role;
				
				// Add extra class
				$extra_class = '';
				if (isset($s['extra_class'])) $extra_class = ' '.$s['extra_class'];
				
				// First / last / mid
				if ($key == 0)                             $extra_class .= ' first';
				elseif ($key == count($children) - 1) $extra_class .= ' last';
				else                                       $extra_class .= ' mid';
				
				if (Auth::user_has_access($role))
				{
					$output .= '<li class="'.((isset($nav_mark[2]) and $nav_mark[2] == $s['mark']) ? 'on' : '').@$extra_class.'">'.anchor($s['link'], $icon.$s['label']).'</li>';
				}
			endforeach;
			
			$output .= '</ul></div>';
		endif;
	endforeach;
	
	// Output it
	if ($echo) 	echo $output;
	else 		return $output;
	
} //end backend_sub_navigation()


/* ------------------------------------------------------------------------------------------ */

/**
 *
 */
function backend_special_nav($children = null)
{
	// Get CI instance
	$ci =& get_instance();
	
	// What is returned
	$return = array();
	
	foreach ($children as $key=>$child) {
		if (@$child['label'] == '*special*') {
			
			// Channels
			if ($child['contents'] == 'channels') {
				$ci->load->model('channels/channel_m');
				$channels = $ci->channel_m->order_by('order_key')->order_by('id')->get_all();
				$channel_nav = array();
				
				foreach ($channels as $channel) {
					$return[] = array(
						'label' => $channel->title,
						'url'   => 'content/all/'.$channel->slug,
						'mark'  => $channel->slug,
						'extra_class' => 'ls',
						'icon'  => '<em class="picto '.$channel->icon.'"></em>',
					);
				} // end foreach
			} // end if
		}
		else {
			$return[] = $child;
		} // end if
	} // end foreach
	
	return $return;
	
} // end backend_special_nav()


/* ------------------------------------------------------------------------------------------ */

/**
 *
 */
function breadcrumbs()
{
	// Get markers
	$nav_mark = CMS::$data->nav_mark;
	
	// Get CI instance
	$ci =& get_instance();
	
	// Get nav configuration
	$nav = $ci->config->item('tree', 'nav');
	
	$breadcrumbs = anchor(BACKEND, 'Home');
	
	foreach ($nav as $n) :
		if ($n['mark'] == @$nav_mark[1]) :
			// Build the link
			if (isset($n['url']) && $n['url']) :
				$n['link'] = BACKEND.'/'.$n['url'];
			else :
				$n['link'] = BACKEND.'/'.$n['controller'];
				if (isset($n['method'])) $n['link'] .= '/'.$n['method'];
			endif;
			
			$breadcrumbs .= ' &bull; '.anchor($n['link'], $n['label']);
			
			// Subnav
			if (isset($n['children'])) :
				foreach ($n['children'] as $s) :
					if (@$nav_mark[2] == $s['mark']) :
						// Build the link
						if (isset($s['url']) && $s['url']) :
							$s['link'] = BACKEND.'/'.$s['url'];
						else :
							$s['link'] = BACKEND.'/'.$s['controller'];
							if (isset($s['method'])) $s['link'] .= '/'.$s['method'];
						endif;
						
						$breadcrumbs .= ' &bull; '.anchor($s['link'], $s['label']);
					endif;
				endforeach;
			endif;
			
		endif;
	endforeach;
	
	return $breadcrumbs;
	
	//return '<a href="#">Home</a> / <a href="#">Newsletter</a> / <a href="#">Categories</a>';
	
} //end breadcrumbs()



/* ------------------------------------------------------------------------------------------ */

/**
 * Add marker to navigation based on the $section variable
 *
 */
function nav_mark($check, $section = NULL, $class = 'on') {
	if ($check == $section) :
		echo ' class="'.$class.'"';
	endif;
	
	return false;
} //end nav_mark();


/* End of file navigation_helper.php */
/* Location: ./app/helpers/navigation_helper.php */