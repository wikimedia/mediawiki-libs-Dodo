<?php

declare( strict_types = 1 );
// @phan-file-suppress PhanCoalescingNeverUndefined
// @phan-file-suppress PhanParamSignatureMismatch
// @phan-file-suppress PhanTypeMismatchArgument
// @phan-file-suppress PhanTypeMismatchReturn
// @phan-file-suppress PhanUndeclaredMethod
// @phan-file-suppress PhanUndeclaredProperty
// @phan-file-suppress PhanUndeclaredTypeThrowsType
// phpcs:disable Generic.NamingConventions.CamelCapsFunctionName.MethodDoubleUnderscore
// phpcs:disable Generic.NamingConventions.CamelCapsFunctionName.ScopeNotCamelCaps
// phpcs:disable MediaWiki.Commenting.FunctionComment.MissingDocumentationPublic
// phpcs:disable MediaWiki.Commenting.FunctionComment.WrongStyle
// phpcs:disable MediaWiki.Commenting.PropertyDocumentation.MissingDocumentationPublic
// phpcs:disable MediaWiki.Commenting.PropertyDocumentation.WrongStyle

namespace Wikimedia\Dodo;

use Wikimedia\Dodo\Internal\UnimplementedTrait;
use Wikimedia\Dodo\Internal\Util;
use Wikimedia\Dodo\Internal\WhatWG;
use Wikimedia\IDLeDOM\Node as INode;

/**
 * Node.php
 * --------
 * Defines a "Node", the primary datatype of the W3C Document Object Model.
 *
 * Conforms to W3C Document Object Model (DOM) Level 1 Recommendation
 * (see: https://www.w3.org/TR/2000/WD-DOM-Level-1-20000929)
 */
abstract class Node extends EventTarget implements \Wikimedia\IDLeDOM\Node {
	// Stub out methods not yet implemented.
	use \Wikimedia\IDLeDOM\Stub\Node;
	use UnimplementedTrait;

	// Helper functions from IDLeDOM
	use \Wikimedia\IDLeDOM\Helper\Node;

	/**********************************************************************
	 * Abstract methods that must be defined in subclasses
	 */

	/**
	 * Delegated subclass method called by Node::isEqualNode()
	 * @param Node $node
	 * @return bool
	 */
	abstract protected function _subclass_isEqualNode( Node $node ): bool;

	/**
	 * Delegated subclass method called by Node::cloneNode()
	 * @return ?Node
	 */
	abstract protected function _subclass_cloneNodeShallow(): ?Node;

	/**********************************************************************
	 * Properties that appear in DOM-LS
	 */

	/*
	 * SET WHEN SOMETHING APPENDS NODE
	 *
	 * @var Node|null should be considered read-only
	 */
	public $_ownerDocument;

	/**
	 * @see $_ownerDocument
	 *
	 * @var Node|null should be considered read-only
	 */
	public $_parentNode;

	/**
	 * DEVIATION FROM SPEC
	 * PURPOSE: SIBLING TRAVERSAL OPTIMIZATION
	 *
	 * If a Node has no siblings, i.e. it is the 'only child' of $_parentNode, then the
	 * properties $_nextSibling and $_previousSibling are set equal to $this.
	 *
	 * This is an optimization for traversing siblings, but in DOM-LS, these properties
	 * should be null in this scenario.
	 *
	 * The relevant accessors are spec-compliant, returning null in this situation.
	 *
	 * @var Node|null should be considered read-only
	 */
	public $_nextSibling;

	/**
	 * @see $_nextSibling
	 * @var Node|null should be considered read-only
	 */
	public $_previousSibling;

	/**
	 * SET WHEN NODE APPENDS SOMETHING
	 *
	 * @var Node|null should be considered read-only
	 */
	public $_firstChild;

	/*
	 * DEVIATION FROM SPEC
	 * PURPOSE: APPEND OPTIMIZATION
	 *
	 * The $_childNodes property holds an array-like object (a NodeList) referencing
	 * each of a Node's children as a live representation of the DOM.
	 *
	 * This 'liveness' is somewhat unperformant, and the upkeep of this object has
	 * a significant impact on append performance.
	 *
	 * So, this implementation chooses to defer its construction until a value
	 * is requested by calling Node::childNodes().
	 *
	 * Until that time, it will have the value null.
	 *
	 * TODO this should have {at}var with the next line, but that breaks phan
	 * because even though NodeList extends ArrayObject, it can't be used in array_splice?
	 *
	 * NodeList|null should be considered read-only
	 */
	public $_childNodes;

