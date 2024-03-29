<?php

declare( strict_types = 1 );

namespace Wikimedia\Dodo\Tests\Harness\Utils;

use Exception;
use PHPUnit\Framework\Assert;
use ReflectionClass;
use Wikimedia\IDLeDOM\CharacterData;
use Wikimedia\IDLeDOM\Comment;
use Wikimedia\IDLeDOM\Document;
use Wikimedia\IDLeDOM\DocumentFragment;
use Wikimedia\IDLeDOM\DocumentType;
use Wikimedia\IDLeDOM\DOMException;
use Wikimedia\IDLeDOM\Element;
use Wikimedia\IDLeDOM\Node;
use Wikimedia\IDLeDOM\ProcessingInstruction;
use Wikimedia\IDLeDOM\Range;
use Wikimedia\IDLeDOM\Text;

/**
 * Common.php
 * --------
 * Derived from common.js
 *
 * @see vendor/web-platform-tests/wpt/dom/common.js
 * @package Wikimedia\Dodo\Tests
 */
class Common {
	// Everything is done in functions in this test harness, so we have to declare
	// all the variables before use to make sure they can be reused.

	/** @var Document */
	public $document = null;
	/** @var Element */
	public $testDiv = null;
	/** @var Element[] */
	public $paras = null;
	/** @var Element */
	public $detachedDiv = null;
	/** @var Element */
	public $detachedPara1 = null;
	/** @var Element */
	public $detachedPara2 = null;
	/** @var Document */
	public $foreignDoc = null;
	/** @var Element */
	public $foreignPara1 = null;
	/** @var Element */
	public $foreignPara2 = null;
	/** @var Document */
	public $xmlDoc = null;
	/** @var Element */
	public $xmlElement = null;
	/** @var Element */
	public $detachedXmlElement = null;
	/** @var Text */
	public $detachedTextNode = null;
	/** @var Text */
	public $foreignTextNode = null;
	/** @var Text */
	public $detachedForeignTextNode = null;
	/** @var Text */
	public $xmlTextNode = null;
	/** @var Text */
	public $detachedXmlTextNode = null;
	/** @var ProcessingInstruction */
	public $processingInstruction = null;
	/** @var ProcessingInstruction */
	public $detachedProcessingInstruction = null;
	/** @var Comment */
	public $comment = null;
	/** @var Comment */
	public $detachedComment = null;
	/** @var Comment */
	public $foreignComment = null;
	/** @var Comment */
	public $detachedForeignComment = null;
	/** @var Comment */
	public $xmlComment = null;
	/** @var Comment */
	public $detachedXmlComment = null;
	/** @var DocumentFragment */
	public $docfrag = null;
	/** @var DocumentFragment */
	public $foreignDocfrag = null;
	/** @var DocumentFragment */
	public $xmlDocfrag = null;
	/** @var DocumentType */
	public $doctype = null;
	/** @var DocumentType */
	public $foreignDoctype = null;
	/** @var DocumentType */
	public $xmlDoctype = null;
	/** @var string[] */
	public $testRangesShort = null;
	/** @var string[] */
	public $testRanges = null;
	/** @var string[] */
	public $testPoints = null;
	/** @var string[] */
	public $testNodesShort = null;
	/** @var string[] */
	public $testNodes = null;

	/**
	 * Create a new instance to hold data needed for Range tests.
	 * @param Document $doc
	 */
	public function __construct( $doc ) {
		$this->document = $doc;
	}

