<?php
// phpcs:disable MediaWiki.NamingConventions.LowerCamelFunctionsName.FunctionName
// phpcs:disable Generic.NamingConventions.CamelCapsFunctionName.ScopeNotCamelCaps

declare( strict_types = 1 );

namespace Wikimedia\Dodo\Tests\Harness;

use Exception;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use Throwable;
use Wikimedia\Dodo\Internal\Util;
use Wikimedia\Dodo\Tests\Harness\Utils\Common;
use Wikimedia\Dodo\Tests\Harness\Utils\Selectors;
use Wikimedia\Dodo\Tools\TestsGenerator\Helpers;
use Wikimedia\IDLeDOM\Attr;
use Wikimedia\IDLeDOM\Comment;
use Wikimedia\IDLeDOM\Document;
use Wikimedia\IDLeDOM\DOMException;
use Wikimedia\IDLeDOM\Element;
use Wikimedia\IDLeDOM\Node;
use Wikimedia\IDLeDOM\ProcessingInstruction;
use Wikimedia\IDLeDOM\SimpleException;
use Wikimedia\IDLeDOM\Text;

/**
 * WPTTestHarness
 */
abstract class WPTTestHarness extends TestCase {
	use Helpers;
	use Selectors;

	public const TEST_QSA = 0x01; // querySelector() and querySelectorAll() tests
	public const TEST_FIND = 0x04; // find() and findAll() tests, may be unsuitable for querySelector[All]
	public const TEST_MATCH = 0x10; // matches() tests

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
	 * @var class-string
	 */
	protected $type_error = \Wikimedia\IDLeDOM\TypeError::class;

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
	 * @var callable[]
	 */
	protected $cleanupFuncs;

	/**
	 * @var ?Common
	 */
	protected $common;

	/**
	 * TODO implement this
	 * @param ?callable $func
	 * @param mixed $this_obj
	 */
	public function step_func_done( ?callable $func = null, $this_obj = null ) : void {
	}

