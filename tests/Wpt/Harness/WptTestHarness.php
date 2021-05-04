<?php
// @phan-file-suppress PhanUndeclaredProperty
// @phan-file-suppress PhanTypeMismatchDimFetch
// @phan-file-suppress PhanTypeMismatchArgumentInternal
// @phan-file-suppress PhanTypeExpectedObjectPropAccess
// @phan-file-suppress PhanImpossibleCondition
// @phan-file-suppress PhanTypeExpectedObjectPropAccess

namespace Wikimedia\Dodo\Tests\Wpt\Harness;

use Exception;
use PHPUnit\Framework\TestCase;
use Wikimedia\Dodo\Document;
use Wikimedia\Dodo\DOMException;
use Wikimedia\Dodo\Tests\Wpt\Harness\Utils\Common;
use Wikimedia\Dodo\Tests\Wpt\Harness\Utils\Selectors;
use Wikimedia\Dodo\Tools\TestsGenerator\Helpers;
use Wikimedia\Dodo\Util;

/**
 * WptTestHarness.php
 * --------
 *
 * @package Wikimedia\Dodo\Tests
 */
abstract class WptTestHarness extends TestCase {
	use Helpers;
	use Selectors;
	use Common;

	const TEST_QSA = 0x01; // querySelector() and querySelectorAll() tests
	const TEST_FIND = 0x04; // find() and findAll() tests, may be unsuitable for querySelector[All]
	const TEST_MATCH = 0x10; // matches() tests

	/**
	 * @see AriaElementReflectionTentativeTest.php
	 *
	 * @var mixed
	 */
	public $parentListbox = null;

	/**
	 * @see AriaElementReflectionTentativeTest.php
	 *
	 * @var mixed
	 */
	public $parentListbox2 = null;

	/**
	 * @var null|Document
	 */
	protected $doc;

	/**
	 * @var string
	 */
	protected $type_error;

	/**
	 * @var string
	 */
	protected $contentType;

	/**
	 * @var string
	 */
	protected $source_file;
	/**
	 * @var string[]
	 */
	protected $invalid_names;
	/**
	 * @var string[]
	 */
	protected $valid_names;
	/**
	 * @var string[]
	 */
	protected $invalid_qnames;

	/**
	 * @param string $name
	 *
	 * @return mixed
	 */
	public function __get( string $name ) {
		if ( $name == 'title' ) {
			return $this->getTitle();
		}
	}

	/**
	 * @return mixed
	 */
	protected function getTitle() {
		return $this->doc->_documentElement->getFirstChild()->getNodeValue();
	}

	/**
	 *
	 */
	public function hasFeature() {
	}

	/**
	 * @param mixed $expected
	 * @param mixed $actual
	 *
	 * @return bool
	 */
	public function same( $expected, $actual ) : bool {
		return $expected === $actual;
	}

	/**
	 * @param $constructor
	 * @param $func
	 * @param $description
	 *
	 * @throws Exception
	 */
	protected function assertThrowsJsData( $constructor, $func, $description = '' ) : void {
		$this->assertThrowsJsImpl( $constructor,
			$func,
			$description,
			'assert_throws_js' );
	}