	/**********************************************************************
	 * Properties that are for internal use by this library
	 */

	/*
	 * DEVELOPERS NOTE:
	 * An index is assigned on ADOPTION. It uniquely identifies the Node
	 * within its owner Document.
	 *
	 * This index makes it simple to represent a Node as an integer.
	 *
	 * It exists for a single optimization. If two Elements have the same id,
	 * they will be stored in an array under their $document_index. This
	 * means we don't have to search the array for a matching Node, we can
	 * look it up in O(1). Yep.
	 *
	 * FIXME It is public because it gets used by the whatwg algorithms page.
	 */
	public $_documentIndex;

	/*
	 * DEVELOPERS NOTE:
	 * An index is assigned on INSERTION. It uniquely identifies the Node among
	 * its siblings.
	 *
	 * It is used to help compute document position and to mark where insertion should
	 * occur.
	 *
	 * Its existence is, frankly, mostly for convenience due to the fact that the most
	 * common representation of child nodes is a linked list that doesn't have numeric
	 * indices otherwise.
	 *
	 * FIXME It is public because it gets used by the whatwg algorithms page.
	 */
	public $_cachedSiblingIndex;

	/* TODO: Unused */
	public $__roothook;

	public function __construct() {
		/* Our ancestors */
		$this->_ownerDocument = null;
		$this->_parentNode = null;

		/* Our children */
		$this->_firstChild = null;
		$this->_childNodes = null;

		/* Our siblings */
		$this->_nextSibling = $this; // for LL
		$this->_previousSibling = $this; // for LL
	}

	/**********************************************************************
	 * ACCESSORS
	 */

	/*
	 * Sometimes, subclasses will override
	 * nodeValue and textContent, so these
	 * accessors should be seen as "defaults,"
	 * which in some cases are extended.
	 */

	/**
	 * Return the node type enumeration for this node.
	 * @see https://dom.spec.whatwg.org/#dom-node-nodetype
	 * @return int
	 */
	abstract public function getNodeType(): int;

	/**
	 * Return the `nodeName` for this node.
	 * @see https://dom.spec.whatwg.org/#dom-node-nodename
	 * @return string
	 */
	abstract public function getNodeName(): string;

	/**
	 * Return the `value` for this node.
	 * @see https://dom.spec.whatwg.org/#dom-node-nodevalue
	 * @return ?string
	 */
	public function getNodeValue() : ?string {
		return null; // Override in subclasses
	}

	/** @inheritDoc */
	public function setNodeValue( ?string $val ) : void {
		/* Any other node: Do nothing */
	}

	/**
	 * Return the `textContent` for this node.
	 * @see https://dom.spec.whatwg.org/#dom-node-textcontent
	 * @return ?string
	 */
	public function getTextContent() : ?string {
		return null; // Override in subclasses
	}

	/** @inheritDoc */
	public function setTextContent( ?string $val ) : void {
		/* Any other node: Do nothing */
	}

	/**
	 * Nodes might not have an ownerDocument. Perhaps they have not been inserted
	 * into a DOM, or are themselves a Document. In those cases, the value of
	 * ownerDocument will be null.
	 *
	 * @inheritDoc
	 */
	final public function getOwnerDocument() {
		return $this->_ownerDocument;
	}

	/**
	 * Nodes might not have a parentNode. Perhaps they have not been inserted
	 * into a DOM, or are a Document node, which is the root of a DOM tree and
	 * thus has no parent. In those cases, the value of parentNode is null.
	 *
	 * @inheritDoc
	 */
	final public function getParentNode() {
		return $this->_parentNode;
	}

	/**
	 * This value is the same as parentNode, except it puts an extra condition,
	 * that the parentNode must be an Element.
	 *
	 * Accordingly, it requires no additional backing property, and can exist only
	 * as an accessor.
	 *
	 * @inheritDoc
	 */
	final public function getParentElement() {
		if ( $this->_parentNode === null ) {
			return null;
		}
		if ( $this->_parentNode->getNodeType() === self::ELEMENT_NODE ) {
			return $this->_parentNode;
		}
		return null;
	}