	/**
	 * Set up some shared state for range tests.
	 */
	public function setupRangeTests() {
		$document = $this->document;
		$this->testDiv = $document->querySelector( '#test' );
		if ( $this->testDiv ) {
			$this->testDiv->parentNode->removeChild( $this->testDiv );
		}
		$this->testDiv = $document->createElement( 'div' );
		$this->testDiv->id = 'test';
		$document->body->insertBefore( $this->testDiv,
			$document->body->firstChild );

		$this->paras = [];
		$this->paras[] = $document->createElement( 'p' );
		$this->paras[0]->setAttribute( 'id',
			'a' );
		// Test some diacritics, to make sure browsers are using code units here
		// and not something like grapheme clusters.
		$this->paras[0]->textContent = "Äb̈c̈d̈ëf̈g̈ḧ\n";
		$this->testDiv->appendChild( $this->paras[0] );

		$this->paras[] = $document->createElement( 'p' );
		$this->paras[1]->setAttribute( 'id',
			'b' );
		$this->paras[1]->setAttribute( 'style',
			'display:none' );
		$this->paras[1]->textContent = "Ijklmnop\n";
		$this->testDiv->appendChild( $this->paras[1] );

		$this->paras[] = $document->createElement( 'p' );
		$this->paras[2]->setAttribute( 'id',
			'c' );
		$this->paras[2]->textContent = 'Qrstuvwx';
		$this->testDiv->appendChild( $this->paras[2] );

		$this->paras[] = $document->createElement( 'p' );
		$this->paras[3]->setAttribute( 'id',
			'd' );
		$this->paras[3]->setAttribute( 'style',
			'display:none' );
		$this->paras[3]->textContent = 'Yzabcdef';
		$this->testDiv->appendChild( $this->paras[3] );

		$this->paras[] = $document->createElement( 'p' );
		$this->paras[4]->setAttribute( 'id',
			'e' );
		$this->paras[4]->setAttribute( 'style',
			'display:none' );
		$this->paras[4]->textContent = 'Ghijklmn';
		$this->testDiv->appendChild( $this->paras[4] );

		$this->detachedDiv = $document->createElement( 'div' );
		$this->detachedPara1 = $document->createElement( 'p' );
		$this->detachedPara1->appendChild( $document->createTextNode( 'Opqrstuv' ) );
		$this->detachedPara2 = $document->createElement( 'p' );
		$this->detachedPara2->appendChild( $document->createTextNode( 'Wxyzabcd' ) );
		$this->detachedDiv->appendChild( $this->detachedPara1 );
		$this->detachedDiv->appendChild( $this->detachedPara2 );

		// Opera doesn't automatically create a doctype for a new HTML document,
		// contrary to spec.  It also doesn't let you add doctypes to documents
		// after the fact through any means I've tried.  So foreignDoc in Opera
		// will have no doctype, foreignDoctype will be null, and Opera will fail
		// some tests somewhat mysteriously as a result.
		$this->foreignDoc = $document->implementation->createHTMLDocument( '' );
		$this->foreignPara1 = $this->foreignDoc->createElement( 'p' );
		$this->foreignPara1->appendChild( $this->foreignDoc->createTextNode( 'Efghijkl' ) );
		$this->foreignPara2 = $this->foreignDoc->createElement( 'p' );
		$this->foreignPara2->appendChild( $this->foreignDoc->createTextNode( 'Mnopqrst' ) );
		$this->foreignDoc->body->appendChild( $this->foreignPara1 );
		$this->foreignDoc->body->appendChild( $this->foreignPara2 );

		// Now we get to do really silly stuff, which nobody in the universe is
		// ever going to actually do, but the spec defines behavior, so too bad.
		// Testing is fun!
		$this->xmlDoctype = $document->implementation->createDocumentType( 'qorflesnorf',
			'abcde',
			"x\"'y" );
		$this->xmlDoc = $document->implementation->createDocument( null,
			null,
			$this->xmlDoctype );
		$this->detachedXmlElement = $this->xmlDoc->createElement( 'everyone-hates-hyphenated-element-names' );
		$this->detachedTextNode = $document->createTextNode( 'Uvwxyzab' );
		$this->detachedForeignTextNode = $this->foreignDoc->createTextNode( 'Cdefghij' );
		$this->detachedXmlTextNode = $this->xmlDoc->createTextNode( 'Klmnopqr' );
		// PIs only exist in XML documents, so don't bother with document or
		// foreignDoc.
		$this->detachedProcessingInstruction = $this->xmlDoc->createProcessingInstruction( 'whippoorwill',
			'chirp chirp chirp' );
		$this->detachedComment = $document->createComment( 'Stuvwxyz' );
		// Hurrah, we finally got to "z" at the end!
		$this->detachedForeignComment = $this->foreignDoc->createComment( "אריה יהודה" );
		$this->detachedXmlComment = $this->xmlDoc->createComment( "בן חיים אליעזר" );

		// We should also test with document fragments that actually contain stuff
		// . . . but, maybe later.
		$this->docfrag = $document->createDocumentFragment();
		$this->foreignDocfrag = $this->foreignDoc->createDocumentFragment();
		$this->xmlDocfrag = $this->xmlDoc->createDocumentFragment();

		$this->xmlElement = $this->xmlDoc->createElement( 'igiveuponcreativenames' );
		$this->xmlTextNode = $this->xmlDoc->createTextNode( 'do re mi fa so la ti' );
		$this->xmlElement->appendChild( $this->xmlTextNode );
		$this->processingInstruction = $this->xmlDoc->createProcessingInstruction(
			'somePI',
			'Did you know that ":syn sync fromstart" is very useful ' .
			'when using vim to edit large amounts of JavaScript embedded ' .
			'in HTML?'
		);
		$this->xmlDoc->appendChild( $this->xmlElement );
		$this->xmlDoc->appendChild( $this->processingInstruction );
		$this->xmlComment = $this->xmlDoc->createComment(
			'I maliciously created a comment that will break incautious ' .
			'XML serializers, but Firefox threw an exception, so all I got ' .
			'was this lousy T-shirt'
		);
		$this->xmlDoc->appendChild( $this->xmlComment );

		$this->comment = $document->createComment( 'Alphabet soup?' );
		$this->testDiv->appendChild( $this->comment );

		$this->foreignComment = $this->foreignDoc->createComment(
			"\"Commenter\" and \"commentator\" mean different things.  " .
			"I've seen non-native speakers trip up on this."
		);
		$this->foreignDoc->appendChild( $this->foreignComment );
		$this->foreignTextNode = $this->foreignDoc->createTextNode(
			"I admit that I harbor doubts about whether we really need " .
			"so many things to test, but it's too late to stop now."
		);
		$this->foreignDoc->body->appendChild( $this->foreignTextNode );

		$this->doctype = $document->doctype;
		Assert::assertNotNull( $this->doctype, "this shouldn't be null" );
		$this->foreignDoctype = $this->foreignDoc->doctype;

		$this->testRangesShort = [ // Various ranges within the text node children of different
			// paragraphs.  All should be valid.
			'[paras[0].firstChild, 0, paras[0].firstChild, 0]',
			'[paras[0].firstChild, 0, paras[0].firstChild, 1]',
			'[paras[0].firstChild, 2, paras[0].firstChild, 8]',
			'[paras[0].firstChild, 2, paras[0].firstChild, 9]',
			'[paras[1].firstChild, 0, paras[1].firstChild, 0]',
			'[paras[1].firstChild, 2, paras[1].firstChild, 9]',
			'[detachedPara1.firstChild, 0, detachedPara1.firstChild, 0]',
			'[detachedPara1.firstChild, 2, detachedPara1.firstChild, 8]',
			'[foreignPara1.firstChild, 0, foreignPara1.firstChild, 0]',
			'[foreignPara1.firstChild, 2, foreignPara1.firstChild, 8]',
			// Now try testing some elements, not just text nodes.
			'[document.documentElement, 0, document.documentElement, 1]',
			'[document.documentElement, 0, document.documentElement, 2]',
			'[document.documentElement, 1, document.documentElement, 2]',
			'[document.head, 1, document.head, 1]',
			'[document.body, 4, document.body, 5]',
			'[foreignDoc.documentElement, 0, foreignDoc.documentElement, 1]',
			'[paras[0], 0, paras[0], 1]',
			'[detachedPara1, 0, detachedPara1, 1]',
			// Now try some ranges that span elements.
			'[paras[0].firstChild, 0, paras[1].firstChild, 0]',
			'[paras[0].firstChild, 0, paras[1].firstChild, 8]',
			'[paras[0].firstChild, 3, paras[3], 1]',
			// How about something that spans a node and its descendant?
			'[paras[0], 0, paras[0].firstChild, 7]',
			'[testDiv, 2, paras[4], 1]',
			// Then a few more interesting things just for good measure.
			'[document, 0, document, 1]',
			'[document, 0, document, 2]',
			'[comment, 2, comment, 3]',
			'[testDiv, 0, comment, 5]',
			'[foreignDoc, 1, foreignComment, 2]',
			'[foreignDoc.body, 0, foreignTextNode, 36]',
			'[xmlDoc, 1, xmlComment, 0]',
			'[detachedTextNode, 0, detachedTextNode, 8]',
			'[detachedForeignTextNode, 0, detachedForeignTextNode, 8]',
			'[detachedXmlTextNode, 0, detachedXmlTextNode, 8]',
			'[detachedComment, 3, detachedComment, 4]',
			'[detachedForeignComment, 0, detachedForeignComment, 1]',
			'[detachedXmlComment, 2, detachedXmlComment, 6]',
			'[docfrag, 0, docfrag, 0]',
			'[processingInstruction, 0, processingInstruction, 4]' ];

		$this->testRanges = array_merge( $this->testRangesShort, [
			'[paras[1].firstChild, 0, paras[1].firstChild, 1]',
			'[paras[1].firstChild, 2, paras[1].firstChild, 8]',
			'[detachedPara1.firstChild, 0, detachedPara1.firstChild, 1]',
			'[foreignPara1.firstChild, 0, foreignPara1.firstChild, 1]',
			'[foreignDoc.head, 1, foreignDoc.head, 1]',
			'[foreignDoc.body, 0, foreignDoc.body, 0]',
			'[paras[0], 0, paras[0], 0]',
			'[detachedPara1, 0, detachedPara1, 0]',
			'[testDiv, 1, paras[2].firstChild, 5]',
			'[document.documentElement, 1, document.body, 0]',
			'[foreignDoc.documentElement, 1, foreignDoc.body, 0]',
			'[document, 1, document, 2]',
			'[paras[2].firstChild, 4, comment, 2]',
			'[paras[3], 1, comment, 8]',
			'[foreignDoc, 0, foreignDoc, 0]',
			'[xmlDoc, 0, xmlDoc, 0]',
			'[detachedForeignTextNode, 7, detachedForeignTextNode, 7]',
			'[detachedXmlTextNode, 7, detachedXmlTextNode, 7]',
			'[detachedComment, 5, detachedComment, 5]',
			'[detachedForeignComment, 4, detachedForeignComment, 4]',
			'[foreignDocfrag, 0, foreignDocfrag, 0]',
			'[xmlDocfrag, 0, xmlDocfrag, 0]',
		] );

		$this->testPoints = [ // Various positions within the page, some invalid.  Remember that
			// paras[0] is visible, and paras[1] is display: none.
			'[paras[0].firstChild, -1]',
			'[paras[0].firstChild, 0]',
			'[paras[0].firstChild, 1]',
			'[paras[0].firstChild, 2]',
			'[paras[0].firstChild, 8]',
			'[paras[0].firstChild, 9]',
			'[paras[0].firstChild, 10]',
			'[paras[0].firstChild, 65535]',
			'[paras[1].firstChild, -1]',
			'[paras[1].firstChild, 0]',
			'[paras[1].firstChild, 1]',
			'[paras[1].firstChild, 2]',
			'[paras[1].firstChild, 8]',
			'[paras[1].firstChild, 9]',
			'[paras[1].firstChild, 10]',
			'[paras[1].firstChild, 65535]',
			'[detachedPara1.firstChild, 0]',
			'[detachedPara1.firstChild, 1]',
			'[detachedPara1.firstChild, 8]',
			'[detachedPara1.firstChild, 9]',
			'[foreignPara1.firstChild, 0]',
			'[foreignPara1.firstChild, 1]',
			'[foreignPara1.firstChild, 8]',
			'[foreignPara1.firstChild, 9]',
			// Now try testing some elements, not just text nodes.
			'[document.documentElement, -1]',
			'[document.documentElement, 0]',
			'[document.documentElement, 1]',
			'[document.documentElement, 2]',
			'[document.documentElement, 7]',
			'[document.head, 1]',
			'[document.body, 3]',
			'[foreignDoc.documentElement, 0]',
			'[foreignDoc.documentElement, 1]',
			'[foreignDoc.head, 0]',
			'[foreignDoc.body, 1]',
			'[paras[0], 0]',
			'[paras[0], 1]',
			'[paras[0], 2]',
			'[paras[1], 0]',
			'[paras[1], 1]',
			'[paras[1], 2]',
			'[detachedPara1, 0]',
			'[detachedPara1, 1]',
			'[testDiv, 0]',
			'[testDiv, 3]',
			// Then a few more interesting things just for good measure.
			'[document, -1]',
			'[document, 0]',
			'[document, 1]',
			'[document, 2]',
			'[document, 3]',
			'[comment, -1]',
			'[comment, 0]',
			'[comment, 4]',
			'[comment, 96]',
			'[foreignDoc, 0]',
			'[foreignDoc, 1]',
			'[foreignComment, 2]',
			'[foreignTextNode, 0]',
			'[foreignTextNode, 36]',
			'[xmlDoc, -1]',
			'[xmlDoc, 0]',
			'[xmlDoc, 1]',
			'[xmlDoc, 5]',
			'[xmlComment, 0]',
			'[xmlComment, 4]',
			'[processingInstruction, 0]',
			'[processingInstruction, 5]',
			'[processingInstruction, 9]',
			'[detachedTextNode, 0]',
			'[detachedTextNode, 8]',
			'[detachedForeignTextNode, 0]',
			'[detachedForeignTextNode, 8]',
			'[detachedXmlTextNode, 0]',
			'[detachedXmlTextNode, 8]',
			'[detachedProcessingInstruction, 12]',
			'[detachedComment, 3]',
			'[detachedComment, 5]',
			'[detachedForeignComment, 0]',
			'[detachedForeignComment, 4]',
			'[detachedXmlComment, 2]',
			'[docfrag, 0]',
			'[foreignDocfrag, 0]',
			'[xmlDocfrag, 0]',
			'[doctype, 0]',
			'[doctype, -17]',
			'[doctype, 1]',
			'[foreignDoctype, 0]',
			'[xmlDoctype, 0]' ];

		$this->testNodesShort = [ 'paras[0]',
			'paras[0].firstChild',
			'paras[1].firstChild',
			'foreignPara1',
			'foreignPara1.firstChild',
			'detachedPara1',
			'detachedPara1.firstChild',
			'document',
			'detachedDiv',
			'foreignDoc',
			'foreignPara2',
			'xmlDoc',
			'xmlElement',
			'detachedTextNode',
			'foreignTextNode',
			'processingInstruction',
			'detachedProcessingInstruction',
			'comment',
			'detachedComment',
			'docfrag',
			'doctype',
			'foreignDoctype' ];

		$this->testNodes = array_merge( $this->testNodesShort,
			[ 'paras[1]',
				'detachedPara2',
				'detachedPara2.firstChild',
				'testDiv',
				'detachedXmlElement',
				'detachedForeignTextNode',
				'xmlTextNode',
				'detachedXmlTextNode',
				'xmlComment',
				'foreignComment',
				'detachedForeignComment',
				'detachedXmlComment',
				'foreignDocfrag',
				'xmlDocfrag',
				'xmlDoctype' ] );
	}