	/**
	 * Like assert_throws_js but allows specifying the assertion type
	 * (assert_throws_js or promise_rejects_js, in practice).
	 *
	 * @param $constructor
	 * @param $func
	 * @param $description
	 * @param $assertion_type
	 *
	 * @throws Exception
	 */
	protected function assertThrowsJsImpl( $constructor, $func, $description, $assertion_type ) {
		/*
		try {
			$this->assertData( false,
				$assertion_type,
				$description,
				'${func} did not throw',
				[ 'func' => $func ] );
		} catch ( Exception $e ) {
			// throw $e;

			// Basic sanity-checks on the thrown exception.
			$this->assertData( is_object( $e ),
				$assertion_type,
				$description,
				'${func} threw ${e} with type ${type}, not an object',
				[ 'func' => $func,
					'e' => $e,
					'type' => gettype( $e ) ] );

			$this->assertData( $e !== null,
				$assertion_type,
				$description,
				'${func} threw null, not an object',
				[ 'func' => $func ] );

			// Basic sanity-check on the passed-in constructor
			$this->assertData( gettype( $constructor ) === 'function',
				$assertion_type,
				$description,
				'${constructor} is not a constructor',
				[ 'constructor' => $constructor ] );
			$obj = $constructor;
			while ( $obj ) {
				if ( gettype( $obj ) === 'function' && $obj->name === 'Error' ) {
					break;
				}
				$obj = Object::getPrototypeOf( $obj );
			}
			$this->assertData( $obj != null,
				$assertion_type,
				$description,
				'${constructor} is not an Error subtype',
				[ 'constructor' => $constructor ] );

			// And checking that our exception is reasonable
			$this->assertData( $e->constructor === $constructor && $e->name === $constructor->name,
				$assertion_type,
				$description,
				'${func} threw ${actual} (${actual_name}) expected instance of ${expected} (${expected_name})',
				[ 'func' => $func,
					'actual' => $e,
					'actual_name' => $e->name,
					'expected' => $constructor,
					'expected_name' => $constructor->name ] );
		}
		*/
	}

	/**
	 *
	 */
	protected function setUp() : void {
		$this->invalid_names = [ "",
			"invalid^Name",
			"\\",
			"'",
			'"',
			"0",
			"0:a" ];
		$this->valid_names = [ "x",
			"X",
			":",
			"a:0" ];
		$this->invalid_qnames = [ ":a",
			"b:",
			"x:y:z" ];
		$this->contentType = 'text/html';
	}

	/**
	 *
	 */
	protected function tearDown() : void {
		$this->doc = null;
	}

	/**
	 * TODO implement this
	 *
	 * @param string $child
	 * @param string $nodeName
	 * @param string $innerHTML
	 */
	protected function testBefore( string $child, string $nodeName, string $innerHTML ) {
	}

	/**
	 * TODO implement this
	 *
	 * @param string $child
	 * @param string $nodeName
	 * @param string $innerHTML
	 */
	protected function testAfter( string $child, string $nodeName, string $innerHTML ) {
	}

	/**
	 * TODO implement this
	 *
	 * @param mixed ...$args
	 */
	protected function testConstructor( ...$args ) {
	}

	/**
	 * TODO implement this
	 *
	 * @param string $child
	 * @param string $nodeName
	 * @param string $innerHTML
	 */
	protected function testReplaceWith( string $child, string $nodeName, string $innerHTML ) {
	}

	/**
	 * TODO implement this
	 *
	 * @param $closure
	 */
	protected function asyncTestData( $closure ) {
		$closure();
	}

	/**
	 * TODO implement this
	 *
	 * @return mixed
	 * @see xhrResponseTypeDocumentTest.php
	 */
	protected function xmlHttpRequest() {
		return null;
	}

	/**
	 * @param $actual
	 * @param null $message
	 */
	protected function assertFalseData( $actual, $message = null ) : void {
		$this->assertFalse( $actual,
			$message );
	}

	/**
	 * @param $expected
	 * @param $actual
	 * @param $closure
	 */
	protected function assertThrowsDomData( $type, $funcOrConstructor, $descriptionOrFunc = null, $maybeDescription = null ) : void {
		$constructor = null;
		$func = null;
		$description = null;
		if ( $funcOrConstructor->name === 'DOMException' ) {
			$constructor = $funcOrConstructor;
			$func = $descriptionOrFunc;
			$description = $maybeDescription;
		} else {
			$constructor = new DOMException( '',
				'' );
			$func = $funcOrConstructor;
			$description = $descriptionOrFunc;
			assert( $maybeDescription === null,
				'Too many args pased to no-constructor version of assert_throws_dom' );
		}
		$this->assertThrowsDomImpl( $type,
			$func,
			$description,
			'assert_throws_dom',
			$constructor );
	}

