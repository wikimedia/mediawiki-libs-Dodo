<?php
// @phan-file-suppress PhanUndeclaredProperty
// @phan-file-suppress PhanTypeMismatchDimFetch
// @phan-file-suppress PhanTypeMismatchArgumentInternal
// @phan-file-suppress PhanTypeExpectedObjectPropAccess
// @phan-file-suppress PhanImpossibleCondition
// @phan-file-suppress PhanTypeExpectedObjectPropAccess

namespace Wikimedia\Dodo\Tests\WPT\Harness;

use Exception;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Wikimedia\Dodo\Document;
use Wikimedia\Dodo\DOMException;
use Wikimedia\Dodo\HTMLElement;
use Wikimedia\Dodo\Internal\Util;
use Wikimedia\Dodo\Tests\WPT\Harness\Utils\Common;
use Wikimedia\Dodo\Tests\WPT\Harness\Utils\Selectors;
use Wikimedia\Dodo\Tools\TestsGenerator\Helpers;

/**
 * WPTTestHarness.php
 * --------
 *
 * @package Wikimedia\Dodo\Tests
 */
abstract class WPTTestHarness extends TestCase {
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
	 * @param Document $doc
	 *
	 * @return HTMLElement
	 */
	public function getDocBody( Document $doc ) : HTMLElement {
		return $doc->getBody();
	}

	/**
	 * TODO implement this
	 */
	public function step_func_done( $func = null, $this_obj = null ) : void {
	}

	/**
	 * @param mixed $elt
	 *
	 * @return mixed
	 */
	public function getOwnPropertyNames( $elt ) {
		try {
			if ( is_iterable( $elt ) ) {
				$names = [];
				foreach ( $elt as $el ) {
					if ( isset( $el->name ) ) {
						$names[] = $el->name;
					}
				}

				return array_merge( array_keys( $names ),
					array_flip( $names ) );
			}
		} catch ( Exception $e ) {
			Util::error( $e->getMessage() );
		}

		return get_object_vars( $elt );
	}

	/**
	 * @param $constructor
	 * @param $func
	 * @param string $description
	 *
	 * @throws Exception
	 */
	protected function assertThrowsJsData( $constructor, $func, string $description = '' ) : void {
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
	protected function assertThrowsJsImpl( $constructor, $func, $description, $assertion_type ) : void {
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
	 *
	 * Assert a DOMException with the expected type is thrown.
	 *
	 * @param {number|string} type The expected exception name or code.  See the
	 *        table of names and codes at
	 *        https://heycam.github.io/webidl/#dfn-error-names-table
	 *        If a number is passed it should be one of the numeric code values
	 *        in that table (e.g. 3, 4, etc).  If a string is passed it can
	 *        either be an exception name (e.g. "HierarchyRequestError",
	 *        "WrongDocumentError") or the name of the corresponding error code
	 *        (e.g. "HIERARCHY_REQUEST_ERR", "WRONG_DOCUMENT_ERR").
	 *
	 * For the remaining arguments, there are two ways of calling
	 * promise_rejects_dom:
	 *
	 * 1) If the DOMException is expected to come from the current global, the
	 * second argument should be the function expected to throw and a third,
	 * optional, argument is the assertion description.
	 *
	 * 2) If the DOMException is expected to come from some other global, the
	 * second argument should be the DOMException constructor from that global,
	 * the third argument the function expected to throw, and the fourth, optional,
	 * argument the assertion description.
	 *
	 * @param callable $funcOrConstructor
	 * @param ?string $descriptionOrFunc
	 * @param ?string $maybeDescription
	 */
	protected function assertThrowsDomData( $type, callable $funcOrConstructor, ?string $descriptionOrFunc = null, ?string $maybeDescription = null ) : void {
		$constructor = null;
		$func = null;
		$description = null;
		try {
			$funcOrConstructor();
		} catch ( Exception $exception ) {
			if ( $exception instanceof DOMException ) {
				$constructor = $exception;
				$func = $funcOrConstructor;
				$description = $descriptionOrFunc ?? $maybeDescription;
			} else {
				$constructor = new DOMException( '',
					'' );
				$func = $funcOrConstructor;
				$description = $descriptionOrFunc ?? $maybeDescription;
				Assert::assertTrue( $maybeDescription === null,
					'Too many args pased to no-constructor version of assert_throws_dom'
				);
			}
		}

		$this->assertThrowsDomImpl( $type,
			$func,
			$description,
			'assert_throws_dom',
			$constructor );
	}

