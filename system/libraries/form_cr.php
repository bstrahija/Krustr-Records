<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');	
	
/**
 * Form Library
 *
 * @author 		Boris Strahija <boris@creolab.hr>
 * @copyright 	Copyright (c) 2010, Boris Strahija, Creo
 * @version 	0.3
 */

class Form {
	
	private static $_ci;
	public $form_data = array();
	public $data_row = null; // Preset data for editing
	private static $_f = array(); // HTML container
	
	private static $_is_valid = true; // If form is valid
	private static $_is_validated = true; // If validation has been run
	
	private static $_errors = null;
	private static $_inline_errors = true; // Show inline errors
	
	private static $_form_prefix = '<ol>';
	private static $_form_sufix = '</ol>';
	private static $_field_prefix = '<li>';
	private static $_field_sufix = '</li>';
	
	private static $_errors_prefix = '<div class="message msg notice errors"><h4>Error!</h4><ul>';
	private static $_errors_sufix = '</ul></div>';
	private static $_error_single_prefix = '<li>';
	private static $_error_single_sufix= '</li>';
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function __construct($form_data = null, $data_row = null)
	{
		// Get CI instance
		self::$_ci =& get_instance();
		
		// Load some resources
		self::$_ci->load->helper('form');
		self::$_ci->load->library('form_validation');
		
		// Set the form data array
		$this->form_data 	= $form_data;
		$this->data_row 	= $data_row;
		
	} //end __contruct()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 * Returns the string with all the form html
	 *
	 */
	function get()
	{
		// First we set the validation rules
		$this->set_rules();
		
		// Then try to validate the form
		$this->validate();
		
		// Container for all string lines
		$f = self::$_f;
		
		// Start
		$f[] = form_open(current_url());
		
		// Set all fields (fieldsets first)
		if (isset($this->form_data['fieldsets'])) {
			foreach ($this->form_data['fieldsets'] as $fieldset_id=>$fieldset) {
				$f[] = form_fieldset().self::$_form_prefix;
					
				// Fieldset title
				if (isset($fieldset['title'])) $f[] = '<h3>'.$fieldset['title'].'</h3>';
				
				// Fields
				if (isset($fieldset['fields'])) {
					foreach ($fieldset['fields'] as $name=>$field) {
						// Add field and label
						$f[] = $this->_field($name, $field);
						
					} // end foreach
				} // end if
					
				$f[] = self::$_form_sufix.form_fieldset_close();
			} // end foreach
		} // end if
		
		// Add submit button
		//$f[] = '<li class="btns"><input type="submit" value="Save"></li>';
		
		// end and return
		$f[] = form_close();
		
		return implode("\n", $f);
		
	} // end get()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	private function _field($name = null, $field = null, $value = null)
	{
		// Start
		$f = array();
		$f[] = self::$_field_prefix;
		
		// Label
		if (@$field['type'] != 'submit' and isset($field['label'])) 
			$f[] = form_label($field['label']);
		
		
		// Class
		$class = null;
		if (@$field['class']) $class = @$field['class'];
		
		
		// The value
		if ( ! $value and self::$_ci->input->post($name)) 
			$value = self::$_ci->input->post($name);
		
		if ( ! $value and @$field['value']) 
			$value = $field['value'];
		
		if ( ! self::$_ci->input->post($name) and ($this->data_row and isset($this->data_row->{$name})))
			$value = $this->data_row->{$name};
		
		
		// Text field
		if ( ! isset($field['type']) or ! $field['type'] or $field['type'] == 'text') {
			$f[] = form_input($name, $value, ' class="txt '.$class.'"');
		}
		
		// Password field
		elseif ($field['type'] == 'password') {
			$f[] = form_password($name, $value, ' class="txt '.$class.'"');
		}
			
		// Select field
		elseif ($field['type'] == 'select') {
			$f[] = form_dropdown($name, @$field['options'], $value);
		}
			
		// Textarea field
		elseif ($field['type'] == 'textarea') {
			$f[] = form_textarea($name, $value, ' class="'.$class.'"');
		}
			
		// Submit button
		elseif ($field['type'] == 'submit') {
			$f[] = form_submit($name, $field['label']);
			
		} // end if
		
		
		// Inline errors
		if (self::$_inline_errors and form_error($name)) {
			$f[] = form_error($name, '<label class="error">', '</label>');
		} // end if
		
		
		// End
		$f[] = self::$_field_sufix;
		self::$_f = $f;
		return implode("\n", $f);
		
	} // end get()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function set_rules()
	{
		if (isset($this->form_data['fieldsets'])) {
			foreach ($this->form_data['fieldsets'] as $fieldset_id=>$fieldset) {
				if (isset($fieldset['fields'])) {
					foreach ($fieldset['fields'] as $name=>$field) {
						// Add form validation rules
						self::$_ci->form_validation->set_rules($name, @$field['label'], @$field['rules']);
					} // end foreach
				} // end if
			} // end foreach
		} // end if
		
	} // end set_rules()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 * Change rule for specific field
	 *
	 */
	public function set_field_rules($name = null, $rules = null)
	{
		if (isset($this->form_data['fieldsets'])) {
			foreach ($this->form_data['fieldsets'] as $fieldset_id=>$fieldset) {
				if (isset($fieldset['fields'])) {
					foreach ($fieldset['fields'] as $field_name=>$field) {
						// Add form validation rules
						if ($name == $field_name) {
							$this->form_data['fieldsets'][$fieldset_id]['fields'][$field_name]['rules'] = $rules;
						} // end if
					} // end foreach
				} // end if
			} // end foreach
		} // end if
		
	} // end set_field_rules()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	public function set_field_value($name = null, $value = null)
	{
		if (isset($this->form_data['fieldsets'])) {
			foreach ($this->form_data['fieldsets'] as $fieldset_id=>$fieldset) {
				if (isset($fieldset['fields'])) {
					foreach ($fieldset['fields'] as $field_name=>$field) {
						// Add value
						if ($name == $field_name) {
							$this->form_data['fieldsets'][$fieldset_id]['fields'][$field_name]['value'] = $value;
						} // end if
					} // end foreach
				} // end if
			} // end foreach
		} // end if
		
	} // end set_field_value()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 * Returns if form passed validation
	 *
	 */
	public function is_valid()
	{
		self::$_is_valid = $this->validate();
		return self::$_is_valid;
		
	} // end is_valid()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function validate()
	{
		$is_valid = self::$_ci->form_validation->run();
		
		self::$_errors = self::$_ci->form_validation->_error_array;
		
		return $is_valid;
		
	} // end validate()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 *
	 */
	function errors()
	{
		$errors = validation_errors(self::$_error_single_prefix, self::$_error_single_sufix);
		if ($errors) return self::$_errors_prefix . $errors . self::$_errors_sufix;
		
		return null;
		
	} // end errors()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
	/**
	 * Display the form
	 *
	 */
	function display()
	{
		echo $this->get();
		
	} // end display()
	
	
	/* ------------------------------------------------------------------------------------------ */
	
} //end Form


/* End of file form.php */