	/**
	 * Similar to assert_throws_dom but allows specifying the assertion type
	 * (assert_throws_dom or promise_rejects_dom, in practice).  The
	 * "constructor" argument must be the DOMException constructor from the
	 * global we expect the exception to come from.
	 */
	protected function assertThrowsDomImpl( $type, $func, $description, $assertion_type, $constructor ) : void {
		try {
			call_user_func( 'func' );
			$this->assertData( false,
				$assertion_type,
				$description,
				'${func} did not throw',
				[ 'func' => $func ] );
		} catch ( Exception $e ) {
//			if ( $e instanceof $AssertionError ) {
//				throw $e;
//			}

			// Basic sanity-checks on the thrown exception.
			$this->assertData( is_object( $e ),
				$assertion_type,
				$description,
				'${func} threw ${e} with type ${type}, not an object',
				[ 'func' => $func,
					'e' => $e,
					'type' => gettype( $e ) ] );

			$this->assertData( $e !== null,
				$assertion_type,
				$description,
				'${func} threw null, not an object',
				[ 'func' => $func ] );

			// Sanity-check our type
			$this->assertData( is_int( $type ) || is_string( $type ),
				$assertion_type,
				$description,
				'${type} is not a number or string',
				[ 'type' => $type ] );

			$codename_name_map = [ 'INDEX_SIZE_ERR' => 'IndexSizeError',
				'HIERARCHY_REQUEST_ERR' => 'HierarchyRequestError',
				'WRONG_DOCUMENT_ERR' => 'WrongDocumentError',
				'INVALID_CHARACTER_ERR' => 'InvalidCharacterError',
				'NO_MODIFICATION_ALLOWED_ERR' => 'NoModificationAllowedError',
				'NOT_FOUND_ERR' => 'NotFoundError',
				'NOT_SUPPORTED_ERR' => 'NotSupportedError',
				'INUSE_ATTRIBUTE_ERR' => 'InUseAttributeError',
				'INVALID_STATE_ERR' => 'InvalidStateError',
				'SYNTAX_ERR' => 'SyntaxError',
				'INVALID_MODIFICATION_ERR' => 'InvalidModificationError',
				'NAMESPACE_ERR' => 'NamespaceError',
				'INVALID_ACCESS_ERR' => 'InvalidAccessError',
				'TYPE_MISMATCH_ERR' => 'TypeMismatchError',
				'SECURITY_ERR' => 'SecurityError',
				'NETWORK_ERR' => 'NetworkError',
				'ABORT_ERR' => 'AbortError',
				'URL_MISMATCH_ERR' => 'URLMismatchError',
				'QUOTA_EXCEEDED_ERR' => 'QuotaExceededError',
				'TIMEOUT_ERR' => 'TimeoutError',
				'INVALID_NODE_TYPE_ERR' => 'InvalidNodeTypeError',
				'DATA_CLONE_ERR' => 'DataCloneError' ];

			$name_code_map = [ 'IndexSizeError' => 1,
				'HierarchyRequestError' => 3,
				'WrongDocumentError' => 4,
				'InvalidCharacterError' => 5,
				'NoModificationAllowedError' => 7,
				'NotFoundError' => 8,
				'NotSupportedError' => 9,
				'InUseAttributeError' => 10,
				'InvalidStateError' => 11,
				'SyntaxError' => 12,
				'InvalidModificationError' => 13,
				'NamespaceError' => 14,
				'InvalidAccessError' => 15,
				'TypeMismatchError' => 17,
				'SecurityError' => 18,
				'NetworkError' => 19,
				'AbortError' => 20,
				'URLMismatchError' => 21,
				'QuotaExceededError' => 22,
				'TimeoutError' => 23,
				'InvalidNodeTypeError' => 24,
				'DataCloneError' => 25,

				'EncodingError' => 0,
				'NotReadableError' => 0,
				'UnknownError' => 0,
				'ConstraintError' => 0,
				'DataError' => 0,
				'TransactionInactiveError' => 0,
				'ReadOnlyError' => 0,
				'VersionError' => 0,
				'OperationError' => 0,
				'NotAllowedError' => 0 ];

			$code_name_map = [];
			foreach ( $name_code_map as $key => $___ ) {
				if ( $name_code_map[$key] > 0 ) {
					$code_name_map[$name_code_map[$key]] = $key;
				}
			}

			$required_props = [];
			$name = null;

			if ( in_int( $type ) ) {
				if ( $type === 0 ) {
					Util::error( 'AssertionError',
						'Test bug: ambiguous DOMException code 0 passed to assert_throws_dom()' );
				} elseif ( !( isset( $code_name_map[$type] ) ) ) {
					Util::error( 'AssertionError',
						'Test bug: unrecognized DOMException code "' . $type . '" passed to assert_throws_dom()' );
				}
				$name = $code_name_map[$type];
				$required_props->code = $type;
			} elseif ( is_string( $type ) ) {
				$name = ( isset( $codename_name_map[$type] ) ) ? $codename_name_map[$type] : $type;
				if ( !( isset( $name_code_map[$name] ) ) ) {
					Util::error( 'AssertionError',
						'Test bug: unrecognized DOMException code name or name "' . $type . '" passed to assert_throws_dom()' );
				}

				$required_props->code = $name_code_map[$name];
			}

			if ( $required_props->code === 0 || ( isset( $e['name'] ) && $e->name !== strtoupper( $e->name ) && $e->name !== 'DOMException' ) ) {
				// New style exception: also test the name property.
				$required_props->name = $name;
			}

			foreach ( $required_props as $prop => $___ ) {
				$this->assertData( isset( $e[$prop] ) && $e[$prop] == $required_props[$prop],
					$assertion_type,
					$description,
					'${func} threw ${e} that is not a DOMException ' . $type . ': property ${prop} is equal to ${actual}, expected ${expected}',
					[ 'func' => $func,
						'e' => $e,
						'prop' => $prop,
						'actual' => $e[$prop],
						'expected' => $required_props[$prop] ] );
			}

			// Check that the exception is from the right global.  This check is last
			// so more specific, and more informative, checks on the properties can
			// happen in case a totally incorrect exception is thrown.
			$this->assertData( $e->constructor === $constructor,
				$assertion_type,
				$description,
				'${func} threw an exception from the wrong global',
				[ 'func' => $func ] );
		}
	}

