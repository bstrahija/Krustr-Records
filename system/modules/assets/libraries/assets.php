<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');	
	
/**
 * Assets Library
 *
 * @author 		Boris Strahija <boris@creolab.hr>
 * @copyright 	Copyright (c) 2010, Boris Strahija, Creo
 * @version 	0.6.0
 */

define('ASSETS_VERSION', '0.6.0');


class Assets {
	
	protected static $ci;
	protected static $less;
	
	
	// Paths and folders
	public static $assets_dir;
	public static $base_path;
	public static $base_url;
	
	public static $js_dir;
	public static $js_path;
	public static $js_url;
	
	public static $css_dir;
	public static $css_path;
	public static $css_url;
	
	public static $cache_dir;
	public static $cache_path;
	public static $cache_url;
	
	
	// Files that should be processed
	private static $_js;
	private static $_css;
	
	
	// Config
	public static $combine              = true;  // Combine files
	public static $combine_js           = true;
	public static $combine_css          = true;
	public static $minify               = false; // Minify all
	public static $minify_js            = true;
	public static $minify_css           = true;
	public static $less_css             = true;
	public static $auto_clear_cache     = false; // Automaticly clear all cache before creating new cache files
	public static $auto_clear_css_cache = false; // Or clear just cached CSS files
	public static $auto_clear_js_cache  = false; // Or just cached JS files
	public static $html5                = true;  // Use HTML5 tags
	public static $auto_protocol        = false; // Auto detect HTTPS protocol
	public static $env                  = 'production';
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function __construct($cfg = null)
	{
		self::$ci =& get_instance();
		
		// Load the resources and config
		if ( ! $cfg) self::$ci->config->load('assets/assets');
		self::$ci->load->helper(array('url', 'file', 'directory', 'string', 'assets/assets'));
		self::$ci->load->library(array('assets/lessc'));
		
		// Load JSMin
		include(reduce_double_slashes(APPPATH.'modules/assets/libraries/jsmin.php'));
		
		// Load CSSMin
		include(reduce_double_slashes(APPPATH.'modules/assets/libraries/cssmin.php'));
		
		// Initialize LessPHP
		self::$less = new lessc();
		
		// Add config to library
		if ($cfg)
		{
			self::configure(array_merge($cfg), config_item('assets'));
		}
		else
		{
			self::configure(config_item('assets'));
		}
		
	} // __contruct()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 * Add new CSS file for processing
	 *
	 */
	public static function css($file = null)
	{
		if ($file)
		{
			// Multiple files as array are supported
			if (is_array($file))
			{
				foreach ($file as $f)
				{
					self::css($f);
				}
			}
			
			// Single file
			else
			{
				self::$_css[] = $file;
			}
		}
		
	} // css()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 * Add new JS file for processing
	 *
	 */
	public static function js($file = null)
	{
		if ($file)
		{
			// Multiple files as array are supported
			if (is_array($file))
			{
				foreach ($file as $f)
				{
					self::js($f);
				}
			}
			
			// Single file
			else
			{
				self::$_js[] = $file;
			}
		}
				
	} // js()
	
	
	
