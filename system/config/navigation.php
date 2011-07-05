<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Navigation structure
 *
 */

$config['nav']['tree'] = array(
	 
	// !Dashboard
	 array(
	 	 'label' 		=> ''
	 	,'icon' 		=> '<em class="mnml home"></em>'
		,'url' 			=> 'dashboard'
		,'mark' 		=> 'dashboard'
	)
	
	// !Content
	,array(
	 	 'label' 		=> 'Content'
	 	,'icon' 		=> '<em class="mnml pencil"></em>'
		,'url' 			=> 'content/all/deals'
		,'mark' 		=> 'content'
		,'children' 	=> array(
			 array(
			 	 'label' 		=> '*special*'
			 	,'contents'     => 'channels'
			)
			
			,array(
			 	 'label' 		=> null
			 	,'extra_class' 	=> 'fixed ff no-drop-down'
			 	,'icon' 		=> '<em class="mnml sync"></em>'
				,'url' 			=> 'content/sync_published/r'
				,'mark' 		=> 'sync'
				,'role'         => 'superadmin'
			)
			,array(
			 	 'label' 		=> 'Comments'
			 	,'extra_class' 	=> 'fixed'
			 	,'icon' 		=> '<em class="picto comments"></em>'
				,'url' 			=> 'comments'
				,'mark' 		=> 'comments'
			)
			,array(
			 	 'label' 		=> 'Media'
			 	,'extra_class' 	=> 'fixed'
			 	,'icon' 		=> '<em class="picto photos"></em>'
				,'url' 			=> 'media'
				,'mark' 		=> 'media'
				,'role'         => 'superadmin'
			)
			,array(
			 	 'label' 		=> 'Variables'
			 	,'extra_class' 	=> 'fixed'
			 	,'icon' 		=> '<em class="picto inbox"></em>'
				,'url' 			=> 'variables'
				,'mark' 		=> 'variables'
			)
			,array(
			 	 'label' 		=> 'Categories'
			 	,'extra_class' 	=> 'fixed'
			 	,'icon' 		=> '<em class="picto list"></em>'
				,'url' 			=> 'categories'
				,'mark' 		=> 'categories'
				,'role'         => 'superadmin'
			)

		) // end children
	)
	
	// !Layout
	,array(
	 	 'label' 		=> 'Layout'
	 	,'icon' 		=> '<em class="mnml relation"></em>'
		,'url' 			=> 'fields'
		,'mark' 		=> 'layout'
		,'role' 		=> 'superadmin'
		,'children' 	=> array(
			 array(
			 	 'label' 		=> 'Fields &amp; Groups'
			 	,'icon' 		=> '<em class="picto relation"></em>'
				,'url' 			=> 'fields'
				,'mark' 		=> 'fields'
			)
			,array(
			 	 'label' 		=> 'Channels'
			 	,'icon' 		=> '<em class="picto">;</em>'
				,'url' 			=> 'channels'
				,'mark' 		=> 'channels'
			)
			,array(
			 	 'label' 		=> 'Navigation'
			 	,'icon' 		=> '<em class="picto star"></em>'
				,'url' 			=> 'navigation'
				,'mark' 		=> 'navigation'
			)
			,array(
			 	 'label' 		=> 'Themes'
			 	,'icon' 		=> '<em class="picto eye"></em>'
				,'url' 			=> 'navigation'
				,'mark' 		=> 'navigation'
			)
		) // end children
	)
	
	// !✰ Forms
	,array(
	 	 'label' 		=> 'Forms'
	 	,'icon' 		=> '<em class="mnml inbox2"></em>'
		,'url' 			=> 'forms'
		,'mark' 		=> 'forms'
		,'role' 		=> 'superadmin'
		,'children' 	=> array(
			array(
			 	 'label' 		=> 'Overview'
			 	,'icon' 		=> '<em class="mnml inbox"></em>'
				,'url' 			=> 'forms'
				,'mark' 		=> 'forms'
			),
			array(
			 	 'label' 		=> 'Entries'
			 	,'icon' 		=> '<em class="mnml mail"></em>'
				,'url' 			=> 'forms/entries'
				,'mark' 		=> 'entries'
			),
		) // end children
	)
	
	// !✰ Mailing
	,array(
	 	 'label' 		=> 'Mailing'
	 	,'icon' 		=> '<em class="mnml">@</em>'
		,'url' 			=> 'mailing'
		,'mark' 		=> 'mailing'
		,'role' 		=> 'superadmin'
		,'children' 	=> array(
			array(
			 	 'label' 		=> 'Templates'
			 	,'icon' 		=> '<em class="mnml">l</em>'
				,'url' 			=> 'templates'
				,'mark' 		=> 'templates'
			),
		) // end children
	)
	
	// !Orders
	,array(
	 	 'label' 		=> 'Orders'
	 	,'icon' 		=> '<em class="picto cart"></em>'
		,'url' 			=> 'orders'
		,'mark' 		=> 'orders'
		,'role' 		=> 'admin'
		,'children' 	=> array(
			 array(
			 	 'label' 		=> 'Overview'
			 	,'icon' 		=> '<em class="picto cart"></em>'
				,'url' 			=> 'orders/all'
				,'mark' 		=> 'orders'
			)
			,array(
			 	 'label' 		=> 'Discounts'
			 	,'icon' 		=> '<em class="picto">%</em>'
				,'url' 			=> 'orders/discounts'
				,'mark' 		=> 'discounts'
			)
			,array(
			 	 'label' 		=> 'Client list'
			 	,'icon' 		=> '<em class="picto briefcase"></em>'
				,'url' 			=> 'orders/client_list'
				,'mark' 		=> 'client_list'
			)
			,array(
			 	 'label' 		=> 'Top Users'
			 	,'icon' 		=> '<em class="picto">^</em>'
				,'url' 			=> 'orders/top_users'
				,'mark' 		=> 'top_users'
			)
		) // end children
	)
	
	// !✰ System
	,array(
	 	 'label' 		=> 'System'
	 	,'icon' 		=> '<em class="mnml cog"></em>'
		,'url' 			=> 'users'
		,'mark' 		=> 'system'
		,'role' 		=> 'admin'
		,'children' 	=> array(
			 array(
			 	 'label' 		=> 'Users'
			 	,'icon' 		=> '<em class="picto user"></em>'
				,'url' 			=> 'users'
				,'mark' 		=> 'users'
			)
			,array(
			 	 'label' 		=> 'Settings'
			 	,'icon' 		=> '<em class="picto switch"></em>'
				,'url' 			=> 'system/settings'
				,'mark' 		=> 'system/settings'
				,'role'         => 'superadmin'
			)
			,array(
			 	 'label' 		=> 'Maintenance'
			 	,'icon' 		=> '<em class="picto off"></em>'
				,'url' 			=> 'system/maintenance'
				,'mark' 		=> 'maintenance'
			)
			,array(
			 	 'label' 		=> 'Modules'
			 	,'icon' 		=> '<em class="picto star"></em>'
				,'url' 			=> 'modules'
				,'mark' 		=> 'modules'
				,'role'         => 'superadmin'
			)
		) // end children
	)

);