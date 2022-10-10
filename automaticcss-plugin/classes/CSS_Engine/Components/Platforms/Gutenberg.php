<?php
/**
 * Automatic.css Gutenberg class file.
 *
 * @package Automatic_CSS
 */

namespace Automatic_CSS\CSS_Engine\Components\Platforms;

use Automatic_CSS\CSS_Engine\CSS_File;
use Automatic_CSS\CSS_Engine\Components\Base;
use Automatic_CSS\Helpers\Logger;

/**
 * Automatic.css Gutenberg class.
 */
class Gutenberg extends Base implements Platform {

	/**
	 * Constructor
	 */
	public function __construct() {
	}

	/**
	 * Check if the plugin is installed and activated.
	 *
	 * @return boolean
	 */
	public static function is_active() {
		return false;
	}

}