	/* ------------------------------------------------------------------------------------------ */
	/* !/===> Processing files, generating HTML tags */
	/* ------------------------------------------------------------------------------------------ */
	
	
	/**
	 *
	 */
	public static function get($type = 'all')
	{
		$html = '';
		
		if ($type == 'all')
		{
			$html .= self::_get_css();
			$html .= self::_get_js();
		}
		elseif ($type == 'css')
		{
			$html .= self::_get_css();
		}
		elseif ($type == 'js')
		{
			$html .= self::_get_js();
		}
		
		return $html;
		
	} // get()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	private static function _get_css()
	{
		$html = '';
		
		if (self::$_css)
		{
			// Simply return a list of all css tags
			if (self::$env == 'dev' or ( ! self::$combine and ( ! self::$minify and ! self::$minify_css) and ! self::$less_css))
			{
				foreach (self::$_css as $css)
				{
					$html .= self::_tag(self::$css_url.'/'.$css);
				}
			
			}
			else
			{
				// Try to cache assets and get html tag
				$files = self::_cache_assets(self::$_css, 'css');
				
				// Add to html
				foreach ($files as $file)
				{
					$html .= self::_tag($file);
				}
			}
		}
		
		return $html;
		
	} // _get_css()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	private static function _get_js()
	{
		$html = '';
		
		if (self::$_js)
		{
			// Simply return a list of all css tags
			if (self::$env == 'dev' or ( ! self::$combine and ( ! self::$minify and ! self::$minify_js)))
			{
				foreach (self::$_js as $js)
				{
					$html .= self::_tag(self::$js_url.'/'.$js);
				}
			}
			else
			{
				// Try to cache assets and get html tag
				$files = self::_cache_assets(self::$_js, 'js');
				
				// Add to html
				foreach ($files as $file)
				{
					$html .= self::_tag($file);
				}
			}
		}
		
		return $html;
		
	} // _get_js()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 * Caches the assets if needed and returns a list files/paths
	 */
	private static function _cache_assets($assets = null, $type = null)
	{
		$files = array(); // Will contain all the processed files
		
		if ($assets and $type)
		{
			$last_modified = 0;
			$path          = ($type == 'css') ? self::$css_path : self::$js_path ;
			
			if (self::$combine)
			{
				// Find last modified file
				foreach ($assets as $asset)
				{
					$last_modified 	= max($last_modified, filemtime(realpath($path.'/'.$asset)));
				}
				
				// Now check if the file exists in the cache directory
				$file_name = date('YmdHis', $last_modified).'.'.$type;
				$file_path = reduce_double_slashes(self::$cache_path.'/'.$file_name);
				
				if ( ! file_exists($file_path))
				{
					$data = '';
					
					// Get file contents
					foreach ($assets as $asset)
					{
						// Get file contents
						$contents = read_file(reduce_double_slashes($path.'/'.$asset));
						$pathinfo = pathinfo($asset);
						if ($pathinfo['dirname'] != '.') 	$base_url = self::$css_url.'/'.$pathinfo['dirname'];
						else 								$base_url = self::$css_url;
						
						// Process
						$data .= self::_process($contents, $type, 'minify', $base_url);
					}
					
					// Process with less and minify
					if ($type == 'css')
					{
						$data = self::_process($data, $type, 'less');
						$data = self::_process($data, $type, 'minify', $base_url);
					}
					
					// Auto clear cache directory?
					if ($type == 'css' and (self::$auto_clear_cache or self::$auto_clear_css_cache))
					{
						self::clear_css_cache();
					}
					
					if ($type == 'js' and (self::$auto_clear_cache or self::$auto_clear_js_cache))
					{
						self::clear_js_cache();
					}
					
					// And save the file
					write_file($file_path, $data);
				}
				
				// Add to files
				$files[] = self::$cache_url.'/'.$file_name;
			}
			
			// No combining
			else
			{
				foreach ($assets as $asset)
				{
					$last_modified 	= filemtime(realpath($path.'/'.$asset));
					
					// Now check if the file exists in the cache directory
					$file 		= pathinfo($asset);
					$file_name 	= date('YmdHis', $last_modified).'.'.$file['filename'].'.'.$type;
					$file_path 	= reduce_double_slashes(self::$cache_path.'/'.$file_name);
					
					if ( ! file_exists($file_path))
					{
						// Get file contents
						$data = read_file(reduce_double_slashes($path.'/'.$asset));
						
						// Process
						$data = self::_process($data, $type, 'all', site_url(self::$css_url));
						
						// Auto clear cache directory?
						if ($type == 'css' and (self::$auto_clear_cache or self::$auto_clear_css_cache))
						{
							self::clear_css_cache($asset);
						}
						
						if ($type == 'js' and (self::$auto_clear_cache or self::$auto_clear_js_cache))
						{
							self::clear_js_cache($asset);
						}
						
						// And save the file
						write_file($file_path, $data);
					}
					
					// Add to files
					$files[] = reduce_double_slashes(self::$cache_url.'/'.$file_name);
				}
			}
		}
		
		return $files;
		
	} // _cache_assets()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 * Minify, less
	 *
	 */
	private static function _process($data = null, $type = null, $do = 'all', $base_url = null)
	{
		if ( ! $base_url) $base_url = self::$base_url;
		
		if ($type == 'css')
		{
			if (self::$less_css and ($do == 'all' or $do == 'less'))
			{
				$data = self::$less->parse($data);
			}
			
			if ((self::$minify or self::$minify_css) and ($do == 'all' or $do == 'minify'))
			{
				$data = CSSMin::minify($data, array(
					//'currentDir'          => $current_dir,
				));
			}
		}
		else
		{
			self::$ci->benchmark->mark('Process_JS_assets_start');
			if ((self::$minify or self::$minify_js) and ($do == 'all' or $do == 'minify'))
			{
				$data = JSMin::minify($data);
			}
			self::$ci->benchmark->mark('Process_JS_assets_end');
		}
		
		return $data;
		
	} // _process()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	private static function _tag($file = null, $type = null)
	{
		// Try to figure out a type if none passed
		if ( ! $type)
		{
			$type = substr(strrchr($file,'.'),1);
		}
		
		// Now return CSS html tag
		if ($file and $type == 'css')
		{
			if (self::$html5) {
				return '<link rel="stylesheet" href="'.$file.'">'.PHP_EOL;
			}
			else
			{
				return '<link rel="stylesheet" type="text/css" href="'.$file.'" />'.PHP_EOL;
			}
		}
		
		// And the JS html tag
		elseif ($file and $type == 'js')
		{
			if (self::$html5)
			{
				return '<script src="'.$file.'"></script>'.PHP_EOL;
			}
			else
			{
				return '<script src="'.$file.'" type="text/javascript" charset="utf-8"></script>'.PHP_EOL;
			}
		}
		
		return null;
		
	} // _tag()
	
	
	
