<?php declare(strict_types = 1);

namespace App\Model\Services;

class FeedCleaner
{

	/** @var self */
	private static $instance;

	private function __construct()
	{
	}

	public static function getInstance(): self
	{
		if (self::$instance === null) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * @param array|string $input
	 *
	 * @return array|string
	 */
	public function run($input)
	{
		if (is_string($input)) {
			return $this->clean($input);
		}

		$output = [];
		foreach ($input as $key => $value) {
			$output[$key] = $this->clean($value);
		}

		return $output;
	}

	private function clean(string $value): string
	{
		$cleaned = strip_tags($value);

		return trim($cleaned);
	}

}
