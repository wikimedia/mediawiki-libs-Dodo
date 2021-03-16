<?php

declare( strict_types = 1 );
// XXX Should fix these!
// @phan-file-suppress PhanTypeArraySuspiciousNullable
// @phan-file-suppress PhanTypeMismatchArgumentInternal
// @phan-file-suppress PhanTypeMismatchArgumentNullable
// @phan-file-suppress PhanUndeclaredProperty
// @phan-file-suppress PhanPossiblyUndeclaredVariable

namespace Wikimedia\Dodo\Tools\TestsGenerator;

use PhpParser\BuilderFactory;
use PhpParser\Comment;
use PhpParser\Comment\Doc;
use PhpParser\Error;
use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\If_;
use PhpParser\NodeDumper;
use PhpParser\NodeFinder;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use PhpParser\Parser;
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter;
use Robo\Result;
use Robo\Task\BaseTask;

class ParserTask extends BaseTask {
	use Helpers;

	/**
	 * @var string
	 */
	private $test;

	/**
	 * @var Parser
	 */
	private $parser;

	/**
	 * @var NodeFinder
	 */
	private $finder;

	/**
	 * @var NodeDumper
	 */
	private $dumper;

	/**
	 * @var BuilderFactory
	 */
	private $factory;

	/**
	 * @var string
	 */
	private $test_type;

	/**
	 * @var string
	 */
	private $test_name;

	/**
	 * @var string
	 */
	private $file;

	/**
	 * @var bool
	 */
	private $wrap_only;

	/**
	 * @var bool
	 */
	private $compact;

	/**
	 * @var string
	 */
	private $test_path;

	/**
	 * ParserTask constructor.
	 *
	 * @param string $test
	 * @param string $test_name
	 * @param string $test_type
	 * @param bool $compact
	 * @param bool $wrap_only
	 * @param string|null $test_path
	 */
	public function __construct( string $test, string $test_name, string $test_type, bool $compact = false,
		bool $wrap_only = false, ?string $test_path = null ) {
		$this->test = $test;
		$this->finder = new NodeFinder;
		$this->parser = ( new ParserFactory )->create( ParserFactory::ONLY_PHP7 );
		$this->dumper = new NodeDumper;
		$this->factory = new BuilderFactory;
		$this->test_type = $test_type;
		$this->test_name = $test_name;
		$this->test_path = self::relpath( $test_path ) ?: $test_name;
		$this->file = '';
		$this->compact = $compact;
		$this->wrap_only = $wrap_only;
	}

	/**
	 * Strip the part of the given path which is outside of this repository.
	 * @param string $path
	 * @return string The stripped path
	 */
	private static function relpath( string $path ): string {
		$path = realpath( $path );
		$base = realpath( __DIR__ . '/../..' );
		$min = ( strlen( $path ) < strlen( $base ) ) ? strlen( $path ) : strlen( $base );
		for ( $i = 0; $i < $min; $i++ ) {
			if ( $path[$i] !== $base[$i] ) {
				break;
			}
		}
		return ltrim( substr( $path, $i ), '/' );
	}

	/**
	 * @return Result
	 */
	public function run() : Result {
		$this->preprocessTest();
		try {
			if ( $this->wrap_only ) {
				// TestWrapper is need only for proper parsing.
				$ast = $this->parser->parse( '<?php class TestWrapper {' . $this->test . '}' );
				$this->wrapInClass( $ast );
			}

			if ( $this->test_type == 'w3c' && !$this->wrap_only ) {
				$ast = $this->parser->parse( '<?php ' . $this->test );
				$this->parseW3cTest( $ast );
				$this->removeW3CDisparity();
			}

			if ( $this->test_type == 'wpt' && !$this->wrap_only ) {
				$this->removeWptDisparity();
				$ast = $this->parser->parse( '<?php ' . $this->test );
				$this->parseWptTest( $ast );
			}
		} catch ( Error $error ) {
			return Result::error( $this,
				"Parse error: {$error->getMessage()}\n",
				[ $this->test ] );
		}

		return Result::success( $this,
			'All good.',
			[ $this->test ] );
	}

	/**
	 */
	protected function preprocessTest() {
		$find = [ 'new Array()' ];
		$replace = [ '[]' ];

		$this->test = str_replace( $find,
			$replace,
			$this->test );
	}

