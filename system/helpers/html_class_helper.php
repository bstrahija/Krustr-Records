<?php

/**
 * The HTML class is a small HTML builder that allows you to create HTML tags without
 * having to mess with strings yourself.
 *
 * ## Example
 *
 *     $div = HTML::div(array('class' => 'grid_12'));
 *
 *     echo $div; // => '<div class="grid_12"></div>'
 *
 * Nesting data can be done by calling another function of the class as either the first
 * or second parameter of another function. If the first parameter is an array it's
 * assumed it contains the attributes to set on the element, if it's a string it's assumed
 * that it's the actual HTML to display inside the parent tag:
 *
 *     HTML::div(
 *         HTML::h1('Hello, world!');
 *     );
 *
 *     HTML::div(
 *         array('class' => 'grid_12'),
 *         HTML::h1('Hello, world!');
 *     );
 *
 * Closures are also allowed (just be sure you're returning the data in them):
 *
 *     HTML::div(array('class' => 'my_div'), function()
 *     {
 *         return HTML::h1("Hello, world!");
 *     });
 *
 * @author Yorick Peterse, Isset Internet Professionals
 * @since  16-05-2011
 * @link   http://isset.nl/
 */
abstract class HTML
{
	/**
	 * Static array containing all the self closing HTML tags and the attributes to use
	 * for the values of those tags. If the value of an item is set to NULL the HTML class
	 * will not set any data of the tag besides the specified attributes.
	 *
	 * @author Yorick Peterse
	 * @since  16-05-2011
	 * @access public
	 * @var    array
	 * @static
	 */
	public static $self_closing_tags = array(
		// <img src="hello.jpg" />
		'img'   => 'src',

		// <hr />
		'hr'    => NULL,

		// <br />
		'br'    => NULL,

		// <input type="submit" value="login" />
		'input' => 'value',

		// <meta charset="utf8" />
		'meta'  => NULL,

		// <link href="style.css" />
		'link'  => 'href'
	);

	/**
	 * Renders the HTML tag along with it's contents. If the first parameter is a string
	 * it will be used as the tag's value, if it's an array this class assumes it's the
	 * collection of attributes and their values. The tag value should in this case be set
	 * in the second parameter.
	 *
	 * Setting sub data can be done in the following two ways:
	 *
	 * 1. Calling another method of this class as a parameter.
	 * 2. Use a closure.
	 *
	 * The latter can be useful when a tag should only be added based on a certain 
	 * condition or requires extra processing:
	 *
	 *     $output = HTML::ul(function()
	 *     {
	 *         if ( $some_condition === TRUE )
	 *         {
	 *             return HTML::li('Hello, world!');
	 *         }
	 *     });
	 *
	 * Note that closures should always *return* the data manually.
	 *
	 * @example
	 *  $output  = HTML::p('Hello, world!');
	 *
	 * @author Yorick Peterse
	 * @since  16-05-2011
	 * @access public
	 * @static
	 * @param  string $method The name of the tag.
	 * @param  array  $params The parameters sent to the method that was called.
	 * @return string
	 */
	public static function __callStatic($method, $params)
	{
		$attributes = array();
		$value      = NULL;

		// Extract the value and attributes
		if ( 
			isset($params[0]) AND gettype($params[0]) === 'string' OR 
			isset($params[0]) AND gettype($params[0]) === 'object'
		)
		{
			$value = $params[0];
		}
		else if ( isset($params[0]) AND gettype($params[0]) === 'array' )
		{
			$attributes = $params[0];

			if ( isset($params[1]) )
			{
				$value = $params[1];
			}
		}

		// Is the value a closure?
		if ( is_object($value) AND strtolower(get_class($value)) === 'closure' )
		{
			$value = $value();
		}
		// It's an object but not a closure, let's give __toString a try and ignore the 
		// problem otherwise.
		else
		{
			$value = (string)$value;
		}

		// Start building the tag
		$tag = "<$method";
		$key = array_keys(self::$self_closing_tags);

		// isset() can't be used when the value of a key is NULL as it results in isset() 
		// thinking the key isn't there.
		if ( in_array($method, $key) )
		{
			$attr = self::$self_closing_tags[$method];

			if ( !empty($attr) )
			{
				$attributes[$attr] = $value;
			}

			$tag .= self::array_to_attributes($attributes) . ' />';
		}
		else
		{
			$tag  .= self::array_to_attributes($attributes) . '>';
			$tag  .= "$value</$method>";
		}

		return $tag;
	}

	/**
	 * Turns an associative into a string containing a set of attributes.
	 *
	 * @example
	 *  $array = array('value' => 'Submit');
	 *  $array = $this->array_to_attributes($array);
	 *
	 *  echo $array; // => 'value="submit"'
	 *
	 * @author Yorick Peterse
	 * @since  16-05-2011
	 * @access private
	 * @static
	 * @param  array $array The associative array to turn into a string.
	 * @return string
	 */
	private static function array_to_attributes($array)
	{
		$array_string = '';

		foreach ( $array as $attr => $value )
		{
			if ( is_array($value) )
			{
				$value = implode(' ', $value);
			}

			$array_string .= " $attr=\"$value\"";
		}

		return $array_string;
	}
}