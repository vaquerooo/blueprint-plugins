<?php
/**
 * Automatic.css Singleton class file.
 *
 * @package Automatic_CSS
 */

namespace Automatic_CSS\Traits;

use Automatic_CSS\Helpers\Logger;

trait Singleton {

	/**
	 * Stores the Plugin's instance, implementing a Singleton pattern.
	 *
	 * @see https://refactoring.guru/design-patterns/singleton/php/example
	 * @var \Automatic_CSS\Plugin
	 */
	private static $instance;

	/**
	 * The Singleton's constructor should always be private to prevent direct
	 * construction calls with the `new` operator.
	 */
	protected function __construct() { }

	/**
	 * Singletons should not be cloneable.
	 */
	protected function __clone() { }

	/**
	 * Singletons should not be restorable from strings.
	 *
	 * @throws \Exception Cannot unserialize a singleton.
	 */
	public function __wakeup() {
		throw new \Exception( 'Cannot unserialize a singleton.' );
	}

	/**
	 * This is the static method that controls the access to the singleton
	 * instance. On the first run, it creates a singleton object and places it
	 * into the static field. On subsequent runs, it returns the client existing
	 * object stored in the static field.
	 */
	public static function get_instance() {
		$cls = static::class;
		if ( ! isset( self::$instance ) ) {
			self::$instance = new static();
		}
		return self::$instance;
	}
}