	/* ------------------------------------------------------------------------------------------ */
	/* !/===> Displaying assets */
	/* ------------------------------------------------------------------------------------------ */
	
	
	/**
	 *
	 */
	public static function display($type = 'all', $css = null, $js = null, $cfg = null)
	{
		// Configuration
		if ($cfg) self::configure($cfg);
		
		// Overwrite CSS files
		if ($css)
		{
			self::$_css = array();
			self::css($css);
		}
		
		// Overwrite JS files
		if ($js)
		{
			self::$_js = array();
			self::js($js);
		}
		
		// Display all the tags
		echo self::get($type);
		
	} // display()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public static function display_css($assets = null, $cfg = null)
	{
		self::display('css', $assets, null, $cfg);
		
	} // display_css()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public static function display_js($assets = null, $cfg = null)
	{
		self::display('js', null, $assets, $cfg);
		
	} // display_js()
	
	
	
	/* ------------------------------------------------------------------------------------------ */
	/* !/===> Deleting files */
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public static function clear_cache($type = null, $asset_file = null)
	{
		$files = directory_map(self::$cache_path, 1);
		
		if ($files)
		{
			foreach ($files as $file)
			{
				if ( ! is_array($file))
				{
					$file_path = reduce_double_slashes(self::$cache_path.'/'.$file);
					$file_info = pathinfo($file_path);
					
					// Clear single file cache
					if ($asset_file)
					{
						$dev_file_name = substr($file, 15); // Get the real filename, without the timestamp prefix
						
						// Compare file name and remove if necesary
						if ($dev_file_name == $asset_file)
						{
							unlink($file_path);
							//echo 'Deleted asset: '.$file."<br>\n";
						}
					}
					
					// Or all files
					else
					{
						if (is_file($file_path) and $file_info)
						{
							// Delete the CSS files
							if ($file_info['extension'] == 'css' and ( ! $type or $type == 'css'))
							{
								unlink($file_path);
								//echo 'Deleted CSS: '.$file."<br>\n";
							}
							
							// Delete the JS files
							if ($file_info['extension'] == 'js' and ( ! $type or $type == 'js'))
							{
								unlink($file_path);
								//echo 'Deleted JS: '.$file."<br>\n";
							}
						}
					}
				}
			}
		}
		
	} // clear_cache()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public static function clear_css_cache($asset_file = null)
	{
		return self::clear_cache('css', $asset_file);
		
	} // empty_css_cache()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public static function clear_js_cache($asset_file = null)
	{
		return self::clear_cache('js', $asset_file);
		
	} // empty_js_cache()
	
	
	