	/** @inheritDoc */
	final public function getPreviousSibling() {
		if ( $this->_parentNode === null ) {
			return null;
		}
		if ( $this->_parentNode->_firstChild === $this ) {
			/*
			 * TODO: Why not check $this->_nextSibling === $this
			 *
			 * Is it because firstChild will be set to null if we should be using
			 * NodeList???
			 */
			return null;
		}
		return $this->_previousSibling;
	}

	/** @inheritDoc */
	final public function getNextSibling() {
		if ( $this->_parentNode === null ) {
			return null;
		}
		if ( $this->_nextSibling === $this->_parentNode->_firstChild ) {
			/*
			 * TODO: Why not check $this->_nextSibling === $this
			 *
			 * Is it because firstChild will be set to null if we should be using
			 * NodeList???
			 */
			return null;
		}
		return $this->_nextSibling;
	}

	/**
	 * When, in other place of the code, you observe folks testing for
	 * $this->_childNodes, it is to see whether we should use the NodeList
	 * or the linked list traversal methods.
	 *
	 * FIXME:
	 * Wait, doesn't this need to be live? I mean, don't we need to re-compute
	 * this thing when things are appended or removed...? Or is it not live?
	 *
	 * @inheritDoc
	 */
	public function getChildNodes() {
		if ( $this->_childNodes === null ) {

			/*
			 * If childNodes has never been created, we've now created it.
			 */
			$this->_childNodes = new NodeList();

			for ( $c = $this->getFirstChild(); $c !== null; $c = $c->getNextSibling() ) {
				$this->_childNodes[] = $c;
			}

			/*
			 * TODO: Must we?
			 * Setting this to null is a signal that we are not to use the Linked List, but
			 * it is stupid and I think we don't actually need it.
			 */
			$this->_firstChild = null;
		}
		return $this->_childNodes;
	}

	/**
	 * CAUTION
	 * Directly accessing _firstChild alone is *not* a shortcut for this
	 * method. Depending on whether we are in NodeList or LinkedList mode, one
	 * or the other or both may be null.
	 *
	 * I'm trying to factor it out, but it will take some time.
	 *
	 * @inheritDoc
	 */
	public function getFirstChild() {
		if ( $this->_childNodes === null ) {
			/*
			 * If we are using the Linked List representation, then just return
			 * the backing property (may still be null).
			 */
			return $this->_firstChild;
		}
		if ( isset( $this->_childNodes[0] ) ) {
			/*
			 * If we are using the NodeList representation, and the
			 * NodeList is not empty, then return the first item in the
			 * NodeList.
			 */
			return $this->_childNodes[0];
		}
		/* Otherwise, the NodeList is empty, so return null. */
		return null;
	}

	/**
	 * @inheritDoc
	 */
	public function getLastChild() {
		if ( $this->_childNodes === null ) {
			/* If we are using the Linked List representation. */
			if ( $this->_firstChild !== null ) {
				/* If we have a firstChild, its previousSibling is the last child. */
				return $this->_firstChild->getPreviousSibling();
			} else {
				/* Otherwise there are no children, and so last child is null. */
				return null;
			}
		} else {
			/* If we are using the NodeList representation. */
			if ( isset( $this->_childNodes[0] ) ) {
				/*
				 * If there is at least one element in the NodeList, return the
				 * last element in the NodeList.
				 */
				return end( $this->_childNodes );
			} else {
				/* Otherwise, there are no children, and so last child is null. */
				return null;
			}
		}
	}

	/**
	 * CAUTION
	 * Testing _firstChild or _childNodes alone is *not* a shortcut for this
	 * method. Depending on whether we are in NodeList or LinkedList mode, one
	 * or the other or both may be null.
	 *
	 * I'm trying to factor it out, but it will take some time.
	 *
	 * @inheritDoc
	 */
	public function hasChildNodes(): bool {
		if ( $this->_childNodes === null ) {
			/*
			 * If we are using the Linked List representation, then the NULL-ity
			 * of firstChild is diagnostic.
			 */
			return $this->_firstChild !== null;
		} else {
			/*
			 * If we are using the NodeList representation, then the
			 * non-emptiness of childNodes is diagnostic.
			 */
			return !empty( $this->_childNodes );
		}
	}