	/**
	 * Add a cleanup function to run after assertTest() completes.
	 * @param callable $func
	 */
	public function add_cleanup( callable $func ) : void {
		$this->cleanupFuncs[] = $func;
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
	 * @param callable $closure
	 */
	protected function asyncTestData( callable $closure ) {
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

	// Assertions from testharness.js

	/**
	 * @param bool $actual
	 * @param string $message
	 */
	protected function wptAssertTrue( bool $actual, string $message = '' ) : void {
		Assert::assertTrue( $actual, $message );
	}

	/**
	 * @param bool $actual
	 * @param string $message
	 */
	protected function wptAssertFalse( bool $actual, string $message = '' ) : void {
		Assert::assertFalse( $actual, $message );
	}

	/**
	 * @param mixed $actual
	 * @param mixed $expected
	 * @param string $message
	 */
	protected function wptAssertEquals( $actual, $expected, string $message = '' ) : void {
		Assert::assertEquals( $expected, $actual, $message );
	}

	/**
	 * @param mixed $actual
	 * @param mixed $expected
	 * @param string $message
	 */
	protected function wptAssertNotEquals( $actual, $expected, $message = '' ) : void {
		Assert::assertNotEquals( $expected, $actual, $message );
	}

	/**
	 * @param mixed $needle
	 * @param array $haystack
	 * @param string $description
	 */
	protected function wptAssertInArray( $needle, $haystack, string $description = '' ) : void {
		Assert::assertContains( $needle, $haystack, $description );
	}

	// assert_object_equals is deprecated and unused

	/**
	 * @param array $actual
	 * @param array $expected
	 * @param string $description
	 */
	protected function wptAssertArrayEquals( $actual, array $expected, string $description = '' ) : void {
		// $actual may be a collection, not a literal array
		Assert::assertThat(
			$actual,
			$this->logicalOr(
				$this->isType( 'array' ),
				$this->isInstanceOf( '\ArrayAccess' )
			),
			$description
		);
		Assert::assertCount( count( $expected ), $actual, $description );

		for ( $i = 0; $i < count( $expected ); $i++ ) {
			Assert::assertEquals( $expected[$i], $actual[$i], $description );
		}
	}

	// assert_array_approx_equals is unused
	// assert_approx_equals is unused
	// assert_less_than is unused
	// assert_greater_than is unused
	// assert_between_exclusive is unused
	// assert_less_than_equal is unused
	// assert_greater_than_equal is unused
	// assert_between_inclusive is unused
	// assert_regexp_match is unused

	/**
	 * @param mixed $object
	 * @param string $class_string
	 * @param string $description
	 *
	 * @todo rewrite this
	 *
	 */
	protected function wptAssertClassString( $object, string $class_string, string $description = '' ) : void {
		if ( $class_string === 'String' ) {
			Assert::assertIsString( $object, $description );
		} else {
			Assert::assertInstanceOf(
				"Wikimedia\\IDLeDOM\\$class_string",
				$object,
				$description
			);
		}
	}

	// assert_own_property is unused
	// assert_not_own_property is unused
	// assert_inherits is unused

	/**
	 * @param mixed $object
	 * @param string $property_name
	 * @param string $description
	 */
	protected function wptAssertIdlAttribute( $object, string $property_name, string $description = '' ) : void {
		// This isn't 100% accurate, since isset returns false if the
		// property is null, but it will do for a first draft.
		Assert::assertTrue( isset( $object->$property_name ), $description );
	}

	/**
	 * @param mixed $object
	 * @param string $propertyName
	 * @param string $description
	 */
	protected function wptAssertReadonly( $object, $propertyName, $description = '' ) : void {
		$initialValue = $object->$propertyName;
		try {
			try {
				// Note that this can have side effects in the case where
				//the property has PutForwards
				$object->$propertyName = $initialValue . 'a'; // XXX use some other value here?
			} catch ( Throwable $e ) {
				// We use trigger_error when you try to write to a readonly
				// property; but JavaScript just ignores the write.
			}
			Assert::assertEquals(
				$initialValue,
				$object->$propertyName,
				$description
			);
		} finally {
			// Try to reset the value, this may fail.
			try {
				$object->$propertyName = $initialValue;
			} catch ( Throwable $e ) {
				/* ignore */
			}
		}
	}

	/**
	 * @param class-string $constructor
	 * @param callable $func
	 * @param string $description
	 *
	 * @throws Exception
	 */
	protected function wptAssertThrowsJs( string $constructor, callable $func, string $description = '' ) : void {
		// In practice $constructor always seems to be TypeError
		try {
			$func();
			$this->wptAssertUnreached( "Function did not throw." );
		} catch ( Throwable $e ) {
			if ( $e instanceof AssertionFailedError ) {
				throw $e;
			}
			// PHP workaround: treat ArgumentCountError and native TypeError
			// as instances of IDLeDOM TypeError
			if ( $e instanceof \ArgumentCountError || $e instanceof \TypeError ) {
				Assert::assertTrue( true ); // ensure we "perform an assertion!"
				return; // close enough!
			}
			Assert::assertInstanceOf(
				$constructor,
				$e,
				$description
			);
		}
	}

	/**
	 *
	 * Assert a DOMException with the expected type is thrown.
	 *
	 * The first parameter is the expected exception name or code.  See the
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
	 * @param string|int $codeOrName
	 * @param callable|string|null $funcOrConstructor
	 * @param callable|string|null $descriptionOrFunc
	 * @param string $maybeDescription
	 */
	protected function wptAssertThrowsDom(
		$codeOrName,
		$funcOrConstructor,
		$descriptionOrFunc = null,
		string $maybeDescription = ''
	) : void {
		// Most callers call like this:
		//   wptAssertThrowsDom( $codeOrName, $func, $message )
		// But some weird callers do this:
		//   wptAssertThrowsDom( $codeOrName, DOMException::class, $func, $message)
		$exceptionClass = DOMException::class;
		$func = $funcOrConstructor;
		$description = $descriptionOrFunc ?? '';
		if ( is_callable( $descriptionOrFunc ) ) {
			// weird caller
			if ( is_string( $funcOrConstructor ) ) {
				$exceptionClass = $funcOrConstructor;
			}
			$func = $descriptionOrFunc;
			$description = $maybeDescription;
		}

		try {
			$func();
			$this->wptAssertUnreached( "Function did not throw." );
		} catch ( Throwable $e ) {
			if ( $e instanceof AssertionFailedError ) {
				throw $e;
			}
			if ( !( $e instanceof SimpleException || $e instanceof DOMException ) ) {
				throw $e;
			}
			Assert::assertInstanceOf(
				$exceptionClass,
				$e,
				$description
			);
			'@phan-var DOMException $e';
			if ( is_int( $codeOrName ) ) {
				$code = $codeOrName;
			} else {
				$codename_name_map = [
					'INDEX_SIZE_ERR' => 'IndexSizeError',
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
					'DATA_CLONE_ERR' => 'DataCloneError',
				];
				$name_code_map = [
					'IndexSizeError' => 1,
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
				];
				$code_name_map = array_flip( $name_code_map );
				$name_code_map += [
					'EncodingError' => 0,
					'NotReadableError' => 0,
					'UnknownError' => 0,
					'ConstraintError' => 0,
					'DataError' => 0,
					'TransactionInactiveError' => 0,
					'ReadOnlyError' => 0,
					'VersionError' => 0,
					'OperationError' => 0,
					'NotAllowedError' => 0,
				];
				$name = $codename_name_map[$codeOrName] ?? $codeOrName;
				$code = $name_code_map[$name];
				if ( $code === 0 ) {
					Assert::assertEquals( $name, $e->name, $description );
				}
			}
			Assert::assertEquals( $code, $e->code, $description );
		}
	}

	/**
	 * @param Throwable $exception
	 * @param callable $func
	 * @param string $description
	 */
	protected function wptAssertThrowsExactly( Throwable $exception, callable $func, string $description = '' ) : void {
		try {
			$func();
			$this->wptAssertUnreached( "Function did not throw." );
		} catch ( Throwable $e ) {
			if ( $e instanceof AssertionFailedError ) {
				throw $e;
			}
			Assert::assertEquals( $exception, $e, $description );
		}
	}

	/**
	 * @param string $description
	 */
	protected function wptAssertUnreached( string $description = '' ) : void {
		// @phan-suppress-next-line PhanAccessMethodInternal
		throw new ExpectationFailedException(
			"Reached unreachable code: $description"
		);
	}

	// assert_any is unused
	// assert_implements is unused
	// assert_implements_optional is unused

	// This function is defined in DOMParser-parseFromString-html.html

	/**
	 * @param mixed $actual
	 * @param array $expected
	 */
	public function wptAssertNode( $actual, array $expected ) : void {
		Assert::assertInstanceOf( $expected['type'], $actual );
		if ( ( $expected['id'] ?? null ) !== null ) {
			Assert::assertEquals(
				$expected['id'], $actual->id, $expected['idMessage']
			);
		}
	}

	/**
	 * @param mixed $val
	 * @return string
	 */
	protected function formatValue( $val ): string {
		if ( !( $val instanceof \Wikimedia\IDLeDOM\Node ) ) {
			return json_encode( $val );
		}
		// Special-case Node objects, since those come up a lot in my tests.  I
		// ignore namespaces.
		$truncate = static function ( string $s, int $len ) {
			/*
			* Return a string truncated to the given length, with
			* ... added at the end if it was longer.
			*/
			if ( strlen( $s ) > $len ) {
				return substr( $s, 0, $len - 3 ) . "...";
			}
			return $s;
		};
		switch ( $val->nodeType ) {
		case Node::ELEMENT_NODE:
			'@phan-var Element $val';
			$ret = '<' . $val->localName;
			for ( $i = 0;  $i < count( $val->attributes );  $i++ ) {
				$ret .= ' ' . $val->attributes[ $i ]->name . '="' . $val->attributes[ $i ]->value . '"';
			}
			$ret .= '>' . $val->innerHTML . '</' . $val->localName . '>';
			return 'Element node ' . $truncate( $ret, 60 );
		case Node::TEXT_NODE:
			'@phan-var Text $val';
			return 'Text node "' . $truncate( $val->data, 60 ) . '"';
		case Node::PROCESSING_INSTRUCTION_NODE:
			'@phan-var ProcessingInstruction $val';
			return 'ProcessingInstruction node with target ' .
				$this->formatValue( $truncate( $val->target, 60 ) ) .
				' and data ' .
				$this->formatValue( $truncate( $val->data, 60 ) );
		case Node::COMMENT_NODE:
			'@phan-var Comment $val';
			return 'Comment node <!--' . $truncate( $val->data, 60 ) . '-->';
		case Node::DOCUMENT_NODE:
			return 'Document node with ' . count( $val->childNodes ) .
				( ( count( $val->childNodes ) == 1 ) ? ' child' : ' children' );
		case Node::DOCUMENT_TYPE_NODE:
			return 'DocumentType node';
		case Node::DOCUMENT_FRAGMENT_NODE:
			return 'DocumentFragment node with ' . count( $val->childNodes ) .
				( ( count( $val->childNodes ) == 1 ) ? ' child' : ' children' );
		default:
			return 'Node object of unknown type';
		}
	}

	/**
	 * @param callable $func
	 * @param ?string $name
	 * @param ?array $properties
	 */
	protected function asyncTest( callable $func, ?string $name = null, ?array $properties = null ) : void {
		// XXX To do!

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
	 * @param mixed $x
	 * @param mixed $y
	 *
	 * @return bool
	 */
	protected function sameValue( $x, $y ) : bool {
		if ( $x === 0 && $y === 0 ) {
			// Distinguish +0 and -0
			return var_export( $x, true ) === var_export( $y, true );
		}

		return $x === $y;
	}

	/**
	 * STUB
	 */
	protected function done() : void {
	}

	/**
	 * @param callable|array $func_or_properties
	 * @param ?array $maybe_properties
	 */
	protected function setupData( $func_or_properties, ?array $maybe_properties = null ) : void {
		$func = null;
		$properties = [];
		if ( is_callable( $func_or_properties ) ) {
			$func = $func_or_properties;
			$properties = $maybe_properties ?? $properties;
		} else {
			'@phan-var array $func_or_properties';
			$properties = $func_or_properties;
		}
		// XXX We don't have a $tests object yet...
		#$tests->setup( $func, $properties );
		#$test_environment->on_new_harness_properties( $properties );
	}

	/**
	 * @param Element $el
	 * @param array $l
	 */
	protected function attributesAre( $el, $l ) {
		for ( $i = 0, $il = count( $l ); $i < $il; $i++ ) {
			$this->attrIs(
				$el->attributes[$i],
				$l[$i][1],
				$l[$i][0],
				( count( $l[$i] ) < 3 ) ? null : $l[$i][2],
				null,
				$l[$i][0]
			);
			Assert::assertEquals(
				$el,
				$el->attributes[$i]->ownerElement
			);
		}
	}

	/**
	 * @param Attr $attr
	 * @param string $v Value
	 * @param string $ln Local name
	 * @param ?string $ns Namespace
	 * @param ?string $p Prefix
	 * @param string $n Node name
	 */
	protected function attrIs( $attr, $v, $ln, $ns, $p, $n ) {
		Assert::assertEquals( $attr->value, $v );
		Assert::assertEquals( $attr->nodeValue, $v );
		Assert::assertEquals( $attr->textContent, $v );
		Assert::assertEquals( $attr->localName, $ln );
		Assert::assertEquals( $attr->namespaceURI, $ns );
		Assert::assertEquals( $attr->prefix, $p );
		Assert::assertEquals( $attr->name, $n );
		Assert::assertEquals( $attr->nodeName, $n );
		Assert::assertEquals( $attr->specified, true );
	}

	/**
	 * TODO implement this
	 *
	 * @param callable $func
	 * @param array $args
	 * @param array &$properties
	 */
	protected function generateTests( $func, $args, &$properties ) : void {
		foreach ( $args as $i => $x ) {
			$name = array_shift( $x );
			$this->assertTest(
				static function () use ( $func, $x ) {
					$func( ...$x );
				},
				$name,
				$properties[$i] ?? $properties
			);
		}
	}

	/**
	 * @param callable $closure
	 * @param string $message
	 * @param array $properties
	 */
	protected function assertTest(
		callable $closure,
		string $message = '',
		array $properties = []
	) : void {
		$this->cleanupFuncs = [];
		try {
			$closure( null );
		} finally {
			// @phan-suppress-next-line PhanEmptyForeach $closure may add cleanups
			foreach ( $this->cleanupFuncs as $f ) {
				$f();
			}
			$this->cleanupFuncs = [];
		}
	}

	/**
	 * @param array|\stdClass $array
	 * @param callable $callback
	 *
	 * @return array
	 */
	protected function arrayMap( $array, callable $callback ) : array {
		if ( is_array( $array ) ) {
			return array_map( $callback, $array );
		}
		// but array could be an object implementing ArrayAccess...
		$result = [];
		foreach ( $array as $v ) {
			$result[] = $callback( $v );
		}
		return $result;
	}

	/**
	 * The test cases use eval on strings in a somewhat-sketchy way to
	 * select one of a number of different nodes in a shared document.
	 * Hack around to pull out the right node, even though we're not
	 * actually evaluating JavaScript. Luckily, none of these expressions
	 * are particularly complicated.
	 *
	 * @param string $nodeExpr
	 * @return mixed
	 */
	protected function wptEvalNode( string $nodeExpr ) {
		// Some of these are arrays
		if ( substr( $nodeExpr, 0, 1 ) === '[' ) {
			$result = [];
			foreach ( explode( ',', substr( $nodeExpr, 1, -1 ) ) as $item ) {
				$result[] = $this->wptEvalNode( trim( $item ) );
			}
			return $result;
		}
		// Some nodes are pure numbers
		if ( preg_match( '/^[-+]?[0-9]+$/', $nodeExpr ) === 1 ) {
			return intval( $nodeExpr );
		}
		// Handle property accessors
		if ( strpos( $nodeExpr, '.' ) !== false ) {
			$props = explode( '.', $nodeExpr );
			$obj = $this->wptEvalNode( array_shift( $props ) );
			foreach ( $props as $p ) {
				$obj = $obj->{$p};
			}
			return $obj;
		}
		// Handle array accessors
		if ( substr( $nodeExpr, -1 ) === ']' ) {
			$pos = strrpos( $nodeExpr, '[' );
			$lhs = $this->wptEvalNode( substr( $nodeExpr, 0, $pos ) );
			$rhs = $this->wptEvalNode( substr( $nodeExpr, $pos + 1, -1 ) );
			return $lhs[$rhs];
		}
		// ok, this *should* be a named field of Common
		return $this->getCommon()->{$nodeExpr};
	}

	/**
	 * Return the 'Common' singleton, which contains a fixture used for
	 * range tests, document position tests, etc.
	 * @return Common
	 */
	protected function getCommon() {
		if ( $this->common === null ) {
			$this->common = new Common( $this->doc );
			$this->common->setupRangeTests();
		}
		return $this->common;
	}
}