	/**
	 * @param $expected_true
	 * @param $function_name
	 * @param $description
	 * @param $error
	 * @param $substitutions
	 */
	protected function assertData( $expected_true, $function_name, $description, $error, $substitutions ) : void {
		if ( $expected_true !== true ) {
			$msg = $this->makeMessage( $function_name,
				$description,
				$error,
				$substitutions );
			Util::error( 'AssertionError',
				$msg );
		}
	}

	/**
	 * @param $function_name
	 * @param $description
	 * @param $error
	 * @param $substitutions
	 *
	 * @return string
	 */
	protected function makeMessage( $function_name, $description, $error, $substitutions ) {
//		foreach ( $substitutions as $p => $___ ) {
//			if ( $substitutions->hasOwnProperty( $p ) ) {
//				$substitutions[$p] = $this->format_value( $substitutions[$p] );
//			}
//		}
//		$node_form = substitute( [ '{text}',
//			'${function_name}: ${description}' . $error ],
//			array_merge ( [ 'function_name' => $function_name,
//				'description' => ( ( $description ) ? $description . ' ' : '' ) ],
//				$substitutions ) );
//
//		return implode( '',
//			array_slice( $node_form,
//				1 ) );
	}

	/**
	 * @param $actual
	 * @param $expected
	 * @param null $message
	 */
	protected function assertNotEqualsData( $actual, $expected, $message = null ) : void {
		$this->assertNotEquals( $expected,
			$actual );
	}

	/**
	 * @param $actual
	 * @param $expected
	 * @param null $message
	 */
	protected function assertEqualNodeData( $actual, $expected, $message = null ) : void {

	}

	protected function runMutationTest( ...$arg ) {

	}