	/* ------------------------------------------------------------------------------------------ */
	/* !/===> Configuration */
	/* ------------------------------------------------------------------------------------------ */
	
	
	/**
	 * Configure the library
	 *
	 */
	public static function configure($cfg = null)
	{
		$cfg = array_merge($cfg, config_item('assets'));
		
		if ($cfg and is_array($cfg))
		{
			foreach ($cfg as $key=>$val)
			{
				self::$$key = $val;
				//echo 'CONFIG: ', $key, ' :: ', $val, '<br>';
			}
		}
		
		// Prepare all the paths and URI's
		self::_paths();
		
	} // configure()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	private static function _paths()
	{
		// Set the assets base path
		self::$base_path = reduce_double_slashes(realpath(self::$assets_dir));
		dump( self::$assets_dir );
		
		// Now set the assets base URL
		self::$base_url = reduce_double_slashes(config_item('base_url').'/'.self::$assets_dir);
		
		// And finally the paths and URL's to the css and js assets
		self::$js_path 		= reduce_double_slashes(self::$base_path .'/'.self::$js_dir);
		self::$css_path 	= reduce_double_slashes(self::$base_path .'/'.self::$css_dir);
		self::$cache_path 	= reduce_double_slashes(self::$base_path .'/'.self::$cache_dir);
		
		dump(self::$js_path);
		
		// URL's
		self::$js_url 		= reduce_double_slashes(self::$base_url  .'/'.self::$js_dir);
		self::$css_url 		= reduce_double_slashes(self::$base_url  .'/'.self::$css_dir);
		self::$cache_url 	= reduce_double_slashes(self::$base_url  .'/'.self::$cache_dir);
		
		// Option to auto discover the protocol
		if (self::$auto_protocol)
		{
			self::$js_url    = str_replace(array('http://', 'https://'), array('//', '//'), self::$js_url);
			self::$css_url   = str_replace(array('http://', 'https://'), array('//', '//'), self::$css_url);
			self::$cache_url = str_replace(array('http://', 'https://'), array('//', '//'), self::$cache_url);
		}
		
		// Check if all directories exist
		if ( ! is_dir(self::$js_path))
		{
			if ( ! @mkdir(self::$js_path, 0755))    exit('Error with JS directory.');
		}
		
		if ( ! is_dir(self::$css_path))
		{
			if ( ! @mkdir(self::$css_path, 0755))   exit('Error with CSS directory.');
		}
		
		if ( ! is_dir(self::$cache_path))
		{
			if ( ! @mkdir(self::$cache_path, 0777)) exit('Error with CACHE directory.');
		}
		
		// Try to make the cache direcory writable
		if (is_dir(self::$cache_path) and ! is_really_writable(self::$cache_path))
		{
			@chmod(self::$cache_path, 0777);
		}
		
		// If it's still not writable throw error
		if ( ! is_dir(self::$cache_path) or ! is_really_writable(self::$cache_path))
		{
			exit('Error with CACHE directory.');
		}
		
	} // _paths()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
} //end Assets


/* End of file assets.php */