	/**
	 * The "length" of a node as defined by the Ranges section of DOM4.
	 * @param Node $node
	 * @return int
	 */
	public static function nodeLength( $node ) {
		// "The length of a node node depends on node:
		//
		// "DocumentType
		//   "0."
		if ( $node->nodeType == Node::DOCUMENT_TYPE_NODE ) {
			return 0;
		}
		// "Text
		// "ProcessingInstruction
		// "Comment
		//   "Its length attribute value."
		// Browsers don't historically support the length attribute on
		// ProcessingInstruction, so to avoid spurious failures, do
		// node.data.length instead of node.length.
		if ( $node->nodeType == Node::TEXT_NODE ||
			 $node->nodeType == Node::PROCESSING_INSTRUCTION_NODE ||
			 $node->nodeType == Node::COMMENT_NODE ) {
			'@phan-var CharacterData $node';
			return strlen( $node->data );
		}
		// "Any other node
		//   "Its number of children."
		return count( $node->childNodes );
	}

	/**
	 * Returns the furthest ancestor of a Node as defined by the spec.
	 * @param Node $node
	 * @return Node
	 */
	public static function furthestAncestor( $node ) {
		$root = $node;
		while ( true ) {
			$parent = $root->parentNode;
			if ( $parent === null ) {
				break;
			}
			$root = $parent;
		}
		return $root;
	}

