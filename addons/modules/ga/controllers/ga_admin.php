<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');	
	
/**
 * Google Analytics Admin Controller
 *
 * @author 		Boris Strahija <boris@creolab.hr>
 * @copyright 	Copyright (c) 2010, Boris Strahija, Creo
 * @version 	0.3
 */

class Ga_admin extends Backend {
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function __construct()
	{
		// Call the parent constructor
		parent::__construct();
		
		
		
	} //end __contruct()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function index()
	{
		$this->load->library('ga/analytics', array(
			'username' => 'boris@mudrakupovina.hr',
			'password' => 'creo2803boris'
		));
			
			//$this->load->driver('cache', array('adapter' => 'apc'));
			
			// Not FALSE? Return it
			if ($this->data['analytic_visits'] = $this->cache->get('analytic_visits'))
			{
				//$this->data['analytic_visits']	= $this->cache->get('analytic_visits');
				$this->data['analytic_views']  	= $this->cache->get('analytic_views');
			}

			else
			{

				try
				{
					// Set by GA Profile ID if provided, else try and use the current domain
					$this->analytics->setProfileById('ga:40859912');

					$end_date = date('Y-m-d');
					$start_date = date('Y-m-d', strtotime('-1 month'));

					$this->analytics->setDateRange($start_date, $end_date);

					$visits = $this->analytics->getVisitors();
					$views = $this->analytics->getPageviews();

					/* build tables */
					if (count($visits))
					{
						foreach ($visits as $date => $visit)
						{
							$year = substr($date, 0, 4);
							$month = substr($date, 4, 2);
							$day = substr($date, 6, 2);

							$utc = mktime(date('h') + 1, NULL, NULL, $month, $day, $year) * 1000;

							$flot_datas_visits[] = '[' . $utc . ',' . $visit . ']';
							$flot_datas_views[] = '[' . $utc . ',' . $views[$date] . ']';
						}

						$flot_data_visits = '[' . implode(',', $flot_datas_visits) . ']';
						$flot_data_views = '[' . implode(',', $flot_datas_views) . ']';
					}

					$this->data['analytic_visits'] = $flot_data_visits;
					$this->data['analytic_views'] = $flot_data_views;

					// Call the model or library with the method provided and the same arguments
					$this->cache->save('analytic_visits', 	$flot_data_visits, 	60 * 60 * 6); // 6 hours
					$this->cache->save('analytic_views', 	$flot_data_views, 	60 * 60 * 6); // 6 hours
				}

				catch (Exception $e)
				{
					echo '<!-- ';
					echo '<pre>'; print_r($e->getMessage()); echo '</pre>';
					echo ' -->';
					//$data->messages['notice'] = sprintf(lang('cp_google_analytics_no_connect'), anchor('admin/settings', lang('cp_nav_settings')));
				} // end catch
			} // end if
		
	} //end index()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
} //end 


/* End of file .php */