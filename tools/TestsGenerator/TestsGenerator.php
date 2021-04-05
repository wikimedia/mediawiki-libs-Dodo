<?php

declare( strict_types=1 );

namespace Wikimedia\Dodo\Tools\TestsGenerator;

use Exception;
use Robo\Exception\TaskException;
use Robo\Result;
use Robo\Tasks;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

/**
 * Class TestsGenerator
 */
class TestsGenerator extends Tasks {

	use LoadTasks;
	use \Robo\Task\Testing\Tasks;
	use \Robo\Task\File\Tasks;
	use \Robo\Task\Filesystem\Tasks;
	use \Robo\Task\Base\Tasks;
	use \Robo\Task\Npm\Tasks;
	use \Robo\Common\IO;
	use Helpers;

	public const W3C = "W3c";
	public const WPT = "Wpt";

	private const W3C_TESTS = "/vendor/fgnass/domino/test/w3c";

	private const WPT_TESTS = "/vendor/web-platform-tests/wpt";

	/**
	 * @var string
	 */
	public $folder;

	/**
	 * @var string
	 */
	public $root_folder;

	/**
	 * @var Filesystem
	 */
	private $filesystem;

	/**
	 * @var array
	 */
	private $tests;

	/**
	 * @var bool
	 */
	private $debug;

	/**
	 * TestsGenerator constructor.
	 */
	public function __construct() {
		$this->debug = true;
		$this->filesystem = new Filesystem();
		$this->folder = __DIR__;
		$this->root_folder = realpath( $this->folder . "/../.." );
		$this->tests = [];
	}

	/**
	 * Main task
	 *
	 * @param array $opts
	 * - rewrite
	 * - phpcbf
	 * - run
	 * - cleanup
	 * - compact = generates all tests in one file
	 */
	public function build( array $opts = [ 'rewrite' => true,
		'limit' => -1,
		'phpcbf' => true,
		'run' => false,
		'cleanup' => false,
		'compact' => false ] ) {
		try {
			$compact_tests = '';
			// init and check for dependencies
			$this->initDependencies( $opts['rewrite'] );

			$this->stopOnFail( false );

			// locate W3C and WPT tests
			$result = $this->taskTestsLocator( $this->root_folder )->run();

			if ( !$result->wasSuccessful() || $result->wasCancelled() ) {
				throw new Exception( $result->getMessage() );
			}

			$files = $result->getData();

			if ( empty( $files ) ) {
				throw new Exception( 'No tests were loaded.' );
			}

			foreach ( $result->getData() as $test_type => $tests ) {
				if ( $test_type === 'time' ) {
					continue;
				}

				if ( $test_type === self::WPT ) {
					$opts['compact'] = false;
				}

				$tests_per_type = $opts['limit'];

				foreach ( $tests as $file ) {
					if ( $tests_per_type-- === 0 ) {
						break;
					}

					$test_name = $file->getFilenameWithoutExtension();
					$new_test_name = $this->snakeToPascal( $test_name );

					// TODO comment
					$test_path = str_replace( strtolower( $this->root_folder .
						( $test_type === self::W3C ? self::W3C_TESTS : self::WPT_TESTS ) ),
						'',
						$file->getPath() );

					// eg. /level1/core -> /Level1/Core
					$test_path = ucwords( $test_path, '/' );
					$test_path = "{$this->root_folder}/tests/{$test_type}{$test_path}/{$new_test_name}Test.php";
					/**
					 * skip test if it's already generated and there is no --rewrite arg provided,
					 * also skip hard to parse tests
					 */
					if ( !$opts['rewrite'] && $this->filesystem->exists( $test_path ) ) {
						$this->say( 'Skipped ' . $test_name );
						continue;
					}

					$actual_test = $this->transpileFile( $file );

					if ( empty( $actual_test ) ) {
						$this->say( 'Skipped ' . $file );
						continue;
					}

					$actual_test = $this->taskParseTest( $actual_test,
						$test_name,
						$test_type,
						$opts['compact'],
						false,
						$file->getRealPath() )->run();

					if ( !$actual_test->wasSuccessful() ) {
						throw new Exception( $actual_test->getMessage() );
					}

					if ( $opts['compact'] ) {
						$compact_tests .= $actual_test->getData()[0];
					} else {
						$phpUnitTest = $actual_test->getData()[0];
						$this->writeTest( $test_path,
							$phpUnitTest );
					}
				}

				// If compact mode is on.
				if ( $opts['compact'] && !empty( $compact_tests ) ) {
					$actual_test = $this->taskParseTest( $compact_tests,
						$test_type,
						$test_type,
						true,
						true )->run();

					if ( !$actual_test->wasSuccessful() ) {
						throw new Exception( $actual_test->getMessage() );
					}
					$phpUnitTest = $actual_test->getData()[0];
					$test_path = "{$this->root_folder}/tests/{$this->snakeToPascal($test_type)}Test.php";
					$this->writeTest( $test_path,
						$phpUnitTest );
					$compact_tests = '';
				}
			}

			// Run phpcbf.
			if ( $opts['phpcbf'] ) {
				$this->taskExec( 'composer fix' )->run();
			}

			// Copy html files for tests.
			$result = $this->copyFiles();
			if ( !$result->wasSuccessful() || $result->wasCancelled() ) {
				throw new Exception( $result->getMessage() );
			}

			// Run phpunit.
			if ( $opts['run'] ) {
				// Regenerate autoload file.
				$result = $this->taskExec( 'composer dump' )->run();

				if ( !$result->wasSuccessful() || $result->wasCancelled() ) {
					throw new Exception( $result->getMessage() );
				}

				// Run tests.
				$result = $this->taskExec( 'composer phpunit' )->run();
				if ( !$result->wasSuccessful() || $result->wasCancelled() ) {
					throw new Exception( $result->getMessage() );
				}

				$this->processLog();
			}

			if ( $opts['cleanup'] ) {
				$this->cleanUp();
			}
		} catch ( TaskException | Exception $e ) {
			$this->yell( $e->getFile() . ':' . $e->getMessage(),
				100,
				'red' );
			$this->yell( $e->getTraceAsString(),
				100,
				'red' );
		}
	}