	/**********************************************************************
	 * MUTATION ALGORITHMS
	 */

	/**
	 * Insert $node as a child of $this, and insert it before $refChild
	 * in the document order.
	 *
	 * spec DOM-LS
	 *
	 * THINGS TO KNOW FROM THE SPEC:
	 *
	 * 1. If $node already exists in
	 *    this Document, this function
	 *    moves it from its current
	 *    position to its new position
	 *    ('move' means 'remove' followed
	 *    by 're-insert').
	 *
	 * 2. If $refNode is NULL, then $node
	 *    is added to the end of the list
	 *    of children of $this. In other
	 *    words, insertBefore($node, NULL)
	 *    is equivalent to appendChild($node).
	 *
	 * 3. If $node is a DocumentFragment,
	 *    the children of the DocumentFragment
	 *    are moved into the child list of
	 *    $this, and the empty DocumentFragment
	 *    is returned.
	 *
	 * THINGS TO KNOW IN LIFE:
	 *
	 * Despite its weird syntax (blame the spec),
	 * this is a real workhorse, used to implement
	 * all of the non-replacing insertion mutations.
	 *
	 * @param INode $node To be inserted
	 * @param ?INode $refNode Child of this node before which to insert $node
	 * @return INode Newly inserted Node or empty DocumentFragment
	 * @throws DOMException "HierarchyRequestError" or "NotFoundError"
	 */
	public function insertBefore( $node, $refNode ) {
		/*
		 * [1]
		 * Ensure pre-insertion validity.
		 * Validation failure will throw
		 * DOMException "HierarchyRequestError" or
		 * DOMException "NotFoundError".
		 */
		WhatWG::ensure_insert_valid( $node, $this, $refNode );

		/*
		 * [2]
		 * If $refNode is $node, re-assign
		 * $refNode to the next sibling of
		 * $node. This may well be NULL.
		 */
		if ( $refNode === $node ) {
			$refNode = $node->getNextSibling();
		}

		/*
		 * [3]
		 * Adopt $node into the Document
		 * to which $this is rooted.
		 */
		$this->_nodeDocument()->adoptNode( $node );

		/*
		 * [4]
		 * Run the complicated algorithm
		 * to Insert $node into $this at
		 * a position before $refNode.
		 */
		WhatWG::insert_before_or_replace( $node, $this, $refNode, false );

		/*
		 * [5]
		 * Return $node
		 */
		return $node;
	}

	/** @inheritDoc */
	public function appendChild( $node ) {
		return $this->insertBefore( $node, null );
	}

	/**
	 * Does not check for insertion validity. This out-performs PHP DOMDocument by
	 * over 2x.
	 *
	 * @param Node $node
	 * @return Node
	 */
	final public function __unsafe_appendChild( Node $node ): Node {
		WhatWG::insert_before_or_replace( $node, $this, null, false );
		return $node;
	}

	/** @inheritDoc */
	public function replaceChild( $new, $old ) {
		/*
		 * [1]
		 * Ensure pre-replacement validity.
		 * Validation failure will throw
		 * DOMException "HierarchyRequestError" or
		 * DOMException "NotFoundError".
		 */
		WhatWG::ensure_replace_valid( $new, $this, $old );

		/*
		 * [2]
		 * Adopt $node into the Document
		 * to which $this is rooted.
		 */
		if ( $new->_nodeDocument() !== $this->_nodeDocument() ) {
			/*
			 * FIXME
			 * adoptNode has a side-effect
			 * of removing the adopted node
			 * from its parent, which
			 * generates a mutation event,
			 * causing _insertOrReplace to
			 * generate 2 deletes and 1 insert
			 * instead of a 'move' event.
			 *
			 * It looks like the MutationObserver
			 * stuff avoids this problem, but for
			 * now let's only adopt (ie, remove
			 * 'node' from its parent) here if we
			 * need to.
			 */
			$this->_nodeDocument()->adoptNode( $new );
		}

		/*
		 * [4]
		 * Run the complicated algorithm
		 * to replace $old with $new.
		 */
		WhatWG::insert_before_or_replace( $new, $this, $old, true );

		/*
		 * [5]
		 * Return $old
		 */
		return $old;
	}