	/**
	 * @param $closure
	 *
	 * @todo review this
	 *
	 */
	protected function asyncTest( $func, $name = null, $properties = null ) : void {
		$func();
		/*		if ( $tests->promise_setup_called ) {
					$tests->status->status = $tests->status->ERROR;
					$tests->status->message = '`async_test` invoked after `promise_setup`';
					$tests->complete();
				}*/

//		if ( gettype( $func ) !== 'function' ) {
//			$properties = $name;
//			$name = $func;
//			$func = null;
//		}
//
//		$test_name = get_test_name( $func,
//			$name );
//		$test_obj = new Test( $test_name,
//			$properties );
//		if ( $func ) {
//			$value = $test_obj->step( $func,
//				$test_obj,
//				$test_obj );
//
//			// Test authors sometimes return values to async_test, expecting us
//			// to handle the value somehow. Make doing so a harness error to be
//			// clear this is invalid, and point authors to promise_test if it
//			// may be appropriate.
//			//
//			// Note that we only perform this check on the initial function
//			// passed to async_test, not on any later steps - we haven't seen a
//			// consistent problem with those (and it's harder to check).
//			if ( $value !== null ) {
//				$msg = 'Test named "' . $test_name . '" passed a function to `async_test` that returned a value.';
//
//				try {
//					if ( $value && gettype( $value->then ) === 'function' ) {
//						$msg .= ' Consider using `promise_test` instead when ' . 'using Promises or async/await.';
//					}
//				} catch ( Exception $err ) {
//				}
//
//				$tests->set_status( $tests->status->ERROR,
//					$msg );
//				$tests->complete();
//			}
//		}
//
//		return $test_obj;
	}

	/**
	 * Asserts that two nodes are equal, in the sense of isEqualNode().  If they
	 * aren't, tries to print a relatively informative reason why not.  TODO: Move
	 * this to testharness.js?
	 */
	protected function assertNodesEqualData( $actual, $expected, $msg ) : void {
		if ( !$actual->isEqualNode( $expected ) ) {
			$msg = 'Actual and expected mismatch for ' . $msg . '.  ';

			while ( $actual && $expected ) {
				$this->assertTrueData( $actual->nodeType === $expected->nodeType && $actual->nodeName === $expected->nodeName && $actual->nodeValue === $expected->nodeValue,
					'First differing node: expected ' . $this->formatValue( $expected ) . ', got ' . $this->formatValue( $actual ) . ' [' . $msg . ']' );
				$actual = $this->nextNode( $actual );
				$expected = $this->nextNode( $expected );
			}
			$this->assertUnreached( "DOMs were not equal but we couldn't figure out why" );
		}
	}

	/**
	 * @param $actual
	 * @param null $message
	 */
	protected function assertTrueData( $actual, $message = null ) {
		$this->assertTrue( $actual,
			$message );
	}

	protected function formatValue( $value ) {
		return $value;
	}

	/**
	 * Returns the first Node that's after node in tree order, or null if node is
	 * the last Node.
	 */
	function nextNode( $node ) {
		if ( $node->hasChildNodes() ) {
			return $node->firstChild;
		}

		return $this->nextNodeDescendants( $node );
	}

	/**
	 * @param mixed ...$arg
	 */
	protected function assertUnreached( $description ) {
		$this->assertData( false,
			"assert_unreached",
			$description,
			"Reached unreachable code" );
	}

	/**
	 * Returns the last Node that's before node in tree order, or null if node is
	 * the first Node.
	 */
	protected function previousNode( $node ) {
		if ( $node->getPreviousSibling() ) {
			$node = $node->getPreviousSibling();
			while ( $node->hasChildNodes() ) {
				$node = $node->lastChild;
			}

			return $node;
		}

		return $node->parentNode;
	}

	/**
	 * @param $el
	 * @param $message
	 *
	 * @todo rewrite this
	 *
	 */
	protected function assertClassStringData( $object, $class_string, $description = '' ) {
		$actual = call_user_func( [ [],
			'toString' ] );
		$expected = '[object ' . $class_string . ']';
		$this->assertData( $this->sameValue( $actual,
			$expected ),
			'assert_class_string',
			$description,
			'expected ${expected} but got ${actual}',
			[ 'expected' => $expected,
				'actual' => $actual ] );
	}

