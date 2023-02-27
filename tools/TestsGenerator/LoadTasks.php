<?php

declare( strict_types = 1 );

namespace Wikimedia\Dodo\Tools\TestsGenerator;

use Robo\Collection\CollectionBuilder;
use Robo\TaskAccessor;

/**
 * Loads TestsGenerator Robo tasks.
 */
trait LoadTasks {
	use TaskAccessor;

	/**
	 * @param null|string $folder
	 *
	 * @return CollectionBuilder
	 */
	public function taskTestsLocator( ?string $folder = null ): CollectionBuilder {
		return $this->task( LocatorTask::class,
			$folder );
	}

	/**
	 * @param string $test
	 * @param string $test_name
	 * @param string $test_type
	 * @param string|null $test_path
	 *
	 * @return CollectionBuilder
	 */
	public function taskParseTest( string $test, string $test_name, string $test_type,
		?string $test_path = null ): CollectionBuilder {
		return $this->task( ParserTask::class,
			$test,
			$test_name,
			$test_type,
			$test_path );
	}

}