	/**
	 * @param bool $expected_true
	 * @param string $function_name
	 * @param ?string $description
	 * @param string $error
	 * @param array<string, mixed> $substitutions
	 */
	protected function assertData( bool $expected_true, string $function_name, ?string $description, string $error, array $substitutions ) : void {
		$msg = $this->makeMessage( $function_name,
			$description,
			$error,
			$substitutions );

		Assert::assertTrue( $expected_true, $msg );
	}

	/**
	 * @param string $function_name
	 * @param ?string $description
	 * @param string $error
	 * @param array<string,mixed> $substitutions
	 *
	 * @return string
	 */
	protected function makeMessage( string $function_name, ?string $description, string $error, array $substitutions ): string {
		$_substitutions = [];
		foreach ( $substitutions as $p => $v ) {
			if ( is_array( $v ) ) {
				$_substitutions[ '${' . $p . '}'] = implode( ',',
					array_keys( $v ) );
			} else {
				$_substitutions[ '${' . $p . '}'] = $this->formatValue( $v );
			}
		}
		return strtr( '${function_name}: ${description} ' . $error,
			array_merge(
				[ '${function_name}' => $function_name,
					'${description}' => ( $description ? $description . ' ' : '' ),
				], $_substitutions ) );
	}

	/**
	 * TODO implement check if possible to implement
	 *
	 * @param mixed $value
	 *
	 * @return string
	 */
	protected function formatValue( $value ): string {
		return json_encode( $value );

// if ( !$seen ) {
//			$seen = [];
//		}
//		if ( gettype( $val ) === 'object' && $val !== null ) {
//			if ( array_search( $val, $seen ) >= 0 ) {
//				return '[...]';
//			}
//			$seen[] = $val;
//		}
//		if ( is_array( $val ) ) {
//			$output = '[';
//			if ( $val->beginEllipsis !== null ) {
//				$output += "…, ";
//			}
//			$output += implode( ', ', array_map( $val, function ( $x ) { return format_value( $x, $seen );  } ) );
//			if ( $val->endEllipsis !== null ) {
//				$output += ", …";
//			}
//			return $output . ']';
//		}
//
//		switch ( gettype( $val ) ) {
//			case 'string':
//				$val = preg_replace( '/\\\/', '\\\\', $val );
//				foreach ( $replacements as $p => $___ ) {
//					$replace = '\\' . $replacements[ $p ];
//					$val = str_replace( RegExp( String::fromCharCode( $p ), 'g' ), $replace, $val );
//				}
//				return '"' . preg_replace( '/"/', '\"', $val ) . '"';
//			case 'boolean':
//
//			case NULL:
//				return String( $val );
//			case 'number':
//				// In JavaScript, -0 === 0 and String(-0) == "0", so we have to
//				// special-case.
//				if ( $val === -0 && 1 / $val === -$Infinity ) {
//					return '-0';
//				}
//				return String( $val );
//			case 'object':
//				if ( $val === null ) {
//					return 'null';
//				}
//
//				// Special-case Node objects, since those come up a lot in my tests.  I
//				// ignore namespaces.
//				if ( is_node( $val ) ) {
//					switch ( $val->nodeType ) {
//						case Node\ELEMENT_NODE:
//							$ret = '<' . $val->localName;
//							for ( $i = 0;  $i < count( $val->attributes );  $i++ ) {
//								$ret += ' ' . $val->attributes[ $i ]->name . '="' . $val->attributes[ $i ]->value . '"';
//							}
//							$ret += '>' . $val->innerHTML . '</' . $val->localName . '>';
//							return 'Element node ' . truncate( $ret, 60 );
//						case Node\TEXT_NODE:
//							return 'Text node "' . truncate( $val->data, 60 ) . '"';
//						case Node\PROCESSING_INSTRUCTION_NODE:
//							return 'ProcessingInstruction node with target ' . format_value( truncate( $val->target, 60 ) ) . ' and data ' . format_value( truncate( $val->data, 60 ) );
//						case Node\COMMENT_NODE:
//							return 'Comment node <!--' . truncate( $val->data, 60 ) . '-->';
//						case Node\DOCUMENT_NODE:
//							return 'Document node with ' . count( $val->childNodes ) . ( ( count( $val->childNodes ) == 1 ) ? ' child' : ' children' );
//						case Node\DOCUMENT_TYPE_NODE:
//							return 'DocumentType node';
//						case Node\DOCUMENT_FRAGMENT_NODE:
//							return 'DocumentFragment node with ' . count( $val->childNodes ) . ( ( count( $val->childNodes ) == 1 ) ? ' child' : ' children' );
//						default:
//							return 'Node object of unknown type';
//					}
//				}
//
//			/* falls through */
//			default:
//				try {
//					return gettype( $val ) . ' "' . truncate( String( $val ), 1000 ) . '"';
//				} catch ( Exception $e ) {
//					return ( '[stringifying object threw ' . String( $e )
//						.				' with type ' . String( gettype( $e ) ) . ']' );
//				}
//		}
	}