	/**
	 * Checks for node dependencies.
	 *
	 * @param bool $rewrite
	 *
	 * @throws TaskException
	 */
	public function initDependencies( bool $rewrite = false ) {
		$result = $this->_copyDir( $this->folder . '/Harness/Wpt',
			$this->root_folder . '/tests/Wpt/Harness' );

		if ( !$result->wasSuccessful() ) {
			throw new Exception( 'No WPT harness.' );
		}

		$result = $this->_copyDir( $this->folder . '/Harness/W3c',
			$this->root_folder . '/tests/W3c/Harness' );

		if ( !$result->wasSuccessful() ) {
			throw new Exception( 'No W3C harness.' );
		}

		$harnesses_skels = ( new Finder() )->name( "*.skel" )->in( $this->root_folder . '/tests' )->files()
			->sortByName();

		if ( $harnesses_skels->count() ) {
			foreach ( $harnesses_skels as $file ) {
				$proper_file_name = $file->getPath() . '/' . $file->getFilenameWithoutExtension();
				if ( $this->filesystem->exists( $proper_file_name ) ) {
					$this->filesystem->remove( $proper_file_name );
				}

				$this->filesystem->rename( $file->getRealPath(),
					$proper_file_name );
			}
		}

		// check if js2php is installed
		if ( !$this->taskExecStack()->stopOnFail()->dir( $this->root_folder )->exec( 'npm list | grep js2php' )
			->printOutput( false )->run()->getMessage() ) {

			$this->taskNpmInstall()->run();
		}

		// Not sure about Robo\ResultData return type, looks excessive.
		if ( !$this->filesystem->exists( $this->root_folder . '/tests/_w3c' ) ) {
			$domino_path = $this->root_folder . '/vendor/fgnass/domino';
			if ( !$this->filesystem->exists( $domino_path ) ) {
				if ( !$this->taskComposerInstall()->dev( true )->run()->wasSuccessful() ) {
					throw new Exception( 'No DominoJS found.' );
				}
			}
		}

		// Source tests.
		if ( !$this->filesystem->exists( $this->root_folder . '/tests/_wpt' ) ) {
			/* make sure there are wpt tests */
			$wpt_path = $this->root_folder . '/vendor/web-platform-tests/wpt';
			if ( !$this->filesystem->exists( $wpt_path ) ) {
				if ( !$this->taskComposerInstall()->dev( true )->run()->wasSuccessful() ) {
					throw new Exception( 'No WPT tests found.' );
				}
			}
		}
	}