	/**
	 * @param array $stmts
	 */
	protected function wrapInClass( array $stmts ) {
		$factory = new BuilderFactory;
		$stmts = $stmts[0]->stmts;

		if ( $this->test_type == 'w3c' ) {
			$class = $factory->class( $this->snakeToCamel( $this->test_name ) . 'Test' )->extend( 'DomTestCase' )
				->addStmts( $stmts )->getNode();
			$stmts = $factory->namespace( 'Wikimedia\Dodo\Tests' )->addStmts( [ $factory->use( 'Exception' ),
				$class ] )->getNode();
		} else {
			// create test class
			$class = $factory->class( $this->test_name . 'Test' )->extend( 'DodoBaseTest' )->addStmts( $stmts )
				->getNode();
			$use_stmts = $factory->use( 'Wikimedia\Dodo\Document' );
			$stmts = $factory->namespace( 'Wikimedia\Dodo\Tests' )->addStmts( [ $use_stmts,
				$class ] )->getNode();
		}

		$prettyPrinter = new PrettyPrinter\Standard();
		$this->test = "<?php \n" . $prettyPrinter->prettyPrint( [ $stmts ] );
	}

	/**
	 * @param array $stmts
	 */
	protected function parseW3cTest( array $stmts ) : void {
		$traverser = new NodeTraverser;

		$dumper = new NodeDumper;
		$dump = $dumper->dump( $stmts ) . "n";

		$traverser->addVisitor( new class( $this->test_name, $this->parser ) extends NodeVisitorAbstract {
			use Helpers;

			/**
			 * @var string
			 */
			public $test_name;
			/**
			 * @var Parser
			 */
			private $parser;

			/**
			 *  constructor.
			 *
			 * @param string $test_name
			 * @param Parser $parser
			 */
			public function __construct( string $test_name, Parser $parser ) {
				$this->test_name = $test_name;
				$this->parser = $parser;
			}

			/**
			 * @param Node $node
			 *
			 * @return int|Node|Function_
			 */
			public function leaveNode( Node $node ) {
				$node_type = $node->getType();
				if ( $node instanceof Function_ && $node->name->toString() === $this->test_name ) {
					$factory = new BuilderFactory;

					$test_method = $this->snakeToCamel( 'test ' . $node->name );

					return $factory->method( $test_method )->makePublic()->addStmts( $node->getStmts() )->getNode();
				}

				if ( $node instanceof Expression && $node->expr instanceof FuncCall ) {
					$expr_name = $node->expr->name->parts[0];
					if ( empty( $expr_name ) ) {
						return $node;
					}

					$functions_calls = [ 'assert',
						'getImplementation',
						'checkInitialization' ];

					if ( preg_match( '(' . implode( '|',
								$functions_calls ) . ')',
							$expr_name ) === 1 ) {
						$args = $node->expr->args;

						$call = new Node\Expr\MethodCall( new Variable( 'this' ),
							$this->snakeToCamel( $expr_name ) . 'Data',
							$args,
							$node->expr->getAttributes() );

						$node->expr = $call;

						return $node;
					}
				}

				// remove all other functions
				if ( $node instanceof Function_ || $node instanceof Comment || $node instanceof Doc ) {
					return NodeTraverser::REMOVE_NODE;
				}

				// unset variable
				if ( $node instanceof Expression && $node->expr instanceof Assign ) {
					if ( $node->expr->var instanceof Variable ) {
						if ( $node->expr->var->name == 'docsLoaded' || $node->expr->var->name == 'builder' ) {
							return NodeTraverser::REMOVE_NODE;
						}
					}
				}
			}
		} );

		$traverser->addVisitor( new class( $this->test_name, $this->parser ) extends NodeVisitorAbstract {
			use Helpers;

			/**
			 * @var string
			 */
			public $test_name;
			/**
			 * @var Parser
			 */
			private $parser;

			/**
			 *  constructor.
			 *
			 * @param string $test_name
			 * @param Parser $parser
			 */
			public function __construct( string $test_name, Parser $parser ) {
				$this->test_name = $test_name;
				$this->parser = $parser;
			}

			/**
			 * @param Node $node
			 *
			 * @return int|Node|Function_
			 */
			public function leaveNode( Node $node ) {
				if ( $node instanceof If_ ) {
					$left_part = $node->cond->left;
					if (
						isset( $left_part ) && isset( $left_part->name ) &&
						is_object( $left_part->name ) &&
						$left_part->name->parts[0] == 'gettype' &&
						$left_part->args[0]->value->name->name == 'code' &&
						$node->cond->left->args[0]->value->var->name == 'ex' ) {
						$ast = $this->parser->parse( '<?php ' .
							'$this->assertEquals( DOMException::NO_MODIFICATION_ALLOWED_ERR, $ex->getCode());' );

						return $ast[0];
					}
				}
			}
		} );

		$stmts = $traverser->traverse( $stmts );

		if ( !$this->compact ) {
			$factory = new BuilderFactory;
			$class = $factory->class( $this->snakeToPascal( $this->test_name ) . 'Test' )->extend( 'DomTestCase' )
				->addStmts( $stmts )->setDocComment( '// @see ' . $this->test_path . '.' )->getNode();
			$stmts = $factory->namespace( 'Wikimedia\Dodo\Tests' )
				->addStmts( [ $factory->use( 'Wikimedia\Dodo\DomException' ),
					$factory->use( 'Exception' ),
					$class ] )->getNode();
		}

		/* $load_func = $this->findFuncCall( $ast, 'load' ); */
		$this->prettyPrint( $stmts );
	}

