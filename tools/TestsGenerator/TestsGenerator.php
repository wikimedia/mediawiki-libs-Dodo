<?php

declare( strict_types = 1 );
// XXX Should fix these!
// @phan-file-suppress PhanUndeclaredMethod
// @phan-file-suppress SecurityCheck-LikelyFalsePositive

namespace Wikimedia\Dodo\Tools\TestsGenerator;

use Exception;
use Robo\Common\IO;
use Robo\Exception\TaskException;
use Robo\Result;
use Robo\Tasks;
use SplFileInfo;
use Symfony\Component\Filesystem\Filesystem;

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
	use IO;
	use Helpers;

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
		'phpcbf' => false,
		'run' => true,
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

			/* hard to parse tests */
			$skips = [ 'DOMImplementation-createDocument',
				'Document-createProcessingInstruction',
				'Element-classlist',
				'MutationObserver-document',
				'Node-baseURI',
				'Node-childNodes',
				'Node-cloneNode-document-with-doctype',
				'Node-parentNode-iframe',
				'Node-properties',
				'attributes-namednodemap' ];

			$files = $result->getData();

			if ( empty( $files ) ) {
				throw new Exception( 'No tests were loaded.' );
			}

			foreach ( $result->getData() as $test_type => $tests ) {
				if ( $test_type == 'w3c_harness' || $test_type == 'wpt_harness' || $test_type == 'time' || $test_type
				== 'wpt' ) {
					continue;
				}

				if ( $test_type == 'wpt' ) {
					$opts['compact'] = false;
				}

				$tests_per_type = $opts['limit'];

				foreach ( $tests as $file ) {
					if ( $tests_per_type-- == 0 ) {
						break;
					}

					$test_name = $file->getFilenameWithoutExtension();
					$new_test_name = $this->snakeToPascal( $test_name );

					if ( $test_type == 'w3c' ) {
						$test_path = str_replace( $this->root_folder . self::W3C_TESTS,
							'',
							$file->getPath() );
					} else {
						$test_path = str_replace( $this->root_folder . self::WPT_TESTS,
							'',
							$file->getPath() );
					}

					$test_path = "{$this->root_folder}/tests/{$test_type}{$test_path}/{$new_test_name}Test.php";

					/**
					 * skip test if it's already generated and there is no --rewrite arg provided,
					 * also skip hard to parse tests
					 */
					if ( $this->filesystem->exists( $test_path ) && !$opts['rewrite'] || in_array( $test_name,
							$skips ) ) {
						$this->say( 'Skipped ' . $test_name );
						continue;
					}

					/* TODO check results */
					$actual_test = $this->getTranspiledFile( $file );
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
				$this->runCodeSniffer();
			}

			// Copy html files for tests.
			$result = $this->copyFiles();
			if ( !$result->wasSuccessful() || $result->wasCancelled() ) {
				throw new Exception( $result->getMessage() );
			}

			// Run phpunit.
			if ( $opts['run'] ) {
				// Regenerate autoload file.
				$result = $this->dumpAutoload();
				if ( !$result->wasSuccessful() || $result->wasCancelled() ) {
					throw new Exception( $result->getMessage() );
				}

				// Run tests.
				$result = $this->runTests();
				if ( !$result->wasSuccessful() || $result->wasCancelled() ) {
					throw new Exception( $result->getMessage() );
				}

				$this->generateFailureList();
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
		// WPT harness.
		if ( !$this->filesystem->exists( $this->root_folder . '/tests/DodoBaseTest.php' ) || $rewrite ) {
			$this->filesystem->copy( $this->folder . '/tests/DodoBaseTest.php.skel',
				$this->root_folder . '/tests/DodoBaseTest.php' );
		}

		// DominoJS harness.
		if ( !$this->filesystem->exists( $this->root_folder . '/tests/DomTestCase.php' ) || $rewrite ) {
			$this->filesystem->copy( $this->folder . '/tests/DomTestCase.php.skel',
				$this->root_folder . '/tests/DomTestCase.php' );
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
	 * TODO exclude tmp file creation, feed with file content
	 *
	 * @param SplFileInfo $file
	 *
	 * @return string
	 * @throws TaskException
	 * @throws Exception
	 */
	public function getTranspiledFile( SplFileInfo $file ) : string {
		$file_path = $file->getRealPath();
		$remove = false;

		if ( $file->getExtension() == 'html' ) {
			preg_match( '#<script>(.*?)</script>#is',
				$file->getContents(),
				$matches );

			if ( isset( $matches[1] ) && $matches[1] ) {
				$content = $matches[1]; // without <script> tag
				$file_path = $this->_tmpDir() . '/' . $file->getFilename();
				file_put_contents( $file_path,
					$content );
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
	 * @return Result
	 */
	public function runCodeSniffer() : Result {
		$tests_path = $this->root_folder . '/tests/';

		return $this->taskExec( $this->root_folder . "/vendor/bin/phpcbf {$tests_path}" )->run();
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
	 * @return Result
	 */
	public function dumpAutoload() : Result {
		return $this->taskExec( 'composer dumpautoload' )->run();
	}

	/**
	 * @return Result
	 */
	public function runTests() : Result {
		return $this->taskExec( 'composer phpunit' )->run();
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

		$json = json_encode( $distinct_errors, JSON_PRETTY_PRINT );
		$this->taskWriteToFile( $this->root_folder . '/tests/log.json' )->text( $json )->run();
	}

	/**
	 * Deletes test files after they have been executed.
	 */
	protected function cleanUp() {
		$this->filesystem->remove( $this->root_folder . '/tests/wpt' );
		$this->filesystem->remove( $this->root_folder . '/tests/w3c' );
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
