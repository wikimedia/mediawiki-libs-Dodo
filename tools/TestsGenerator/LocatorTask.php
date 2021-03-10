<?php

namespace Wikimedia\Dodo\Tools\TestsGenerator;

use Robo\Common\ExecOneCommand;
use Robo\Result;
use Robo\Task\BaseTask;
use Symfony\Component\Finder\Finder;

/**
 * Class TestsLocator
 *
 * @todo
 * - rename to w3c test locator
 * @package DodoTestsGenerator\Robo\Task\Locator
 *
 */
class LocatorTask extends BaseTask {

	use ExecOneCommand;

	private const W3C_TESTS = "/vendor/fgnass/domino/test/w3c/level1";

	private const WPT_TESTS = "/vendor/web-platform-tests/wpt/dom/nodes";

	/**
	 * @var Finder
	 */
	private $w3c_tests;
	/**
	 * @var Finder
	 */
	private $wpt_tests;

	/**
	 * @var Finder
	 */
	private $wpt_harness;

	/**
	 * @var Finder
	 */
	private $w3c_harness;

	/**
	 * FileLoader constructor.
	 *
	 * @param null|string $folder
	 */
	public function __construct( ?string $folder ) {
		$this->folder = $folder ?? realpath( __DIR__ . "/../.." );
	}

	/**
	 * @return Result
	 */
	public function run() : Result {
		// pick a few W3C tests
		$this->initTests();
		$this->locateHarnesses();

		if ( !$this->w3c_tests->hasResults() ) {
			return Result::error( $this,
				'No W3C tests were found.' );
		}

		if ( !$this->wpt_tests->hasResults() ) {
			return Result::error( $this,
				'No WPT tests were found.' );
		}

		$tests = [ 'w3c' => iterator_to_array( $this->w3c_tests ),
			'wpt' => iterator_to_array( $this->wpt_tests ),
			'w3c_harness' => iterator_to_array( $this->w3c_harness ),
			'wpt_harness' => iterator_to_array( $this->wpt_harness ) ];

		$w3c_count = count( $tests['w3c'] );
		$wpt_count = count( $tests['wpt'] );

		$this->printTaskInfo( 'W3C tests: ' . $w3c_count );
		$this->printTaskInfo( 'WPT tests: ' . $wpt_count );
		$this->printTaskInfo( 'Total tests: ' . ( $w3c_count + $wpt_count ) );

		return Result::success( $this,
			'All good.',
			$tests );
	}

	/**
	 * @return void
	 */
	protected function initTests() : void {
		$exclude_dirs = [ 'obsolete',
			'nyi' ];
		$w3c_tests_path = $this->folder . self::W3C_TESTS;
		$this->w3c_tests = ( new Finder() )->name( '*.js' )->exclude( $exclude_dirs )->in( $w3c_tests_path )
			->ignoreUnreadableDirs()->files()->sortByName();

		/**
		 * For now only load .html's
		 */
		$exclude_dirs = [ 'Document-createElement-namespace-tests' ];
		$wpt_tests_path = $this->folder . self::WPT_TESTS;
		$this->wpt_tests = ( new Finder() )->name( "*.html" )->in( $wpt_tests_path )->exclude( $exclude_dirs )
			->ignoreUnreadableDirs()->files()->sortByName();
	}

	/**
	 * Locates w3c and wpt harnesses
	 */
	protected function locateHarnesses() {
		$w3c_tests_path = realpath( $this->folder . self::W3C_TESTS . '/../harness' );
		$this->w3c_harness = ( new Finder() )->name( '*.js' )->in( $w3c_tests_path )->ignoreUnreadableDirs()->files()
			->sortByName();

		$wpt_tests_path = realpath( $this->folder . self::WPT_TESTS . '/../../resources' );
		$this->wpt_harness = ( new Finder() )->name( "*harness.js" )->in( $wpt_tests_path )->ignoreUnreadableDirs()
			->files()->sortByName();
	}
}