	/**
	 * @param $x
	 * @param $y
	 *
	 * @return bool
	 */
	protected function sameValue( $x, $y ) {
		if ( $y !== $y ) {
			//NaN case
			return $x !== $x;
		}
		if ( $x === 0 && $y === 0 ) {
			//Distinguish +0 and -0
			return 1 / $x === 1 / $y;
		}

		return $x === $y;
	}

	/**
	 * @param $object
	 * @param $property_name
	 * @param $description
	 *
	 * @todo rewrite this
	 *
	 */
	protected function assertReadonlyData( $object, $property_name, $description = '' ) {
		$initial_value = $object[$property_name];
		try {
			//Note that this can have side effects in the case where
			//the property has PutForwards
			$object[$property_name] = $initial_value . 'a'; //XXX use some other value here?
			$this->assertData( $this->sameValue( $object[$property_name],
				$initial_value ),
				'assert_readonly',
				$description,
				'changing property ${p} succeeded',
				[ 'p' => $property_name ] );
		} finally {
			$object[$property_name] = $initial_value;
		}
	}

	/**
	 * @todo implement this
	 */
	protected function checkRecords( ...$arg ) {

	}

	/**
	 * STUB
	 */
	protected function done() {

	}

	/**
	 * @TODO rewrite this
	 *
	 * @param $func_or_properties
	 * @param $maybe_properties
	 */
	protected function setupData( $func_or_properties, $maybe_properties ) {
		$func = null;
		$properties = [];
		if ( count( $arguments ) === 2 ) {
			$func = $func_or_properties;
			$properties = $maybe_properties;
		} elseif ( $func_or_properties instanceof $Function ) {
			$func = $func_or_properties;
		} else {
			$properties = $func_or_properties;
		}
		$tests->setup( $func,
			$properties );
		$test_environment->on_new_harness_properties( $properties );
	}

	/**
	 * @param $actual
	 * @param $expected
	 */
	protected function assertNodeData( $actual, $expected ) {

		$this->assertTrueData( $actual instanceof $expected->type,
			'Node type mismatch: actual = ' . $actual->nodeType . ', expected = ' . $expected->nodeType );
		if ( gettype( $expected->id ) !== null ) {
			$this->assertEqualsData( $actual->id,
				$expected->id );
		}
		if ( gettype( $expected->nodeValue ) !== null ) {
			$this->assertEqualsData( $actual->nodeValue,
				$expected->nodeValue );
		}
	}

	/**
	 * @param mixed $actual
	 * @param mixed $expected
	 * @param string $message
	 */
	protected function assertEqualsData( $actual, $expected, string $message = null ) {
		$this->assertEquals( $expected,
			$actual,
			$message );
	}

	/**
	 * @param $node
	 * @param $parent
	 * @param $type
	 *
	 * @see test_remove
	 */
	protected function assertTestRemove( $node, $parent, $type ) {
		$message = $type . ' should support remove()';
		$this->assertTrueData( isset( $node['remove'] ),
			$message );
		$this->assertEqualsData( gettype( $node->remove ),
			'function',
			$message );
		$this->assertEqualsData( count( $node->remove ),
			0,
			$message );

		$message = 'remove() should work if ' . $type . " doesn't have a parent";
		$this->assertEqualsData( $node->parentNode,
			null,
			'Node should not have a parent' );
		$this->assertEqualsData( $node->remove(),
			null,
			$message );
		$this->assertEqualsData( $node->parentNode,
			null,
			'Removed new node should not have a parent' );

		$message = 'remove() should work if ' . $type . ' does have a parent';
		$this->assertEqualsData( $node->parentNode,
			null,
			'Node should not have a parent' );
		$parent->appendChild( $node );
		$this->assertEqualsData( $node->parentNode,
			$parent,
			'Appended node should have a parent' );
		$this->assertEqualsData( $node->remove(),
			null );
		$this->assertEqualsData( $node->parentNode,
			null,
			'Removed node should not have a parent' );
		$this->assertArrayEqualsData( $parent->childNodes,
			[],
			'Parent should not have children' );

		$message = 'remove() should work if ' . $type . ' does have a parent and siblings';
		$this->assertEqualsData( $node->parentNode,
			null,
			'Node should not have a parent' );
		$before = $parent->appendChild( $this->doc->createComment( 'before' ) );
		$parent->appendChild( $node );
		$after = $parent->appendChild( $this->doc->createComment( 'after' ) );
		$this->assertEqualsData( $node->parentNode,
			$parent,
			'Appended node should have a parent' );
		$this->assertEqualsData( $node->remove(),
			null );
		$this->assertEqualsData( $node->parentNode,
			null,
			'Removed node should not have a parent' );
		$this->assertArrayEqualsData( $parent->childNodes,
			[ $before,
				$after ],
			'Parent should have two children left' );
	}