	/** @inheritDoc */
	public function removeChild( $node ) {
		if ( $this === $node->_parentNode ) {
			/* Defined on ChildNode class */
			$node->remove();
		} else {
			/* That's not my child! */
			Util::error( "NotFoundError" );
		}
		/*
		 * The spec requires that
		 * the return value always
		 * be equal to $node.
		 */
		return $node;
	}

	/**
	 * Puts $this and the entire subtree
	 * rooted at $this into "normalized"
	 * form.
	 *
	 * In a normalized sub-tree, no text
	 * nodes in the sub-tree are empty,
	 * and there are no adjacent text nodes.
	 *
	 * @see https://dom.spec.whatwg.org/#dom-node-normalize
	 * @inheritDoc
	 */
	final public function normalize() : void {
		for ( $n = $this->getFirstChild(); $n !== null; $n = $n->getNextSibling() ) {
			/*
			 * [0]
			 * Proceed to traverse the
			 * subtree in a depth-first
			 * fashion.
			 */
			$n->normalize();

			if ( $n->getNodeType() === self::TEXT_NODE ) {
				if ( $n->getNodeValue() === '' ) {
					/*
					 * [1]
					 * If you are a text node,
					 * and you are empty, then
					 * you get pruned.
					 */
					$this->removeChild( $n );
				} else {
					$p = $n->getPreviousSibling();
					if ( $p && $p->getNodeType() === self::TEXT_NODE ) {
						/*
						 * [2]
						 * If you are a text node,
						 * and you are not empty,
						 * and you follow a
						 * non-empty text node
						 * (if it were empty, it
						 * would have been pruned
						 * in the depth-first
						 * traversal), then you
						 * get merged into that
						 * previous non-empty text
						 * node.
						 */
						$p->appendData( $n->getNodeValue() );
						$this->removeChild( $n );
					}
				}
			}
		}
	}

	/**********************************************************************
	 * COMPARISONS AND PREDICATES
	 */

	/** @inheritDoc */
	final public function compareDocumentPosition( $that ): int {
		/*
		 * CAUTION
		 * The order of these args matters
		 */
		return WhatWG::compare_document_position( $that, $this );
	}

	/** @inheritDoc */
	final public function contains( $node ): bool {
		if ( $node === null ) {
			return false;
		}
		if ( $this === $node ) {
			/* As per the DOM-LS, containment is inclusive. */
			return true;
		}

		return ( $this->compareDocumentPosition( $node ) & self::DOCUMENT_POSITION_CONTAINED_BY ) !== 0;
	}

	/**
	 * @inheritDoc
	 */
	final public function isSameNode( $node ): bool {
		return $this === $node;
	}

	/**
	 * Determine whether this node and $other are equal
	 *
	 * spec: DOM-LS
	 *
	 * NOTE:
	 * Each subclass of Node has its own criteria for equality.
	 * Rather than extend   Node::isEqualNode(),  subclasses
	 * must implement   _subclass_isEqualNode(),  which is called
	 * from   Node::isEqualNode()  and handles all of the equality
	 * testing specific to the subclass.
	 *
	 * This allows the recursion and other fast checks to be
	 * handled here and written just once.
	 *
	 * Yes, we realize it's a bit weird.
	 *
	 * @inheritDoc
	 */
	public function isEqualNode( $node ): bool {
		if ( $node === null ) {
			/* We're not equal to NULL */
			return false;
		}
		if ( $node->getNodeType() !== $this->getNodeType() ) {
			/* If we're not the same nodeType, we can stop */
			return false;
		}

		if ( !$this->_subclass_isEqualNode( $node ) ) {
			/* Run subclass-specific equality comparison */
			return false;
		}

		/* Call this method on the children of both nodes */
		for (
			$a = $this->getFirstChild(), $b = $node->getFirstChild();
			$a !== null && $b !== null;
			$a = $a->getNextSibling(), $b = $b->getNextSibling()
		) {
			if ( !$a->isEqualNode( $b ) ) {
				return false;
			}
		}

		/* If we got through all of the children (why wouldn't we?) */
		return $a === null && $b === null;
	}