	/**
	 * "The ancestor containers of a Node are the Node itself and all its
	 * ancestors."
	 *
	 * Is node1 an ancestor container of node2?
	 * @param Node $node1
	 * @param Node $node2
	 * @return bool
	 */
	public static function isAncestorContainer( $node1, $node2 ) {
		return $node1 === $node2 ||
			( ( $node2->compareDocumentPosition( $node1 ) & Node::DOCUMENT_POSITION_CONTAINS ) !== 0 );
	}

	/**
	 * Returns the first Node that's after node in tree order, or null if node is
	 * the last Node.
	 * @param Node $node
	 * @return ?Node
	 */
	public static function nextNode( $node ) {
		if ( $node->hasChildNodes() ) {
			return $node->firstChild;
		}

		return self::nextNodeDescendants( $node );
	}

	/**
	 * Returns the last Node that's before node in tree order, or null if node is
	 * the first Node.
	 * @param Node $node
	 * @return ?Node
	 */
	public static function previousNode( $node ) {
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
	 * Returns the next Node that's after node and all its descendants in tree
	 * order, or null if node is the last Node or an ancestor of it.
	 * @param Node $node
	 * @return ?Node
	 */
	public static function nextNodeDescendants( $node ) {
		while ( $node && !$node->nextSibling ) {
			$node = $node->parentNode;
		}
		if ( !$node ) {
			return null;
		}

		return $node->nextSibling;
	}

	/**
	 * Returns the ownerDocument of the Node, or the Node itself if it's a
	 * Document.
	 * @param Node $node
	 * @return \Wikimedia\IDLeDOM\Document
	 */
	public static function ownerDocument( $node ) {
		// @phan-suppress-next-line PhanTypeMismatchReturnSuperType
		return ( $node->nodeType == Node::DOCUMENT_NODE ) ? $node : $node->ownerDocument;
	}

	/**
	 * Returns true if ancestor is an ancestor of descendant, false otherwise.
	 * @param ?Node $ancestor
	 * @param ?Node $descendant
	 * @return bool
	 */
	public static function isAncestor( $ancestor, $descendant ) {
		if ( $ancestor === null || $descendant === null ) {
			return false;
		}
		while ( $descendant && $descendant !== $ancestor ) {
			$descendant = $descendant->parentNode;
		}

		return $descendant === $ancestor;
	}

	/**
	 * Returns true if ancestor is an inclusive ancestor of descendant, false
	 * otherwise.
	 * @param ?Node $ancestor
	 * @param ?Node $descendant
	 * @return bool
	 */
	public static function isInclusiveAncestor( $ancestor, $descendant ) {
		return $ancestor === $descendant ||
			self::isAncestor( $ancestor, $descendant );
	}

	/**
	 * Returns true if descendant is a descendant of ancestor, false otherwise.
	 * @param ?Node $descendant
	 * @param ?Node $ancestor
	 * @return bool
	 */
	public static function isDescendant( $descendant, $ancestor ) {
		return self::isAncestor( $ancestor,
			$descendant );
	}

	/**
	 * Returns true if descendant is an inclusive descendant of ancestor, false
	 * otherwise.
	 * @param ?Node $descendant
	 * @param ?Node $ancestor
	 * @return bool
	 */
	public static function isInclusiveDescendant( $descendant, $ancestor ) {
		return $descendant === $ancestor ||
			self::isDescendant( $descendant, $ancestor );
	}

	/**
	 * The position of two boundary points relative to one another, as defined by
	 * the spec.
	 * @param Node $nodeA
	 * @param int $offsetA
	 * @param Node $nodeB
	 * @param int $offsetB
	 * @return string
	 */
	public static function getPosition( $nodeA, $offsetA, $nodeB, $offsetB ) {
		// "If node A is the same as node B, return equal if offset A equals offset
		// B, before if offset A is less than offset B, and after if offset A is
		// greater than offset B."
		if ( $nodeA === $nodeB ) {
			if ( $offsetA === $offsetB ) {
				return 'equal';
			}
			if ( $offsetA < $offsetB ) {
				return 'before';
			}
			if ( $offsetA > $offsetB ) {
				return 'after';
			}
		}

		// "If node A is after node B in tree order, compute the position of (node
		// B, offset B) relative to (node A, offset A). If it is before, return
		// after. If it is after, return before."
		if ( ( $nodeB->compareDocumentPosition( $nodeA ) & Node::DOCUMENT_POSITION_FOLLOWING ) !== 0 ) {
			$pos = self::getPosition( $nodeB, $offsetB, $nodeA, $offsetA );
			if ( $pos === 'before' ) {
				return 'after';
			}
			if ( $pos === 'after' ) {
				return 'before';
			}
		}

		// "If node A is an ancestor of node B:"
		if ( ( $nodeB->compareDocumentPosition( $nodeA ) & Node::DOCUMENT_POSITION_CONTAINS ) !== 0 ) {
			// "Let child equal node B."
			$child = $nodeB;

			// "While child is not a child of node A, set child to its parent."
			while ( $child->parentNode !== $nodeA ) {
				$child = $child->parentNode;
			}

			// "If the index of child is less than offset A, return after."
			'@phan-var Node $child'; // @var Node $child
			if ( self::indexOf( $child ) < $offsetA ) {
				return 'after';
			}
		}

		// "Return before."
		return 'before';
	}

	/**
	 * "Contained" as defined by DOM Range: "A Node node is contained in a range
	 * range if node's furthest ancestor is the same as range's root, and (node, 0)
	 * is after range's start, and (node, length of node) is before range's end."
	 * @param Node $node
	 * @param Range $range
	 * @return bool
	 */
	public static function isContained( $node, $range ) {
		$pos1 = self::getPosition(
			$node,
			0,
			$range->startContainer,
			$range->startOffset
		);
		$pos2 = self::getPosition(
			$node,
			self::nodeLength( $node ),
			$range->endContainer,
			$range->endOffset
		);

		return self::furthestAncestor( $node ) === self::furthestAncestor( $range->startContainer ) &&
			$pos1 === 'after' &&
			$pos2 === 'before';
	}

	/**
	 * "Partially contained" as defined by DOM Range: "A Node is partially
	 * contained in a range if it is an ancestor container of the range's start but
	 * not its end, or vice versa."
	 * @param Node $node
	 * @param Range $range
	 * @return bool
	 */
	public static function isPartiallyContained( $node, $range ) {
		$cond1 = self::isAncestorContainer(
			$node,
			$range->startContainer
		);
		$cond2 = self::isAncestorContainer(
			$node,
			$range->endContainer
		);

		return ( $cond1 && !$cond2 ) || ( $cond2 && !$cond1 );
	}

	/**
	 * Index of a node as defined by the spec.
	 * @param Node $node
	 * @return int
	 */
	public static function indexOf( $node ) {
		if ( $node->parentNode === null ) {
			// No preceding sibling nodes, right?
			return 0;
		}
		$i = 0;
		while ( $node !== $node->parentNode->childNodes[$i] ) {
			$i++;
		}

		return $i;
	}

	/**
	 * extractContents() implementation, following the spec.  If an exception is
	 * supposed to be thrown, will return a string with the name (e.g.,
	 * "HIERARCHY_REQUEST_ERR") instead of a document fragment.  It might also
	 * return an arbitrary human-readable string if a condition is hit that implies
	 * a spec bug.
	 * @param Range $range
	 * @return DocumentFragment|string
	 */
	public function myExtractContents( $range ) {
		// "Let frag be a new DocumentFragment whose ownerDocument is the same as
		// the ownerDocument of the context object's start node."
		$ownerDoc =
			( $range->startContainer->nodeType == Node::DOCUMENT_NODE ) ?
			$range->startContainer : $range->startContainer->ownerDocument;
		$frag = $ownerDoc->createDocumentFragment();

		// "If the context object's start and end are the same, abort this method,
		// returning frag."
		if ( $range->startContainer == $range->endContainer && $range->startOffset == $range->endOffset ) {
			return $frag;
		}

		// "Let original start node, original start offset, original end node, and
		// original end offset be the context object's start and end nodes and
		// offsets, respectively."
		$originalStartNode = $range->startContainer;
		$originalStartOffset = $range->startOffset;
		$originalEndNode = $range->endContainer;
		$originalEndOffset = $range->endOffset;

		// "If original start node is original end node, and they are a Text,
		// ProcessingInstruction, or Comment node:"
		if (
			$range->startContainer === $range->endContainer &&
			(
				$range->startContainer->nodeType === Node::TEXT_NODE ||
				$range->startContainer->nodeType === Node::PROCESSING_INSTRUCTION_NODE ||
				$range->startContainer->nodeType === Node::COMMENT_NODE
			)
		) {
			'@phan-var CharacterData $originalStartNode';
			// "Let clone be the result of calling cloneNode(false) on original
			// start node."
			$clone = $originalStartNode->cloneNode( false );
			'@phan-var CharacterData $clone';

			// "Set the data of clone to the result of calling
			// substringData(original start offset, original end offset − original
			// start offset) on original start node."
			$clone->data = $originalStartNode->substringData( $originalStartOffset,
				$originalEndOffset - $originalStartOffset );

			// "Append clone as the last child of frag."
			$frag->appendChild( $clone );

			// "Call deleteData(original start offset, original end offset −
			// original start offset) on original start node."
			$originalStartNode->deleteData( $originalStartOffset,
				$originalEndOffset - $originalStartOffset );

			// "Abort this method, returning frag."
			return $frag;
		}

		// "Let common ancestor equal original start node."
		$commonAncestor = $originalStartNode;

		// "While common ancestor is not an ancestor container of original end
		// node, set common ancestor to its own parent."
		while ( !self::isAncestorContainer( $commonAncestor,
			$originalEndNode ) ) {
			$commonAncestor = $commonAncestor->parentNode;
		}

		// "If original start node is an ancestor container of original end node,
		// let first partially contained child be null."
		$firstPartiallyContainedChild = null;
		if ( self::isAncestorContainer( $originalStartNode,
			$originalEndNode ) ) {
			// "Otherwise, let first partially contained child be the first child of
			// common ancestor that is partially contained in the context object."
		} else {
			for ( $i = 0, $iMax = count( $commonAncestor->childNodes ); $i < $iMax; $i++ ) {
				if ( self::isPartiallyContained( $commonAncestor->childNodes[$i],
					$range ) ) {
					$firstPartiallyContainedChild = $commonAncestor->childNodes[$i];
					break;
				}
			}
			if ( !$firstPartiallyContainedChild ) {
				throw new Exception( 'Spec bug: no first partially contained child!' );
			}
		}

		// "If original end node is an ancestor container of original start node,
		// let last partially contained child be null."
		$lastPartiallyContainedChild = null;
		if ( self::isAncestorContainer( $originalEndNode,
			$originalStartNode ) ) {
			// "Otherwise, let last partially contained child be the last child of
			// common ancestor that is partially contained in the context object."
		} else {
			for ( $i = count( $commonAncestor->childNodes ) - 1; $i >= 0; $i-- ) {
				if ( self::isPartiallyContained( $commonAncestor->childNodes[$i],
					$range ) ) {
					$lastPartiallyContainedChild = $commonAncestor->childNodes[$i];
					break;
				}
			}
			if ( !$lastPartiallyContainedChild ) {
				throw new Exception( 'Spec bug: no last partially contained child!' );
			}
		}

		// "Let contained children be a list of all children of common ancestor
		// that are contained in the context object, in tree order."
		//
		// "If any member of contained children is a DocumentType, raise a
		// HIERARCHY_REQUEST_ERR exception and abort these steps."
		$containedChildren = [];
		for ( $i = 0, $iMax = count( $commonAncestor->childNodes ); $i < $iMax; $i++ ) {
			if ( self::isContained( $commonAncestor->childNodes[$i],
				$range ) ) {
				if ( $commonAncestor->childNodes[$i]->nodeType === Node::DOCUMENT_TYPE_NODE ) {
					return 'HIERARCHY_REQUEST_ERR';
				}
				$containedChildren[] = $commonAncestor->childNodes[$i];
			}
		}

		// "If original start node is an ancestor container of original end node,
		// set new node to original start node and new offset to original start
		// offset."
		$newNode = null;
		$newOffset = null;
		if ( self::isAncestorContainer( $originalStartNode,
			$originalEndNode ) ) {
			$newNode = $originalStartNode;
			$newOffset = $originalStartOffset;
			// "Otherwise:"
		} else {
			// "Let reference node equal original start node."
			$referenceNode = $originalStartNode;

			// "While reference node's parent is not null and is not an ancestor
			// container of original end node, set reference node to its parent."
			while (
				$referenceNode->parentNode !== null &&
				!self::isAncestorContainer(
					$referenceNode->parentNode,
					$originalEndNode
				)
			) {
				$referenceNode = $referenceNode->parentNode;
			}
			'@phan-var Node $referenceNode';

			// "Set new node to the parent of reference node, and new offset to one
			// plus the index of reference node."
			$newNode = $referenceNode->parentNode;
			$newOffset = 1 + self::indexOf( $referenceNode );
		}

		// "If first partially contained child is a Text, ProcessingInstruction, or
		// Comment node:"
		if (
			$firstPartiallyContainedChild &&
			(
				$firstPartiallyContainedChild->nodeType === Node::TEXT_NODE ||
				$firstPartiallyContainedChild->nodeType === Node::PROCESSING_INSTRUCTION_NODE ||
				$firstPartiallyContainedChild->nodeType === Node::COMMENT_NODE
			)
		) {
			'@phan-var CharacterData $originalStartNode';
			// "Let clone be the result of calling cloneNode(false) on original
			// start node."
			$clone = $originalStartNode->cloneNode( false );
			'@phan-var CharacterData $clone';

			// "Set the data of clone to the result of calling substringData() on
			// original start node, with original start offset as the first
			// argument and (length of original start node − original start offset)
			// as the second."
			$clone->data = $originalStartNode->substringData(
				$originalStartOffset,
				self::nodeLength( $originalStartNode ) - $originalStartOffset
			);

			// "Append clone as the last child of frag."
			$frag->appendChild( $clone );

			// "Call deleteData() on original start node, with original start
			// offset as the first argument and (length of original start node −
			// original start offset) as the second."
			$originalStartNode->deleteData(
				$originalStartOffset,
				self::nodeLength( $originalStartNode ) - $originalStartOffset
			);
			// "Otherwise, if first partially contained child is not null:"
		} else {
			if ( $firstPartiallyContainedChild ) {
				// "Let clone be the result of calling cloneNode(false) on first
				// partially contained child."
				$clone = $firstPartiallyContainedChild->cloneNode( false );

				// "Append clone as the last child of frag."
				$frag->appendChild( $clone );

				// "Let subrange be a new Range whose start is (original start node,
				// original start offset) and whose end is (first partially contained
				// child, length of first partially contained child)."
				$subrange = $ownerDoc->createRange();
				$subrange->setStart(
					$originalStartNode,
					$originalStartOffset
				);
				$subrange->setEnd(
					$firstPartiallyContainedChild,
					self::nodeLength( $firstPartiallyContainedChild )
				);

				// "Let subfrag be the result of calling extractContents() on
				// subrange."
				$subfrag = self::myExtractContents( $subrange );

				// "For each child of subfrag, in order, append that child to clone as
				// its last child."
				for ( $i = 0, $iMax = count( $subfrag->childNodes ); $i < $iMax; $i++ ) {
					$clone->appendChild( $subfrag->childNodes[$i] );
				}
			}
		}

		// "For each contained child in contained children, append contained child
		// as the last child of frag."
		for ( $i = 0, $iMax = count( $containedChildren ); $i < $iMax; $i++ ) {
			$frag->appendChild( $containedChildren[$i] );
		}

		// "If last partially contained child is a Text, ProcessingInstruction, or
		// Comment node:"
		if (
			$lastPartiallyContainedChild &&
			(
				$lastPartiallyContainedChild->nodeType === Node::TEXT_NODE ||
				$lastPartiallyContainedChild->nodeType === Node::PROCESSING_INSTRUCTION_NODE ||
				$lastPartiallyContainedChild->nodeType === Node::COMMENT_NODE
			)
		) {
			'@phan-var CharacterData $originalEndNode';
			// "Let clone be the result of calling cloneNode(false) on original
			// end node."
			$clone = $originalEndNode->cloneNode( false );
			'@phan-var CharacterData $clone';

			// "Set the data of clone to the result of calling substringData(0,
			// original end offset) on original end node."
			$clone->data = $originalEndNode->substringData(
				0,
				$originalEndOffset
			);

			// "Append clone as the last child of frag."
			$frag->appendChild( $clone );

			// "Call deleteData(0, original end offset) on original end node."
			$originalEndNode->deleteData(
				0,
				$originalEndOffset
			);
			// "Otherwise, if last partially contained child is not null:"
		} else {
			if ( $lastPartiallyContainedChild ) {
				// "Let clone be the result of calling cloneNode(false) on last
				// partially contained child."
				$clone = $lastPartiallyContainedChild->cloneNode( false );

				// "Append clone as the last child of frag."
				$frag->appendChild( $clone );

				// "Let subrange be a new Range whose start is (last partially
				// contained child, 0) and whose end is (original end node, original
				// end offset)."
				$subrange = $ownerDoc->createRange();
				$subrange->setStart(
					$lastPartiallyContainedChild,
					0
				);
				$subrange->setEnd(
					$originalEndNode,
					$originalEndOffset
				);

				// "Let subfrag be the result of calling extractContents() on
				// subrange."
				$subfrag = self::myExtractContents( $subrange );
				if ( is_string( $subfrag ) ) {
					throw new Exception( "should never happen" );
				}

				// "For each child of subfrag, in order, append that child to clone as
				// its last child."
				for ( $i = 0, $iMax = count( $subfrag->childNodes ); $i < $iMax; $i++ ) {
					$clone->appendChild( $subfrag->childNodes[$i] );
				}
			}
		}

		// "Set the context object's start and end to (new node, new offset)."
		'@phan-var Node $newNode';
		$range->setStart( $newNode, $newOffset );
		$range->setEnd( $newNode, $newOffset );

		// "Return frag."
		return $frag;
	}

	/**
	 * insertNode() implementation, following the spec.  If an exception is meant
	 * to be thrown, will return a string with the expected exception name, for
	 * instance "HIERARCHY_REQUEST_ERR".
	 * @param Range $range
	 * @param Node $node
	 * @return ?string
	 */
	public static function myInsertNode( $range, $node ) {
		// "If range's start node is a ProcessingInstruction or Comment node, or is
		// a Text node whose parent is null, or is node, throw an
		// "HierarchyRequestError" exception and terminate these steps."
		if (
			$range->startContainer->nodeType === Node::PROCESSING_INSTRUCTION_NODE ||
			$range->startContainer->nodeType === Node::COMMENT_NODE ||
			(
				$range->startContainer->nodeType === Node::TEXT_NODE &&
				$range->startContainer->parentNode === null
			) ||
			$range->startContainer === $node
		) {
			return 'HIERARCHY_REQUEST_ERR';
		}

		// "Let referenceNode be null."
		$referenceNode = null;

		// "If range's start node is a Text node, set referenceNode to that Text node."
		if ( $range->startContainer->nodeType === Node::TEXT_NODE ) {
			$referenceNode = $range->startContainer;

			// "Otherwise, set referenceNode to the child of start node whose index is
			// start offset, and null if there is no such child."
		} else {
			if ( $range->startOffset < count( $range->startContainer->childNodes ) ) {
				$referenceNode = $range->startContainer->childNodes[$range->startOffset];
			}
		}

		// "Let parent be range's start node if referenceNode is null, and
		// referenceNode's parent otherwise."
		$parent_ = ( $referenceNode === null ) ? $range->startContainer : $referenceNode->parentNode;

		// "Ensure pre-insertion validity of node into parent before
		// referenceNode."
		$error = self::ensurePreInsertionValidity(
			$node,
			$parent_,
			$referenceNode
		);
		if ( $error ) {
			return $error;
		}

		// "If range's start node is a Text node, set referenceNode to the result
		// of splitting it with offset range's start offset."
		if ( $range->startContainer->nodeType === Node::TEXT_NODE ) {
			$s = $range->startContainer;
'@phan-var Text $s';
			$referenceNode = $s->splitText( $range->startOffset );
		}

		// "If node is referenceNode, set referenceNode to its next sibling."
		if ( $node === $referenceNode ) {
			$referenceNode = $referenceNode->nextSibling;
		}

		// "If node's parent is not null, remove node from its parent."
		if ( $node->parentNode ) {
			$node->parentNode->removeChild( $node );
		}

		// "Let newOffset be parent's length if referenceNode is null, and
		// referenceNode's index otherwise."
		$newOffset = ( $referenceNode === null ) ? self::nodeLength( $parent_ ) : self::indexOf( $referenceNode );

		// "Increase newOffset by node's length if node is a DocumentFragment node,
		// and one otherwise."
		$newOffset += ( $node->nodeType === Node::DOCUMENT_FRAGMENT_NODE ) ? self::nodeLength( $node ) : 1;

		// "Pre-insert node into parent before referenceNode."
		$parent_->insertBefore( $node, $referenceNode );

		// "If range's start and end are the same, set range's end to (parent,
		// newOffset)."
		if ( $range->startContainer == $range->endContainer && $range->startOffset == $range->endOffset ) {
			$range->setEnd( $parent_, $newOffset );
		}

		return null; // no error
	}

	// To make filter() calls more readable

	/**
	 * @param Node $node
	 * @return bool
	 */
	public static function isElement( $node ) {
		return $node->nodeType == Node::ELEMENT_NODE;
	}

	/**
	 * @param Node $node
	 * @return bool
	 */
	public static function isText( $node ) {
		return $node->nodeType == Node::TEXT_NODE;
	}

	/**
	 * @param Node $node
	 * @return bool
	 */
	public static function isDoctype( $node ) {
		return $node->nodeType == Node::DOCUMENT_TYPE_NODE;
	}

	/**
	 * @param Node $node
	 * @param Node $parent
	 * @param ?Node $child
	 * @return string
	 */
	public static function ensurePreInsertionValidity( $node, $parent, $child ) {
		// "If parent is not a Document, DocumentFragment, or Element node, throw a
		// HierarchyRequestError."
		if (
			$parent->nodeType !== Node::DOCUMENT_NODE &&
			$parent->nodeType !== Node::DOCUMENT_FRAGMENT_NODE &&
			$parent->nodeType !== Node::ELEMENT_NODE
		) {
			return 'HIERARCHY_REQUEST_ERR';
		}

		// "If node is a host-including inclusive ancestor of parent, throw a
		// HierarchyRequestError."
		//
		// XXX Does not account for host
		if ( self::isInclusiveAncestor( $node, $parent ) ) {
			return 'HIERARCHY_REQUEST_ERR';
		}

		// "If child is not null and its parent is not parent, throw a NotFoundError
		// exception."
		if ( $child && $child->parentNode !== $parent ) {
			return 'NOT_FOUND_ERR';
		}

		// "If node is not a DocumentFragment, DocumentType, Element, Text,
		// ProcessingInstruction, or Comment node, throw a HierarchyRequestError."
		if (
			$node->nodeType !== Node::DOCUMENT_FRAGMENT_NODE &&
			$node->nodeType !== Node::DOCUMENT_TYPE_NODE &&
			$node->nodeType !== Node::ELEMENT_NODE &&
			$node->nodeType !== Node::TEXT_NODE &&
			$node->nodeType !== Node::PROCESSING_INSTRUCTION_NODE &&
			$node->nodeType !== Node::COMMENT_NODE
		) {
			return 'HIERARCHY_REQUEST_ERR';
		}

		// "If either node is a Text node and parent is a document, or node is a
		// doctype and parent is not a document, throw a HierarchyRequestError."
		if (
			(
				$node->nodeType === Node::TEXT_NODE &&
				$parent->nodeType === Node::DOCUMENT_NODE
			) || (
				$node->nodeType === Node::DOCUMENT_TYPE_NODE &&
				$parent->nodeType !== Node::DOCUMENT_NODE
			)
		) {
			return 'HIERARCHY_REQUEST_ERR';
		}

		// "If parent is a document, and any of the statements below, switched on
		// node, are true, throw a HierarchyRequestError."
		if ( $parent->nodeType === Node::DOCUMENT_NODE ) {
			switch ( $node->nodeType ) {
				case Node::DOCUMENT_FRAGMENT_NODE:
					// "If node has more than one element child or has a Text node
					// child.  Otherwise, if node has one element child and either
					// parent has an element child, child is a doctype, or child is not
					// null and a doctype is following child."
					$numElements = 0;
					foreach ( $node->childNodes as $c ) {
						if ( self::isElement( $c ) ) {
							$numElements++;
						}
						if ( self::isText( $c ) ) {
							return 'HIERARCHY_REQUEST_ERR';
						}
					}
					if ( $numElements > 1 ) {
						return 'HIERARCHY_REQUEST_ERR';
					}

					if ( $numElements === 1 ) {
						foreach ( $parent->childNodes as $c ) {
							if ( self::isElement( $c ) ) {
								return 'HIERARCHY_REQUEST_ERR';
							}
						}

						if ( $child !== null ) {
							if ( $child->nodeType === Node::DOCUMENT_TYPE_NODE ) {
								return 'HIERARCHY_REQUEST_ERR';
							}

							for ( $i = self::indexOf( $child ) + 1; $i < count( $parent->childNodes ); $i++ ) {
								if ( self::isDocType( $parent->childNodes[$i] ) ) {
									return 'HIERARCHY_REQUEST_ERR';
								}
							}
						}
					}
					break;

				case Node::ELEMENT_NODE:
					// "parent has an element child, child is a doctype, or child is
					// not null and a doctype is following child."
					foreach ( $parent->childNodes as $c ) {
						if ( self::isElement( $c ) ) {
							return 'HIERARCHY_REQUEST_ERR';
						}
					}

					if ( $child !== null ) {
						if ( $child->nodeType == Node::DOCUMENT_TYPE_NODE ) {
							return 'HIERARCHY_REQUEST_ERR';
						}

						for ( $i = self::indexOf( $child ) + 1; $i < count( $parent->childNodes ); $i++ ) {
							if ( self::isDocType( $parent->childNodes[$i] ) ) {
								return 'HIERARCHY_REQUEST_ERR';
							}
						}
					}
					break;

				case Node::DOCUMENT_TYPE_NODE:
					// "parent has a doctype child, an element is preceding child, or
					// child is null and parent has an element child."
					foreach ( $parent->childNodes as $c ) {
						if ( self::isDoctype( $c ) ) {
							return 'HIERARCHY_REQUEST_ERR';
						}
					}
					if ( $child !== null ) {
						for ( $i = self::indexOf( $child ) - 1; $i >= 0; $i-- ) {
							if ( self::isElement( $parent->childNodes[$i] ) ) {
								return 'HIERARCHY_REQUEST_ERR';
							}
						}
					} else {
						foreach ( $parent->childNodes as $c ) {
							if ( self::isElement( $c ) ) {
								return 'HIERARCHY_REQUEST_ERR';
							}
						}
					}
					break;
			}
		}
	}

	/**
	 * Asserts that two nodes are equal, in the sense of isEqualNode().  If they
	 * aren't, tries to print a relatively informative reason why not.
	 * @param Node $actual
	 * @param Node $expected
	 * @param string $msg
	 */
	public static function assertNodesEqual( $actual, $expected, $msg ) {
		if ( !$actual->isEqualNode( $expected ) ) {
			$msg = 'Actual and expected mismatch for ' . $msg . '.  ';

			while ( $actual && $expected ) {
				Assert::assertTrue(
					$actual->nodeType === $expected->nodeType &&
					$actual->nodeName === $expected->nodeName &&
					$actual->nodeValue === $expected->nodeValue,
					'First differing node: expected ' .
					# $this->formatValue( $expected ) . // NYI
					', got ' .
					# $this->formatValue( $actual ) . // NYI
					' [' . $msg . ']' );
				$actual = self::nextNode( $actual );
				$expected = self::nextNode( $expected );
			}
			// This should be unreachable!
			throw new Exception(
				"DOMs were not equal but we couldn't figure out why"
			);
		}
	}

	/**
	 * Given a DOMException, return the name (e.g., "HIERARCHY_REQUEST_ERR").
	 * @param DOMException $e
	 * @return string
	 */
	public static function getDomExceptionName( $e ) {
		$reflect = new ReflectionClass( DOMException::class );
		foreach ( $reflect->getConstants() as $prop => $num ) {
			if (
				preg_match( '/^[A-Z_]+_ERR$/', $prop ) &&
				$num == $e->code
			) {
				return $prop;
			}
		}

		throw new Exception(
			'Exception seems to not be a DOMException?'
		);
	}

	/**
	 * Given an array of endpoint data [start container, start offset, end
	 * container, end offset], returns a Range with those endpoints.
	 * @param array $endpoints
	 * @return Range
	 */
	public static function rangeFromEndpoints( $endpoints ) {
		// If we just use document instead of the ownerDocument of endpoints[0],
		// WebKit will throw on setStart/setEnd.  This is a WebKit bug, but it's in
		// range, not selection, so we don't want to fail anything for it.
		$range = self::ownerDocument( $endpoints[0] )->createRange();
		$range->setStart( $endpoints[0], $endpoints[1] );
		$range->setEnd( $endpoints[2], $endpoints[3] );
		return $range;
	}

}
