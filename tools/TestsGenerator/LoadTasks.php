<?php

namespace Wikimedia\Dodo\Tools\TestsGenerator;

use Robo\Collection\CollectionBuilder;

/**
 * Load DodoTestsGenerator Robo tasks.
 */
trait LoadTasks {

	/**
	 * @param null|string $folder
	 *
	 * @return CollectionBuilder
	 */
	public function taskTestsLocator( ?string $folder = null ) : CollectionBuilder {
		return $this->task( LocatorTask::class,
			$folder );
	}

	/**
	 * @param string $test
	 * @param string $test_name
	 * @param string $test_type
	 *
	 * @return CollectionBuilder
	 */
	public function taskParseTest( string $test, string $test_name, string $test_type ) : CollectionBuilder {
		return $this->task( ParserTask::class,
			$test,
			$test_name,
			$test_type );
	}

}