	/**
	 * Clone this Node
	 *
	 * spec DOM-LS
	 *
	 * NOTE:
	 * 1. If $deep is false, then no child nodes are cloned, including
	 *    any text the node contains (since these are Text nodes).
	 * 2. The duplicate returned by this method is not part of any
	 *    document until it is added using ::appendChild() or similar.
	 * 3. Initially (DOM4)   , $deep was optional with default of 'true'.
	 *    Currently (DOM4-LS), $deep is optional with default of 'false'.
	 * 4. Shallow cloning is delegated to   _subclass_cloneNodeShallow(),
	 *    which needs to be implemented by the subclass.
	 *    For a similar pattern, see Node::isEqualNode().
	 * 5. All "deep clones" are a shallow clone followed by recursion on
	 *    the tree structure, so this suffices to capture subclass-specific
	 *    behavior.
	 *
	 * @param bool $deep if true, clone entire subtree
	 * @return ?Node (clone of $this)
	 */
	public function cloneNode( bool $deep = false ) {
		/* Make a shallow clone using the delegated method */
		$clone = $this->_subclass_cloneNodeShallow();

		/* If the shallow clone is all we wanted, we're done. */
		if ( $deep === false ) {
			return $clone;
		}

		/* Otherwise, recurse on the children */
		for ( $n = $this->getFirstChild(); $n !== null; $n = $n->getNextSibling() ) {
			/* APPEND DIRECTLY; NO CHECKINSERTVALID */
			WhatWG::insert_before_or_replace( $clone, $n->cloneNode( true ), null, false );
		}

		return $clone;
	}

	/**
	 * Return DOMString containing prefix for given namespace URI.
	 *
	 * spec DOM-LS
	 *
	 * NOTE
	 * Think this function looks weird? It's actually spec:
	 * https://dom.spec.whatwg.org/#locate-a-namespace
	 *
	 * @inheritDoc
	 */
	public function lookupPrefix( ?string $ns ): ?string {
		return WhatWG::locate_prefix( $this, $ns );
	}

	/**
	 * Return DOMString containing namespace URI for a given prefix
	 *
	 * NOTE
	 * Inverse of Node::lookupPrefix
	 *
	 * @inheritDoc
	 */
	public function lookupNamespaceURI( ?string $prefix ): ?string {
		return WhatWG::locate_namespace( $this, $prefix );
	}

	/**
	 * Determine whether this is the default namespace
	 *
	 * @inheritDoc
	 */
	public function isDefaultNamespace( ?string $ns ): bool {
		return ( $ns ?? null ) === $this->lookupNamespaceURI( null );
	}

	/**********************************************************************
	 * UTILITY METHODS AND DODO EXTENSIONS
	 */
	/*
	 * You were sorting out ROOTEDNESS AND STUFF
	 * At the same time, you were unravelling the
	 * crucial function ChildNode::remove.
	 *
	 *
	 * There are three distinct phases in which a Node
	 * can exist, and the state diagram works like
	 * this:
	 *
	 *                      [1] Unowned, Unrooted
	 *                      7|
	 *                     / Document::adoptNode()
	 *                    /  v
	 *      Node::remove()  [2] Owned, Unrooted
	 *                    \  |
	 *                     \ Document:;insertBefore()
	 *                      \v
	 *                      [3] Owned, Rooted
	 *
	 *      [1]->[2] (adoption)
	 *              Sets:
	 *                      ownerDocument    on Nodes of subtree rooted at Node
	 *                      _documentIndex on Nodes of subtree rooted at Node
	 *
	 *      [2]->[3] (insertion)
	 *              Sets:
	 *                      parentNode      on Node
	 *                      nextSibling     on Node
	 *                      previousSibling on Node
	 *                      _cachedSiblingIndex on Node
	 *
	 *              Possibly sets:
	 *                      firstChild      on parent of Node, if Node is
	 *                                      the first child.
	 *
	 *      [3]->[1] (removal)
	 *              Unsets:
	 *                      parentNode
	 *                      nextSibling
	 *                      previousSibling
	 *                      _cachedSiblingIndex
	 *                      parentNode->firstChild, if we were last
	 *              ???
	 *                      Does it unset ownerDocument?
	 *                      Does it unset _documentIndex?
	 *                        (remove_from_node_table does this)
	 *
	 * _documentIndex is being set by add_to_node_table. ugh
	 * _documentIndex is being set by add_to_node_table. ugh
	 *
	 * TODO
	 * Centralize all of this.
	 * For instance, node->removeChild(node)
	 * should just call node->remove()?
	 *
	 *      Document::importNode($node)
	 *              $this->adoptNode($node->clone())
	 *      Document::insertBefore()
	 *              Node::insertBefore()
	 *              update_document_stuff;
	 *      Document::replaceChild()
	 *              Node::replaceChild()
	 *              update_document_stuff;
	 *      Document::removeChild()
	 *              Node::removeChild()
	 *              update_document_stuff;
	 *      Document::cloneNode()
	 *              Node::cloneNode();
	 *              (clone children)
	 *              update_document_stuff
	 *
	 * FIXME: This is an antipattern right here.
	 * These don't need to be re-defined on the
	 * Document.
	 *
	 * Already, insert_before_or_replace is calling
	 *      node->__root()
	 *              node->mutate
	 *
	 * and FIXME update_document_state is just
	 * setting whether the document has a doctype
	 * node or a document element. it's horrible.
	 *
	 * And where is _documentIndex being set?
	 */

