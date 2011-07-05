<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');	
	
/**
 * Dashboard Admin Controller
 *
 * @author 		Boris Strahija <boris@creolab.hr>
 * @copyright 	Copyright (c) 2010, Boris Strahija, Creo
 * @version 	0.3
 */
 

class Dashboard_admin extends Backend {
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function __construct()
	{
		// Call the parent constructor
		parent::__construct();
		
		// Restricted access
		Auth::restrict('editor');
		
		// Load some resources
		$this->config->load('ga/ga');
		$this->load->model('channels/channel_m');
		
		// Set navigation mark
		$this->set_nav_mark('dashboard');
		
	} //end __contruct()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function index()
	{
		// Run analytics
		if (CMS::$data->analytic_visits = $this->cache->get('analytic_visits')) {
			CMS::$data->analytic_views  = $this->cache->get('analytic_views');
		} else {
			//$this->_analytics();
		} // end if
		
		// Special view for superadmin ;)
		if (is_superadmin())
		{
			$this->view = 'superadmin';
		}
		
		// Get all channels
		CMS::$data->channels = $channels = $this->channel_m->order_by('title')->get_all();
		
	} //end index()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function ga()
	{
		$this->layout = false;
		$this->view   = false;
		$this->output->enable_profiler(false);
		
		if ($this->input->is_ajax_request()) {
			$this->_analytics();
			$this->load->view('analytics', array(
				'analytic_visits' => CMS::$data->analytic_visits,
				'analytic_views'  => CMS::$data->analytic_views,
			));
		} // end if
		
	} // end ga()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	private function _analytics()
	{
		// Check if data is cached
		if (CMS::$data->analytic_visits = $this->cache->get('analytic_visits')) {
			CMS::$data->analytic_views  = $this->cache->get('analytic_views');
		}
		
		// If it's not cached get it
		else
		{
			$this->load->library('ga/analytics', array(
				 'username' => config_item('analytics_username')
				,'password' => config_item('analytics_password')
			));
			
			// Get analytics data (credit to Phil Sturgeon and PyroCMS for this)
			try
			{
				// Set by GA Profile ID if provided, else try and use the current domain
				$this->analytics->setProfileById('ga:'.config_item('analytics_profile_id'));

				$this->analytics->setDateRange(date('Y-m-d', mktime(0,0,0,date("m"),1,date("Y"))), date('Y-m-d', mktime(0,0,0,date("m"),31,date("Y"))));

				$visits = $this->analytics->getVisitors();
				$views  = $this->analytics->getPageviews();
				
				// Biuld chart data
				if (count($visits)) {
					foreach ($visits as $date => $visit) {
						$year 	= date('Y');
						$month 	= date('m');
						$day 	= $date;
						$utc 	= mktime(date('h') + 1, NULL, NULL, $month, $day, $year) * 1000;
						$utc 	= date("M.d", mktime(0,0,0,$month,$date,$year));
						
						$chart_visits[] = array($utc, $visit);
						$chart_views[]  = array($utc, $views[$date]);
					} // end foreach
				} // end if

				CMS::$data->analytic_visits = $chart_visits;
				CMS::$data->analytic_views  = $chart_views;

				// Save to cache
				$this->cache->save('analytic_visits', 	$chart_visits, 	60 * 60 * .5); // 30 minutes
				$this->cache->save('analytic_views', 	$chart_views, 	60 * 60 * .5); // 30 minutes
			}

			// Throw error if no data received
			catch (Exception $e)
			{
				//echo '<!-- ';
				echo '<pre>'; print_r($e->getMessage()); echo '</pre>';
				//echo ' -->';
				//$data->messages['notice'] = sprintf(lang('cp_google_analytics_no_connect'), anchor('admin/settings', lang('cp_nav_settings')));
			} // end catch
		} // end if
		
	} // end _analytics()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	function mnml()
	{
		
	} // end mnml()
	
	function pictos()
	{
		
	} // end pictos()
	
	function iconic()
	{
		
	} // end iconic()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
} //end Admin_dashboard


/* End of file admin_dashboard.php */