	/**
	 * @param mixed $stmts
	 */
	protected function prettyPrint( $stmts ) {
		if ( is_array( $stmts ) && count( $stmts ) == 1 ) {
			$stmts = array_pop( $stmts );
		}
		$prettyPrinter = new PrettyPrinter\Standard();
		if ( !is_array( $stmts ) ) {
			$stmts = [ $stmts ];
		}
		if ( $this->compact && $this->test_type == 'w3c' ) {
			$this->test = $prettyPrinter->prettyPrint( $stmts );
		} else {
			$this->test = "<?php \n" . $prettyPrinter->prettyPrint( $stmts );
		}
	}

	/**
	 * Removes disparity for W3C tests.
	 */
	protected function removeW3CDisparity() {
		$find_replace = [ 'global $builder;' => '$builder = $this->getBuilder();',
			'$success = null;' => '',
			'load(' => '$this->load(',
			'->item(0)' => '[0]',
			'$ex->code' => '$ex->getCode()',
			'checkInitialization(' => '$this->checkInitialization(',
			'Exception $ex' => 'DomException $ex',
			'fail(' => '$this->makeFailed(',
			'throw $ex;' => '$this->fail($ex->getMessage());' ];

		$this->test = strtr( $this->test,
			$find_replace );

		// Remove unnecessary empty lines.
		$this->test = preg_replace( '/^[ \t]*[\r\n]+/m',
			'',
			$this->test );
	}

	/**
	 * Removes disparity after js2php.
	 */
	protected function removeWptDisparity() {
		$find_replace = [ '$document->' => '$this->doc->',
			'= create(' => '= $this->create(',
			'$TypeError' => '$this->type_error',
			'test_constructor' => '$this->testConstructor',
			'Object::keys( $testExtensions )->' => '$testExtensions->',
			'new DOMParser()' => '(new DOMParser())',
			'$win::' => "",
			'new XMLHttpRequest();' => '$this->xmlHttpRequest();' ];

		$this->test = strtr( $this->test,
			$find_replace );

		// Remove unnecessary empty lines.
		$this->test = preg_replace( '/^[ \t]*[\r\n]+/m',
			'',
			$this->test );
	}