	/**
	 * Set the ownerDocument reference on a subtree rooted at $this.
	 *
	 * When a Node becomes part of a Document, even if it is not yet inserted.
	 *
	 * Called by Document::adoptNode()
	 *
	 * @param Document $doc
	 */
	public function __set_owner( Document $doc ) {
		$this->_ownerDocument = $doc;

		/* FIXME: Wat ? */
		if ( method_exists( $this, "tagName" ) ) {
			/* Element subclasses might need to change case */
			$this->tagName = null;
		}

		for ( $n = $this->getFirstChild(); $n !== null; $n = $n->getNextSibling() ) {
			$n->__set_owner( $n, $owner );
		}
	}

	/**
	 * Determine whether this Node is rooted (belongs to a tree)
	 *
	 * @return bool
	 *
	 * NOTE
	 * A Node is rooted if it belongs to a tree, in which case it will
	 * have an ownerDocument. Document nodes maintain a list of all the
	 * nodes inside their tree, assigning each an index,
	 * Node::_documentIndex.
	 *
	 * Therefore if we are currently rooted, we can tell by checking that
	 * we have one of these.
	 *
	 * TODO: This should be Node::isConnected(), see spec.
	 */
	public function _isRooted(): bool {
		return (bool)$this->_documentIndex;
	}

	/* Called by WhatWG::insert_before_or_replace */
	/*
	 * TODO
	 * This is the only place where
	 *      __add_to_node_table
	 *      __add_from_id_table
	 * is called.
	 *
	 * FIXME
	 * The *REASON* that this, and __uproot(),
	 * and __set_owner() exist, is fundamentally
	 * that they need to operate recursively on
	 * the subtree, which means it needs to be
	 * down here on Node.
	 *
	 * All of this extra stuff in here just
	 * crept in here over time.
	 */
	public function __root(): void {
		$doc = $this->getOwnerDocument();

		if ( $this->getNodeType() === self::ELEMENT_NODE ) {
			/* getElementById table */
			$id = $this->getAttribute( 'id' );
			if ( $id !== null ) {
				$doc->__add_to_id_table( $id, $this );
			}
			/* <SCRIPT> elements use this hook */
			/* TODO This hook */
			if ( $this->__roothook ) {
				$this->__roothook();
			}

			/*
			 * TODO: Why do we only do this for Element?
			 * This is how it was written in Domino. Is this
			 * a bug?
			 *
			 * Oh, I see, it doesn't recurse if the first
			 * thing isn't an ELEMENT? Well, maybe then
			 * it can't have children? I dunno.
			 */

			/* RECURSE ON CHILDREN */
			/*
			 * TODO
			 * What if we didn't use recursion to do this?
			 * What if we used some other way? Wouldn't that
			 * make it even faster?
			 *
			 * What if we somehow had a list of indices in
			 * documentorder that would give us the subtree.
			 */
			for ( $n = $this->getFirstChild(); $n !== null; $n = $n->getNextSibling() ) {
				$n->__root();
			}
		}
	}