	/**
	 * TODO Refactor this
	 *
	 * from DominoJS:
	 * Similar to assert_throws_dom but allows specifying the assertion type
	 * (assert_throws_dom or promise_rejects_dom, in practice).  The
	 * "constructor" argument must be the DOMException constructor from the
	 * global we expect the exception to come from.
	 *
	 * @param $type
	 * @param callable $func
	 * @param ?string $description
	 * @param $assertion_type
	 * @param $constructor
	 */
	protected function assertThrowsDomImpl( $type, callable $func, ?string $description, $assertion_type, $constructor ) : void {
		try {
			$func();
			$this->assertData( false,
				$assertion_type,
				$description,
				'${func} did not throw',
				[ 'func' => $func ] );
		} catch ( Exception $e ) {
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

			$code_name_map = array_flip( $name_code_map );
			$required_props = [];
			$name = null;

			if ( is_int( $type ) ) {
				if ( $type === 0 ) {
					Util::error( 'AssertionError',
						'Test bug: ambiguous DOMException code 0 passed to assert_throws_dom()' );
				} elseif ( !( isset( $code_name_map[$type] ) ) ) {
					Util::error( 'AssertionError',
						'Test bug: unrecognized DOMException code "' . $type . '" passed to assert_throws_dom()' );
				}
				$name = $code_name_map[$type];
				$required_props['code'] = $type;
			} elseif ( is_string( $type ) ) {
				$name = $codename_name_map[$type] ?? $type;
				if ( !( isset( $name_code_map[$name] ) ) ) {
					Util::error( 'AssertionError',
						'Test bug: unrecognized DOMException code name or name "' . $type . '" passed to assert_throws_dom()' );
				}

				$required_props['code'] = $name_code_map[$name];
			}

			if ( $required_props['code'] === 0 || ( $e->name !== strtoupper( $e->getName() ) && $e->getName() !== 'DOMException' ) ) {
				// New style exception: also test the name property.
				$required_props['name'] = $name;
			}

			foreach ( $required_props as $prop => $___ ) {
				$proper_name = 'get' . ucfirst( $prop );
				$this->assertData( $e->{$proper_name}() === $required_props[$prop],
					$assertion_type,
					$description,
					'${func} threw ${e} that is not a DOMException ' . $type . ': property ${prop} is equal to ${actual}, expected ${expected}',
					[ '${func}' => 'Closure',
						'${e}' => $e->getName(),
						'${prop}' => $prop,
						'${actual}' => $e->{$prop},
						'${expected}' => $required_props[$prop] ] );
			}
		}
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
	 * TODO stub
	 *
	 * @param $actual
	 * @param $expected
	 * @param null $message
	 */
	protected function assertEqualNodeData( $actual, $expected, $message = null ) : void {
	}

	/**
	 * TODO stub
	 *
	 * @param ...$arg
	 */
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
	protected function assertUnreached( $description ) : void {
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
	protected function assertClassStringData( $object, $class_string, $description = '' ) : void {
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
	protected function sameValue( $x, $y ) : bool {
		if ( $x === 0 && $y === 0 ) {
			// Distinguish +0 and -0
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
	protected function assertReadonlyData( $object, $property_name, $description = '' ) : void {
		$initial_value = $object[$property_name];
		try {
			// Note that this can have side effects in the case where
			//the property has PutForwards
			$object[$property_name] = $initial_value . 'a'; // XXX use some other value here?
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
	protected function checkRecords( ...$arg ) : void {
	}

	/**
	 * STUB
	 */
	protected function done() : void {
	}

	/**
	 * @param $func_or_properties
	 * @param $maybe_properties
	 *
	 * @todo rewrite this
	 *
	 */
	protected function setupData( $func_or_properties, $maybe_properties ) : void {
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
	protected function assertNodeData( $actual, $expected ) : void {
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
	protected function assertEqualsData( $actual, $expected, string $message = '' ) : void {
		Assert::assertEquals( $expected, $actual, $message );
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
	 * @param array $actual
	 * @param array $expected
	 * @param string $description
	 */
	protected function assertArrayEqualsData( $actual, array $expected, string $description = '' ) : void {
		Assert::assertIsArray( $actual, $description );
		Assert::assertCount( count( $expected ), $actual, $description );

		for ( $i = 0; $i < count( $expected ); $i++ ) {
			Assert::assertEquals( $expected[$i], $actual[$i], $description );
		}
	}

	/**
	 * @param $exception
	 * @param $func
	 * @param $description
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
	protected function assertThrowsExactlyImpl( $exception, $func, $description, $assertion_type ) : void {
		try {
			$func();
			$this->assertData( false,
				$assertion_type,
				$description,
				'${func} did not throw',
				[ 'func' => $func ] );
		} catch ( Exception $e ) {
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

	/**
	 * @param $attr
	 * @param $v
	 * @param $ln
	 * @param $ns
	 * @param $p
	 * @param $n
	 */
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
	 * @param &$properties
	 */
	protected function generateTests( $func, $args, &$properties ) : void {
		foreach ( $args as $x => $i ) {
			$name = $x[0];
			$this->assertTest( static function () use ( $x ) {
				array_slice( $x,
					1 );
			},
				$name,
				( is_array( $properties ) ) ? $properties[$i] : $properties );
		}
	}

	/**
	 * @param callable $closure
	 * @param string|null $message
	 */
	protected function assertTest( callable $closure, string $message = null ) : void {
		$closure( null );
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
	protected function assertIdlAttributeData( $obj, $method, $type ) : void {
	}

	/**
	 * @param $actual
	 * @param $expected
	 * @param string|null $description
	 */
	protected function assertInArrayData( $actual, $expected, ?string $description = null ) : void {
		$this->assertData( in_array( $actual,
			$expected,
			true ),
			'assertInArrayData',
			$description,
			'value ${actual} not in array ${expected}',
			[ 'actual' => $actual,
				'expected' => $expected ] );
	}

	/**
	 * @param mixed ...$arg
	 */
	protected function parseFromString( ...$arg ) : void {
	}
}