	/**
	 * Parses WPT test
	 *
	 * @param array $ast
	 */
	protected function parseWptTest( array $ast ) {
		$factory = new BuilderFactory;
		$this->test_name = $this->snakeToCamel( 'test ' . $this->test_name );

		$functions = array_filter( $ast,
			function ( $smtm ) {
				if ( $smtm instanceof Function_ ) {
					return $smtm;
				}

				return null;
			} );

		$ast = array_filter( $ast,
			function ( $smtm ) {
				if ( !$smtm instanceof Function_ ) {
					return $smtm;
				}

				return null;
			} );

		$node = $factory->method( $this->test_name )->makePublic()->addStmts( $ast )->getNode();

		$stmts = array_merge( $functions,
			[ $node ] );

		if ( $this->test_name == 'testAppendOnDocument' ) {
			$stmts = $functions;
		}

		$traverser = new NodeTraverser;

		$dumper = new NodeDumper;
		$dump = $dumper->dump( $stmts ) . "n";

		$traverser->addVisitor( new class() extends NodeVisitorAbstract {
			use Helpers;

			/**
			 * @param Node $node
			 *
			 * @return int|Node|Function_
			 */
			public function leaveNode( Node $node ) {
				$factory = new BuilderFactory;

				if ( $node instanceof Expression && $node->expr instanceof Node\Expr\MethodCall ) {
					if ( $node->expr->name->name == 'forEach' ) {
						if ( isset( $node->expr->var->name ) ) {
							$for_expr = new Node\Expr\Variable( $node->expr->var->name );
						}

						if ( $node->expr->var instanceof Node\Expr\ArrayDimFetch ) {
							$for_expr = new Node\Expr\Variable( $node->expr->var->var->name );
						}

						if ( $node->expr->var instanceof Node\Expr\Array_ ) {
							$for_expr = $node->expr->var;
						}

						$new_cycle = new Node\Stmt\Foreach_( $for_expr,
							new Node\Expr\Variable( $node->expr->args[0]->value->params[0]->var->name ) );
						$new_cycle->stmts = $node->expr->args[0]->value->stmts;

						return $new_cycle;
					}
				}

				// remove all other functions
				if ( $node instanceof Comment || $node instanceof Doc ) {
					return NodeTraverser::REMOVE_NODE;
				}

				if ( $node instanceof Expression && $node->expr instanceof FuncCall ) {
					$expr_name = $node->expr->name->parts[0];
					if ( empty( $expr_name ) ) {
						return $node;
					}

					$functions_calls = [ 'testNode',
						'create',
						'test',
						'async_test',
						'testRemove',
						'assert',
						'test_',
						'_test',
						'check' ];

					if ( preg_match( '(' . implode( '|',
								$functions_calls ) . ')',
							$expr_name ) === 1 ) {
						$args = $node->expr->args;
						if ( $expr_name == 'assert_equals' || $expr_name == 'assert_not_equals' ) {
							[ $args[0],
								$args[1] ] = [ $args[1],
								$args[0] ];
						}

						$call = new Node\Expr\MethodCall( new Variable( 'this' ),
							$this->snakeToCamel( $expr_name ),
							$args,
							$node->expr->getAttributes() );

						$node->expr = $call;

						return $node;
					}
				}

				if ( $node instanceof Function_ ) {
					$node = $factory->method( $this->snakeToCamel( $node->name->name ) )->makePublic()
						->addStmts( $node->stmts )->addParams( $node->getParams() )->getNode();

					return $node;
				}
			}

		} );

		$stmts = $traverser->traverse( $stmts );

		$traverser->addVisitor( new class() extends NodeVisitorAbstract {
			use Helpers;

			/**
			 * @param Node $node
			 *
			 * @return int|Node|Function_
			 */
			public function leaveNode( Node $node ) {
				if ( $node instanceof Node\Stmt\ClassMethod ) {
					$node_name = $node->name->name;
					if ( strpos( $node_name,
							'test' ) !== false ) {
						array_unshift( $node->stmts,
							$this->addExpectation( 'expectException' ) );
					}

					return $node;
				}
			}
		} );
		$stmts = $traverser->traverse( $stmts );

		if ( !$this->compact ) {
			// create test class
			$class = $factory->class( $this->test_name . 'Test' )->extend( 'DodoBaseTest' )->addStmts( $stmts )
				->setDocComment( '// @see ' . $this->test_path . '.' )->getNode();
			/* TODO add a logic here, if there is new Document() -> add Dodo\Document  */
			$use_stmts = $factory->use( 'Wikimedia\Dodo\Document' );
			$stmts = $factory->namespace( 'Wikimedia\Dodo\Tests' )->addStmts( [ $use_stmts,
				$class ] )->getNode();
		}

		$this->prettyPrint( $stmts );
	}

	/**
	 * Marks the test as skipped.
	 *
	 * @return Node\Expr\MethodCall
	 */
	protected function markTestAsSkipped() : Node\Expr\MethodCall {
		return new Node\Expr\MethodCall( new Variable( 'this' ),
			'markTestSkipped' );
	}

	/**
	 * @param array $ast
	 */
	protected function parseW3cHarness( array $ast ) : void {
	}

	/**
	 * @param array $ast
	 */
	protected function parseWptHarness( array $ast ) : void {
	}

	/**
	 *
	 * @param array $ast
	 * @param string $name
	 *
	 * @return null|Node
	 */
	protected function findFuncCall( array $ast, string $name ) : ?Node {
		return $this->finder->findFirst( $ast,
			function ( Node $node ) use ( $name ) {
				if ( isset( $node->expr ) && isset( $node->expr->name ) && $node->expr instanceof FuncCall ) {
					$expr_name = $node->expr->name->toString();
					if ( $expr_name == $name ) {
						return $node;
					}
				}
			} );
	}

	/**
	 * @param string $code
	 *
	 * @return string
	 */
	protected function makeFuncPublic( string $code ) : string {
		return str_replace( 'function',
			"public function",
			$code );
	}

	/**
	 * @param array $ast
	 * @param string $name
	 */
	protected function find( array $ast, string $name ) {
		$nodeFinder = new NodeFinder;
	}

	/**
	 * @param array|Node $ast
	 *
	 * @return string
	 */
	protected function dumpAst( $ast ) : string {
		$dumper = new NodeDumper;

		return $dumper->dump( $ast ) . "n";
	}

}