	/*
	 * TODO
	 * This is the only place where
	 *      __remove_from_id_table
	 *      __remove_from_node_table
	 * is called.
	 */
	public function __uproot(): void {
		$doc = $this->getOwnerDocument();

		/* Manage id to element mapping */
		if ( $this->getNodeType() === self::ELEMENT_NODE ) {
			$id = $this->getAttribute( 'id' );
			if ( $id !== null ) {
				$doc->__remove_from_id_table( $id, $this );
			}
		}

		/*
		 * TODO: And here we don't restrict to ELEMENT_NODE.
		 * Why not? I think this is the intended behavior, no?
		 * Then does that make the behavior in root() a bug?
		 * Go over with Scott.
		 */
		for ( $n = $this->getFirstChild(); $n !== null; $n = $n->getNextSibling() ) {
			$n->__uproot();
		}
	}

	/**
	 * The document this node is associated to.
	 *
	 * spec DOM-LS
	 *
	 * NOTE
	 * How is this different from ownerDocument? According to DOM-LS,
	 * Document::ownerDocument() must equal NULL, even though it's often
	 * more convenient if a document is its own owner.
	 *
	 * What we're looking for is the "node document" concept, as laid
	 * out in the DOM-LS spec:
	 *
	 *      -"Each node has an associated node document, set upon creation,
	 *       that is a document."
	 *
	 *      -"A node's node document can be changed by the 'adopt'
	 *       algorithm."
	 *
	 *      -"The node document of a document is that document itself."
	 *
	 *      -"All nodes have a node document at all times."
	 *
	 * TODO
	 * Does the DOM-LS method Node::getRootNode (not implemented here)
	 * in its non-shadow-tree branch, do the same thing?
	 *
	 * TODO
	 * Wouldn't it fit better with all the __root* junk if it were
	 * called __root_node?
	 *
	 * @return Document
	 */
	public function _nodeDocument(): Document {
		return $this->_ownerDocument ?? $this;
	}

	/**
	 * The index of this Node in its parent's childNodes list
	 *
	 * @return int index
	 * @throws Something if we have no parent
	 *
	 * NOTE
	 * Calling Node::_getSiblingIndex() will automatically trigger a switch
	 * to the NodeList representation (see Node::childNodes()).
	 */
	public function _getSiblingIndex(): int {
		if ( $this->_parentNode === null ) {
			return 0; /* ??? TODO: throw or make an error ??? */
		}

		if ( $this === $this->_parentNode->getFirstChild() ) {
			return 0;
		}

		/* We fire up the NodeList mode */
		$childNodes = $this->_parentNode->childNodes();

		/* We end up re-indexing here if we ever run into trouble */
		if ( $this->_cachedSiblingIndex === null || $childNodes[$this->_cachedSiblingIndex] !== $this ) {
			/*
			 * Ensure that we don't have an O(N^2) blowup
			 * if none of the kids have defined indices yet
			 * and we're traversing via nextSibling or
			 * previousSibling
			 */
			foreach ( $childNodes as $i => $child ) {
				$child->_cachedSiblingIndex = $i;
			}

			Util::assert( $childNodes[$this->_cachedSiblingIndex] === $this );
		}
		return $this->_cachedSiblingIndex;
	}

	/**
	 * Remove all of the Node's children.
	 *
	 * NOTE
	 * Provides minor optimization over iterative calls to
	 * Node::removeChild(), since it calls Node::modify() once.
	 * TODO: Node::modify() no longer exists. Does this optimization?
	 */
	public function __remove_children() {
		if ( $this->_isRooted() ) {
			$root = $this->_ownerDocument;
		} else {
			$root = null;
		}

		/* Go through all the children and remove me as their parent */
		for ( $n = $this->getFirstChild(); $n !== null; $n = $n->getNextSibling() ) {
			if ( $root !== null ) {
				/* If we're rooted, mutate */
				$root->_mutateRemove( $n );
			}
			$n->_parentNode = null;
		}

		/* Remove the child node memory or references on this node */
		if ( $this->_childNodes !== null ) {
			/* BRANCH: NodeList (array-like) */
			$this->_childNodes = new NodeList();
		} else {
			/* BRANCH: circular linked list */
			$this->_firstChild = null;
		}
	}

	/**
	 * Convert the children of a node to an HTML string.
	 * This is used by the innerHTML getter
	 *
	 * @return string
	 */
	public function _node_serialize(): string {
		$s = "";

		for ( $n = $this->getFirstChild(); $n !== null; $n = $n->getNextSibling() ) {
			$s .= WhatWG::serialize_node( $n, $this );
		}

		return $s;
	}
}