	/**
	 * @param $actual
	 * @param $expected
	 * @param $description
	 */
	protected function assertArrayEqualsData( $actual, $expected, $description = '' ) {
		$max_array_length = 20;

		$this->assertData( gettype( $actual ) === 'object' && $actual !== null && isset( $actual['length'] ),
			'assertArrayEquals',
			$description,
			'value is ${actual}, expected array',
			[ 'actual' => $actual ] );
		$this->assertData( count( $actual ) === count( $expected ),
			'assertArrayEquals',
			$description,
			'lengths differ, expected array ${expected} length ${expectedLength}, got ${actual} length ${actualLength}',
			[ 'expected' => $this->shortenArray( $expected,
				count( $expected ) - 1 ),
				'expectedLength' => count( $expected ),
				'actual' => $this->shortenArray( $actual,
					count( $actual ) - 1 ),
				'actualLength' => count( $actual ) ] );

		for ( $i = 0, $iMax = count( $actual ); $i < $iMax; $i++ ) {
			$this->assertData( $actual->hasOwnProperty( $i ) === $expected->hasOwnProperty( $i ),
				'assertArrayEquals',
				$description,
				'expected property ${i} to be ${expected} but was ${actual} (expected array ${arrayExpected} got ${arrayActual})',
				[ 'i' => $i,
					'expected' => ( $expected->hasOwnProperty( $i ) ) ? 'present' : 'missing',
					'actual' => ( $actual->hasOwnProperty( $i ) ) ? 'present' : 'missing',
					'arrayExpected' => $this->shortenArray( $expected,
						$i ),
					'arrayActual' => $this->shortenArray( $actual,
						$i ) ] );
			$this->assertData( $this->sameValue( $expected[$i],
				$actual[$i] ),
				'assertArrayEquals',
				$description,
				'expected property ${i} to be ${expected} but got ${actual} (expected array ${arrayExpected} got ${arrayActual})',
				[ 'i' => $i,
					'expected' => $expected[$i],
					'actual' => $actual[$i],
					'arrayExpected' => $this->shortenArray( $expected,
						$i ),
					'arrayActual' => $this->shortenArray( $actual,
						$i ) ] );
		}
	}

	/**
	 * @param $arr
	 * @param int $offset
	 * @param $max_array_length
	 *
	 * @return array
	 */
	protected function shortenArray( $arr, $offset = 0, &$max_array_length = 20 ) {
		// Make ", …" only show up when it would likely reduce the length, not accounting for
		// fonts.
		if ( count( $arr ) < $max_array_length + 2 ) {
			return $arr;
		}

// By default we want half the elements after the offset and half before
// But if that takes us past the end of the array, we have more before, and
// if it takes us before the start we have more after.
		$length_after_offset = floor( $max_array_length / 2 );
		$upper_bound = min( $length_after_offset + $offset,
			count( $arr ) );
		$lower_bound = max( $upper_bound - $max_array_length,
			0 );

		if ( $lower_bound === 0 ) {
			$upper_bound = $max_array_length;
		}

		$output = array_slice( $arr,
			$lower_bound,
			$upper_bound/*CHECK THIS*/ );
		if ( $lower_bound > 0 ) {
			$output->beginEllipsis = true;
		}
		if ( $upper_bound < count( $arr ) ) {
			$output->endEllipsis = true;
		}

		return $output;
	}

