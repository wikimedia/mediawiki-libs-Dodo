<?php

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
	 * ParserTask constructor.
	 *
	 * @param string $test
	 * @param string $test_name
	 * @param string $test_type
	 */
	public function __construct( string $test, string $test_name, string $test_type ) {
		$this->test = $test;
		$this->finder = new NodeFinder;
		$this->parser = ( new ParserFactory )->create( ParserFactory::ONLY_PHP7 );
		$this->dumper = new NodeDumper;
		$this->factory = new BuilderFactory;
		$this->test_type = $test_type;
		$this->test_name = $test_name;
		$this->file = '';
	}

	/**
	 * @return Result
	 */
	public function run() : Result {
		$this->preprocessTest();
		try {
			if ( $this->test_type == 'w3c' ) {
				$ast = $this->parser->parse( '<?php ' . $this->test );
				$this->parseW3cTest( $ast );
				$this->removeW3CDisparity();
			}

			if ( $this->test_type == 'wpt' ) {
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
	 * @param array $ast
	 */
	protected function parseW3cTest( array $ast ) : void {
		$traverser = new NodeTraverser;

		$traverser->addVisitor( new class( $this->test_name ) extends NodeVisitorAbstract {
			use Helpers;

			/**
			 * @var string
			 */
			public $test_name;

			/**
			 *  constructor.
			 *
			 * @param string $test_name
			 */
			public function __construct( string $test_name ) {
				$this->test_name = $test_name;
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

				if ( $node instanceof If_ ) {
					return NodeTraverser::REMOVE_NODE;
				}

				if ( $node instanceof Expression && $node->expr instanceof FuncCall ) {
					$expr_name = $node->expr->name->parts[0];
					if ( empty( $expr_name ) ) {
						return $node;
					}

					$functions_calls = [ 'assert' ];

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

		$ast = $traverser->traverse( $ast );

		$factory = new BuilderFactory;
		$class = $factory->class( $this->snakeToCamel( $this->test_name ) . 'Test' )->extend( 'DodoBaseTest' )
			->addStmts( $ast )->getNode();
		$stmts = $factory->namespace( 'Wikimedia\Dodo\Tests' )->addStmts( [ $factory->use( 'Exception' ),
			$class ] )->getNode();

		/* $load_func = $this->findFuncCall( $ast, 'load' ); */

		$prettyPrinter = new PrettyPrinter\Standard();
		$this->test = "<?php \n" . $prettyPrinter->prettyPrint( [ $stmts ] );
	}

	/**
	 * Removes disparity for W3C tests
	 */
	protected function removeW3CDisparity() {
		$find_replace = [ 'global $builder;' => '',
			'$success = null;' => '',
			'load(' => '$this->load(',
			'$doc->' => '$this->doc->',
			'->item(0)' => '[0]',
			'$doc =' => '$this->doc =',
			'$ex->code' => '$ex->getCode()',
			'assertSize' => '$this->assertSize' ];

		$this->test = strtr( $this->test,
			$find_replace );

		$this->test = preg_replace( '#/*(.*?)*/#is',
			'',
			$this->test );
		$this->test = preg_replace( '#assertNull\((.*?),#is',
			'$this->assertNull(',
			$this->test );

		$this->test = preg_replace( '#assertEquals\((.*?),#is',
			'$this->assertEquals(',
			$this->test );
	}

	/**
	 * Removes disparity after js2php
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
			} );

		$ast = array_filter( $ast,
			function ( $smtm ) {
				if ( !$smtm instanceof Function_ ) {
					return $smtm;
				}
			} );

		$node = $factory->method( $this->test_name )->makePublic()->addStmts( $ast )->getNode();

		$stmts = array_merge( $functions,
			[ $node ] );

		$traverser = new NodeTraverser;

		$dumper = new NodeDumper;
		$dump = $dumper->dump( $ast ) . "n";

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

				if ( $node instanceof Function_ ) {
					$node = $factory->method( $this->snakeToCamel( $node->name->name ) )->makePublic()
						->addStmts( $node->stmts )->addParams( $node->getParams() )->getNode();

					return $node;
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
			}
		} );

		$stmts = $traverser->traverse( $stmts );
		// create test class
		$class = $factory->class( $this->test_name . 'Test' )->extend( 'DodoBaseTest' )->addStmts( $stmts )->getNode();
		/* TODO add a logic here, if there is new Document() -> add Dodo\Document  */
		$use_stmts = $factory->use( 'Wikimedia\Dodo\Document' );
		$stmts = $factory->namespace( 'Wikimedia\Dodo\Tests' )->addStmts( [ $use_stmts,
			$class ] )->getNode();

		$prettyPrinter = new PrettyPrinter\Standard();
		$this->test = "<?php \n" . $prettyPrinter->prettyPrint( [ $stmts ] );
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
	 * @return string|string[]
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
}