	/**
	 * Transpiles a file.
	 * TODO rewrite this mess.
	 *
	 * @param SplFileInfo $file
	 *
	 * @return string
	 * @throws Exception
	 */
	protected function transpileFile( SplFileInfo $file ) : string {
		$file_path = $file->getRealPath();
		$remove = false;
		$other_scripts = [];

		if ( $file->getExtension() === 'html' ) {
			$file_content = $file->getContents();
			preg_match_all( '#<script>(.*?)</script>#is',
				$file_content,
				$matches );

			preg_match_all( '/<script src="(.*)"><\/script>/',
				$file_content,
				$includes );

			if ( !empty( $includes[1] ) ) {
				$defaults = [ '/resources/testharness.js',
					'/resources/testharnessreport.js', ];

				$scripts_diff = array_diff( $includes[1],
					$defaults );

				if ( $scripts_diff ) {
					foreach ( $scripts_diff as $script ) {
						$sf = $file->getPath() . '/' . $script;
						if ( $this->filesystem->exists( $sf ) ) {
							$other_scripts[] = file_get_contents( $sf );
						}
					}
				}
			}

			$other_scripts = implode( '',
				$other_scripts );

			if ( !empty( $matches[1] ) ) {
				$content = implode( '',
					$matches[1] ); // without <script> tag
				$file_path = $this->_tmpDir() . '/' . $file->getFilename();
				$this->taskWriteToFile( $file_path )->text( $content . $other_scripts )->run();
				$remove = true;
			} else {
				return '';
			}
		}

		$result = $this->taskExec( 'npm run js2php' )->arg( $file_path )->dir( $this->root_folder )
			->printOutput( false )->run();

		if ( $result->wasSuccessful() ) {
			if ( $remove ) {
				$this->_remove( $file_path );
			}

			return preg_replace( '#(.*?)<?php#is',
				'',
				$result->getMessage() );
		}

		throw new Exception( sprintf( 'Failed to parse %s',
			$file_path ) );
	}

	/**
	 * @param string $test_name
	 * @param string $test_code
	 *
	 * @return Result
	 */
	public function writeTest( string $test_name, string $test_code ) : Result {
		return $this->taskWriteToFile( $test_name )->text( $test_code )->run();
	}

	/**
	 * Copies html files for testing
	 *
	 * @return Result
	 */
	protected function copyFiles() : Result {
		$w3c_core = $this->root_folder . self::W3C_TESTS . '/level1/core/files/*.html';
		$w3c_html = $this->root_folder . self::W3C_TESTS . '/level1/html/files/*.html';

		$cp_dirs = [ $w3c_core => $this->root_folder . '/tests/w3c/level1/core/files/',
			$w3c_html => $this->root_folder . '/tests/w3c/level1/html/files/' ];

		return $this->taskFlattenDir( $cp_dirs )->run();
	}

	/**
	 *
	 */
	public function processLog(): void {
		$log_exits = $this->filesystem->exists( [ 'tests/log.xml' ] );
		if ( $log_exits ) {
			$log_file = file_get_contents( $this->root_folder . '/tests/log.xml' );
			if ( empty( $log_file ) ) {
				$this->yell( 'No log file found.' );
			}
			$log_file = str_replace( $this->root_folder,
				'',
				$log_file );

			$this->taskWriteToFile( $this->root_folder . '/tests/log.xml' )->text( $log_file )->run();
		}
	}

	/**
	 * Deletes test files after they have been executed.
	 */
	protected function cleanUp() {
		$this->filesystem->remove( $this->root_folder . '/tests/wpt' );
		$this->filesystem->remove( $this->root_folder . '/tests/w3c' );
	}

	/**
	 * Generates a list by tests error category.
	 */
	public function generateFailureList() {
		$log_file = file_get_contents( $this->root_folder . '/tests/log.xml' );
		if ( empty( $log_file ) ) {
			$this->yell( 'No log file found.' );
		}

		$xml = simplexml_load_string( $log_file );
		$xml->registerXPathNamespace( 'fn',
			'http://www.w3.org/2005/xpath-functions' );
		// TODO fn:distinct-values
		$errors = $xml->xpath( '//error' );
		// $errors = $xml->xpath('//testsuite[@errors=1]');
		$distinct_errors = [];

		foreach ( $errors as $error ) {
			$type = (string)$error->attributes()->type;
			if ( !in_array( $type,
				$distinct_errors ) ) {
				$distinct_errors[] = $type;
			}
		}

		if ( empty( $distinct_errors ) ) {
			$this->say( 'No errors found' );
		}

		foreach ( $distinct_errors as &$error ) {
			$tests = $xml->xpath( "//error[@type='$error']/parent::*" );
			$error = [ 'error' => $error,
				'tests' => $tests,
				'total' => count( $tests ) ];
		}

		$json = json_encode( $distinct_errors,
			JSON_PRETTY_PRINT );
		$this->taskWriteToFile( $this->root_folder . '/tests/log.json' )->text( $json )->run();
	}

	/**
	 * @param string $test
	 * @param ?string $method
	 *
	 * @return Result
	 */
	public function runTestFromFile( string $test, ?string $method = null ) : Result {
		$method = $method === null ? $test : $method;

		return $this->taskPhpUnit()->filter( $method )->file( '/tests/' . $test . '.php' )->run();
	}

	/**
	 * @param string $test
	 * @param string $method
	 *
	 * @return Result
	 */
	protected function runTest( string $test, string $method ) : Result {
		return $this->taskPhpUnit()->file( $test )->filter( $method )->run();
	}
}