	/**
	 *
	 */
	protected function assertThrowsExactlyData( $exception, $func, $description ) {
		$this->assertThrowsExactlyImpl( $exception,
			$func,
			$description,
			'assert_throws_exactly' );
	}

	/**
	 * Like assert_throws_exactly but allows specifying the assertion type
	 * (assert_throws_exactly or promise_rejects_exactly, in practice).
	 */
	protected function assertThrowsExactlyImpl( $exception, $func, $description, $assertion_type ) {
		try {
			$func();
			$this->assertData( false,
				$assertion_type,
				$description,
				'${func} did not throw',
				[ 'func' => $func ] );
		} catch ( Exception $e ) {
			if ( $e instanceof AssertionError ) {
				throw $e;
			}

			$this->assertData( $this->sameValue( $e,
				$exception ),
				$assertion_type,
				$description,
				'${func} threw ${e} but we expected it to throw ${exception}',
				[ 'func' => $func,
					'e' => $e,
					'exception' => $exception ] );
		}
	}

	/**
	 * @param $clone
	 * @param $object
	 *
	 * @return bool
	 */
	protected function isCloneOf( $clone, $object ) {
		return ( $clone == $object && $clone !== $object && spl_object_hash( $clone ) !== spl_object_hash( $object ) );
	}

	/**
	 * @param $el
	 * @param $l
	 */
	protected function attributesAre( $el, $l ) {
		for ( $i = 0, $il = count( $l ); $i < $il; $i++ ) {
			$this->attrIs( $el->attributes[$i],
				$l[$i][1],
				$l[$i][0],
				( count( $l[$i] ) < 3 ) ? null : $l[$i][2],
				null,
				$l[$i][0] );
			$this->assertEqualsData( $el->attributes[$i]->ownerElement,
				$el );
		}
	}

	protected function attrIs( $attr, $v, $ln, $ns, $p, $n ) {
		$this->assertEqualsData( $attr->value,
			$v );
		$this->assertEqualsData( $attr->nodeValue,
			$v );
		$this->assertEqualsData( $attr->textContent,
			$v );
		$this->assertEqualsData( $attr->localName,
			$ln );
		$this->assertEqualsData( $attr->namespaceURI,
			$ns );
		$this->assertEqualsData( $attr->prefix,
			$p );
		$this->assertEqualsData( $attr->name,
			$n );
		$this->assertEqualsData( $attr->nodeName,
			$n );
		$this->assertEqualsData( $attr->specified,
			true );
	}

	/**
	 * TODO implement this
	 *
	 * @param $func
	 * @param $args
	 * @param $properties
	 */
	protected function generateTests( $func, $args, &$properties ) {
		foreach ( $args as $x => $i ) {
			$name = $x[0];
			$this->assertTest( function () use ( $x ) {
				array_slice( $x,
					1 );
			},
				$name,
				( is_array( $properties ) ) ? $properties[$i] : $properties );
		}
	}

	/**
	 * @param callable $closure
	 * @param mixed $type
	 */
	protected function assertTest( callable $closure, $type = null ) : void {
		$closure();
	}

	/**
	 * @param array $array
	 * @param callable $callback
	 *
	 * @return array
	 */
	protected function arrayMap( array $array, callable $callback ) : array {
		return array_map( $callback,
			$array );
	}

	/**
	 * @param $obj
	 * @param $method
	 * @param $type
	 */
	protected function assertIdlAttributeData( $obj, $method, $type ) {

	}

	/**
	 * @param $actual
	 * @param $expected
	 * @param string|null $description
	 */
	protected function assertInArrayData( $actual, $expected, ?string $description = null ) {
		$this->assertData( array_search( $actual,
				$expected,
				true ) != false,
			'assertInArrayData',
			$description,
			'value ${actual} not in array ${expected}',
			[ 'actual' => $actual,
				'expected' => $expected ] );
	}

	/**
	 * @param mixed ...$arg
	 */
	protected function parseFromString( ...$arg ) {

	}
}
