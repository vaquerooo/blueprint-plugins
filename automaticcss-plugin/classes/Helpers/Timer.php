<?php
/**
 * Automatic.css Timer file.
 *
 * @package Automatic_CSS
 */

namespace Automatic_CSS\Helpers;

/**
 * Timer class.
 *
 * @see https://www.php.net/manual/en/function.microtime.php
 */
class Timer {

	/**
	 * Time when the timer was started
	 *
	 * @var float
	 */
	private $time_start;

	/**
	 * Time when the timer was stopped
	 *
	 * @var float
	 */
	private $time_stop;

	/**
	 * Is the timer running?
	 *
	 * @var bool
	 */
	private $is_running;

	/**
	 * Undocumented function
	 */
	public function __construct() {
		$this->start();
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function start(): void {
		$this->time_start = microtime( true );
		$this->time_stop = null;
		$this->is_running = true;
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function stop(): void {
		$this->time_stop = microtime( true );
		$this->is_running = false;
	}

	/**
	 * Get the timer
	 *
	 * @param int $precision Rounding precision.
	 * @return float
	 */
	public function get_time( $precision = 2 ): float {
		if ( $this->is_running ) {
			$this->stop();
		}
		$time = $this->time_stop - $this->time_start;
		// now the integer section ($seconds) should be small enough to allow a float with 2 decimal digits.
		return round( ( $time ), $precision );
	}
}
