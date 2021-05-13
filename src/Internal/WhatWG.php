<?php

declare( strict_types = 1 );
// @phan-file-suppress PhanImpossibleCondition
// @phan-file-suppress PhanParamTooMany
// @phan-file-suppress PhanPluginDuplicateAdjacentStatement
// @phan-file-suppress PhanPluginInvalidPregRegex
// @phan-file-suppress PhanPossiblyUndeclaredVariable
// @phan-file-suppress PhanSuspiciousValueComparison
// @phan-file-suppress PhanTypeMismatchArgumentNullableInternal
// @phan-file-suppress PhanUndeclaredMethod
// @phan-file-suppress PhanUndeclaredProperty
// @phan-file-suppress PhanUndeclaredVariable
// @phan-file-suppress PhanUnextractableAnnotationSuffix
// phpcs:disable Generic.Files.LineLength.TooLong
// phpcs:disable Generic.NamingConventions.CamelCapsFunctionName.ScopeNotCamelCaps
// phpcs:disable Generic.NamingConventions.UpperCaseConstantName.ClassConstantNotUpperCase

namespace Wikimedia\Dodo\Internal;

use Wikimedia\Dodo\Attr;
use Wikimedia\Dodo\DocumentFragment;
use Wikimedia\Dodo\DOMException;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Node;

/******************************************************************************
 * whatwg.php
 * ----------
 * Contains lots of broken-out implementations of algorithms
 * described in WHATWG and other specifications.
 *
 * It was broken out so that the methods in the various classes
 * could be simpler, and to allow for re-use in other places.
 *
 * It also makes it easier to read and understand in isolation from
 * the context of a class, where there can be many conveniences that
 * affect the implementation.
 *
 * That said, it may be a problem having so much on this one page,
 * so perhaps we need to re-examine things.
 *
 */
class WhatWG {

	/******************************************************************************
	 * TREE PREDICATES AND MUTATION
	 */

	/**
	 * @see https://dom.spec.whatwg.org/#dom-node-comparedocumentposition
	 * @param Node $node1
	 * @param Node $node2
	 * @return int
	 */
	public static function compare_document_position( Node $node1, Node $node2 ): int {
		/* #1-#2 */
		if ( $node1 === $node2 ) {
			return 0;
		}

		/* #3 */
		$attr1 = null;
		$attr2 = null;

		/* #4 */
		if ( $node1->getNodeType() === Node::ATTRIBUTE_NODE ) {
			$attr1 = $node1;
			$node1 = $attr1->getOwnerElement();
		}
		/* #5 */
		if ( $node2->getNodeType() === Node::ATTRIBUTE_NODE ) {
			$attr2 = $node2;
			$node2 = $attr2->getOwnerElement();

			if ( $attr1 !== null && $node1 !== null && $node2 === $node1 ) {
				foreach ( $node2->attributes as $a ) {
					if ( $a === $attr1 ) {
						return Node::DOCUMENT_POSITION_IMPLEMENTATION_SPECIFIC + Node::DOCUMENT_POSITION_PRECEDING;
					}
					if ( $a === $attr2 ) {
						return Node::DOCUMENT_POSITION_IMPLEMENTATION_SPECIFIC + Node::DOCUMENT_POSITION_FOLLOWING;
					}
				}
			}
		}

		/* #6 */
		if ( $node1 === null || $node2 === null || $node1->_nodeDocument() !== $node2->_nodeDocument() || $node1->_isRooted() !== $node2->_isRooted() ) {
			/* UHH, in the spec this is supposed to add DOCUMENT_POSITION_PRECEDING or DOCUMENT_POSITION_FOLLOWING
			 * in some consistent way, usually based on pointer comparison, which we can't do here. Hmm. Domino
			 * just straight up omits it. This is stupid, the spec shouldn't ask this. */
			return ( Node::DOCUMENT_POSITION_DISCONNECTED + Node::DOCUMENT_POSITION_IMPLEMENTATION_SPECIFIC );
		}

		/* #7 */
		$node1_ancestors = [];
		$node2_ancestors = [];
		for ( $n = $node1->getParentNode(); $n !== null; $n = $n->getParentNode() ) {
			$node1_ancestors[] = $n;
		}
		for ( $n = $node2->getParentNode(); $n !== null; $n = $n->getParentNode() ) {
			$node2_ancestors[] = $n;
		}

		if ( in_array( $node1, $node2_ancestors ) && $attr1 === null ) {
			return Node::DOCUMENT_POSITION_CONTAINS + Node::DOCUMENT_POSITION_PRECEDING;
		} elseif ( $node1 === $node2 && $attr2 !== null ) {
			return Node::DOCUMENT_POSITION_CONTAINS + Node::DOCUMENT_POSITION_PRECEDING;
		}

		/* #8 */
		if ( in_array( $node2, $node1_ancestors ) && $attr2 === null ) {
			return Node::DOCUMENT_POSITION_CONTAINED_BY + Node::DOCUMENT_POSITION_FOLLOWING;
		} elseif ( $node1 === $node2 && $attr1 !== null ) {
			return Node::DOCUMENT_POSITION_CONTAINED_BY + Node::DOCUMENT_POSITION_FOLLOWING;
		}

		/* #9 */
		$node1_ancestors = array_reverse( $node1_ancestors );
		$node2_ancestors = array_reverse( $node2_ancestors );
		$len = min( count( $node1_ancestors ), count( $node2_ancestors ) );

		for ( $i = 1; $i < $len; $i++ ) {
			if ( $node1_ancestors[$i] !== $node2_ancestors[$i] ) {
				if ( $node1_ancestors[$i]->__sibling_index() < $node2_ancestors[$i]->__sibling_index() ) {
					return Node::DOCUMENT_POSITION_PRECEDING;
				}
			}
		}

		# 10
		return Node::DOCUMENT_POSITION_FOLLOWING;
	}

	/*
	 * DOM-LS Removes the 'prefix' and 'namespaceURI' attributes from
	 * Node and places them only on Element and Attr.
	 *
	 * Due to the fact that an Attr (should) have an ownerElement,
	 * these two algorithms only operate on Elements.
	 *
	 * The spec actually says that if an Attr has no ownerElement,
	 * then the algorithm returns NULL.
	 *
	 * Anyway, they operate only on Elements.
	 */

	/**
	 * @see https://dom.spec.whatwg.org/#locate-a-namespace
	 *
	 * @param Node $node
	 * @param ?string $prefix
	 * @return ?string
	 */
	public static function locate_namespace( Node $node, ?string $prefix ): ?string {
		if ( $prefix === '' ) {
			$prefix = null;
		}

		switch ( $node->getNodeType() ) {
		case Node::ENTITY_NODE:
		case Node::NOTATION_NODE:
		case Node::DOCUMENT_TYPE_NODE:
		case Node::DOCUMENT_FRAGMENT_NODE:
			break;
		case Node::ELEMENT_NODE:
			if ( $node->getNamespaceURI() !== null && $node->prefix() === $prefix ) {
				return $node->getNamespaceURI();
			}
			foreach ( $node->attributes as $a ) {
				if ( $a->getNamespaceURI() === Util::NAMESPACE_XMLNS ) {
					if ( ( $a->prefix() === 'xmlns' && $a->getLocalName() === $prefix )
						 || ( $prefix === null && $a->prefix() === null && $a->getLocalName() === 'xmlns' ) ) {
						$val = $a->getValue();
						return ( $val === "" ) ? null : $val;
					}
				}
			}
			break;
		case Node::DOCUMENT_NODE:
			if ( $node->_documentElement ) {
				return self::locate_namespace( $node->_documentElement, $prefix );
			}
			break;
		case Node::ATTRIBUTE_NODE:
			if ( $node->_ownerElement ) {
				return self::locate_namespace( $node->_ownerElement, $prefix );
			}
			break;
		default:
			$parent = $node->getParentElement();
			if ( $parent === null ) {
				return null;
			} else {
				'@phan-var Element $parent'; // @var Element $parent
				return self::locate_namespace( $parent, $ns );
			}
		}

		return null;
	}

	/**
	 * @see https://dom.spec.whatwg.org/#locate-a-namespace-prefix
	 *
	 * @param Node $node
	 * @param ?string $ns
	 * @return ?string
	 */
	public static function locate_prefix( Node $node, ?string $ns ): ?string {
		if ( $ns === "" || $ns === null ) {
			return null;
		}

		switch ( $node->getNodeType() ) {
		case Node::ENTITY_NODE:
		case Node::NOTATION_NODE:
		case Node::DOCUMENT_FRAGMENT_NODE:
		case Node::DOCUMENT_TYPE_NODE:
			break;
		case Node::ELEMENT_NODE:
			if ( $node->getNamespaceURI() !== null && $node->getNamespaceURI() === $ns ) {
				return $node->prefix();
			}

			foreach ( $node->attributes as $a ) {
				if ( $a->prefix() === "xmlns" && $a->getValue() === $ns ) {
					return $a->getLocalName();
				}
			}
			break;
		case Node::DOCUMENT_NODE:
			if ( $node->_documentElement ) {
				return self::locate_prefix( $node->_documentElement, $ns );
			}
			break;
		case  Node::ATTRIBUTE_NODE:
			if ( $node->_ownerElement ) {
				return self::locate_prefix( $node->_ownerElement, $ns );
			}
			break;
		default:
			$parent = $node->getParentElement();
			if ( $parent === null ) {
				return null;
			} else {
				'@phan-var Element $parent'; // @var Element $parent
				return self::locate_prefix( $parent, $ns );
			}
		}

		return null;
	}

	/**
	 * @param Node $node
	 * @param Node $parent
	 * @param ?Node $before
	 * @param bool $replace
	 */
	public static function insert_before_or_replace( Node $node, Node $parent, ?Node $before, bool $replace ): void {
		/*
		 * TODO: FACTOR: $before is intended to always be non-NULL
		 * if $replace is true, but I think that could fail unless
		 * we encode it into the prototype, which is non-standard.
		 * (we are combining the 'insert before' and 'replace' algos)
		 */

		/******************* PRE-FLIGHT CHECKS */

		if ( $node === $before ) {
			return;
		}

		if ( $node instanceof DocumentFragment && $node->_isRooted() ) {
			Util::error( "HierarchyRequestError" );
		}

		/******************** COMPUTE AN INDEX */
		/* NOTE: MUST DO HERE BECAUSE STATE WILL CHANGE */

		if ( $parent->_childNodes ) {
			if ( $before !== null ) {
				$ref_index = $before->__sibling_index();
			} else {
				$ref_index = count( $parent->_childNodes );
			}
			if ( $node->_parentNode === $parent && $node->__sibling_index() < $ref_index ) {
				$ref_index--;
			}
		}

		$ref_node = $before ?? $parent->getFirstChild();

		/************ IF REPLACING, REMOVE OLD CHILD */

		if ( $replace ) {
			if ( $before->_isRooted() ) {
				// @phan-suppress-next-line PhanTypeMismatchArgumentNullable This is a real bug, to be fixed in a followup
				$before->_nodeDocument()->_mutateRemove( $before );
				$before->__uproot();
			}
			$before->_parentNode = null;
		}

		/************ IF BOTH ROOTED, FIRE MUTATIONS */

		$bothWereRooted = $node->_isRooted() && $parent->_isRooted();

		if ( $bothWereRooted ) {
			/* "soft remove" -- don't want to uproot it. */
			$node->_remove();
		} else {
			if ( $node->_parentNode ) {
				$node->remove();
			}
		}

		/************** UPDATE THE NODE LIST DATA */

		$insert = [];

		if ( $node instanceof DocumentFragment ) {
			for ( $n = $node->getFirstChild(); $n !== null; $n = $n->getNextSibling() ) {
				$insert[] = $n; /* TODO: Needs to clone? */
				$n->_parentNode = $parent;
			}
		} else {
			$insert[0] = $node; /* TODO: Needs to clone? */
			$insert[0]->_parentNode = $parent;
		}

		if ( empty( $insert ) ) {
			if ( $replace ) {
				if ( $ref_node !== null /* If you work it out, you'll find that this condition is equivalent to 'if $parent has children' */ ) {
					LinkedList::ll_replace( $ref_node, null );
				}
				if ( $parent->_childNodes === null && $parent->_firstChild === $before ) {
					$parent->_firstChild = null;
				}
			}
		} else {
			if ( $ref_node !== null ) {
				if ( $replace ) {
					LinkedList::ll_replace( $ref_node, $insert[0] );
				} else {
					LinkedList::ll_insert_before( $insert[0], $ref_node );
				}
			}
			if ( $parent->_childNodes !== null ) {
				if ( $replace ) {
					array_splice( $parent->_childNodes, $ref_index, 1, $insert );
				} else {
					array_splice( $parent->_childNodes, $ref_index, 0, $insert );
				}
				foreach ( $insert as $i => $n ) {
					$n->_index = $ref_index + $i;
				}
			} elseif ( $parent->_firstChild === $before ) {
				$parent->_firstChild = $insert[0];
			}
		}

		/*********** EMPTY OUT THE DOCUMENT FRAGMENT */

		if ( $node instanceof DocumentFragment ) {
			/*
			 * TODO: Why? SPEC SAYS SO!
			 */
			if ( $node->_childNodes ) {
				/* TODO PORT: easiest way to do this in PHP and preserves references */
				$node->_childNodes = [];
			} else {
				$node->_firstChild = null;
			}
		}

		/************ ROOT NODES AND FIRE MUTATION HANDLERS */

		$d = $parent->_nodeDocument();

		if ( $bothWereRooted ) {
			$d->_mutateMove( $insert[0] );
		} else {
			if ( $parent->_isRooted() ) {
				foreach ( $insert as $n ) {
					$n->__root( $d );
					$d->_mutateInsert( $n );
				}
			}
		}
	}

	/**
	 * TODO: Look at the way these were implemented in the original;
	 * there are some speedups esp in the way that you implement
	 * things like "node has a doctype child that is not child
	 *
	 * @param Node $node
	 * @param Node $parent
	 * @param ?Node $child
	 */
	public static function ensure_insert_valid( Node $node, Node $parent, ?Node $child ): void {
		/*
		 * DOM-LS: #1: If parent is not a Document, DocumentFragment,
		 * or Element node, throw a HierarchyRequestError.
		 */
		switch ( $parent->getNodeType() ) {
		case Node::DOCUMENT_NODE:
		case Node::DOCUMENT_FRAGMENT_NODE:
		case Node::ELEMENT_NODE:
			break;
		default:
			Util::error( "HierarchyRequestError" );
		}

		/*
		 * DOM-LS #2: If node is a host-including inclusive ancestor
		 * of parent, throw a HierarchyRequestError.
		 */
		if ( $node === $parent ) {
			Util::error( "HierarchyRequestError" );
		}
		if ( $node->_nodeDocument() === $parent->_nodeDocument() && $node->_isRooted() === $parent->_isRooted() ) {
			/*
			 * If the conditions didn't figure it out, then check
			 * by traversing parentNode chain.
			 */
			for ( $n = $parent; $n !== null; $n = $n->getParentNode() ) {
				if ( $n === $node ) {
					Util::error( "HierarchyRequestError" );
				}
			}
		}

		/*
		 * DOM-LS #3: If child is not null and its parent is not $parent, then
		 * throw a NotFoundError
		 */
		if ( $child !== null && $child->_parentNode !== $parent ) {
			Util::error( "NotFoundError" );
		}

		/*
		 * DOM-LS #4: If node is not a DocumentFragment, DocumentType,
		 * Element, Text, ProcessingInstruction, or Comment Node,
		 * throw a HierarchyRequestError.
		 */
		switch ( $node->getNodeType() ) {
		case Node::DOCUMENT_FRAGMENT_NODE:
		case Node::DOCUMENT_TYPE_NODE:
		case Node::ELEMENT_NODE:
		case Node::TEXT_NODE:
		case Node::PROCESSING_INSTRUCTION_NODE:
		case Node::COMMENT_NODE:
			break;
		default:
			Util::error( "HierarchyRequestError" );
		}

		/*
		 * DOM-LS #5. If either:
		 *      -node is a Text and parent is a Document
		 *      -node is a DocumentType and parent is not a Document
		 * throw a HierarchyRequestError
		 */
		if ( ( $node->getNodeType() === Node::TEXT_NODE && $parent->getNodeType() === Node::DOCUMENT_NODE )
			 || ( $node->getNodeType() === Node::DOCUMENT_TYPE_NODE && $parent->getNodeType() !== Node::DOCUMENT_NODE ) ) {
			Util::error( "HierarchyRequestError" );
		}

		/*
		 * DOM-LS #6: If parent is a Document, and any of the
		 * statements below, switched on node, are true, throw a
		 * HierarchyRequestError.
		 */
		if ( $parent->getNodeType() !== Node::DOCUMENT_NODE ) {
			return;
		}

		switch ( $node->getNodeType() ) {
		case Node::DOCUMENT_FRAGMENT_NODE:
			/*
			 * DOM-LS #6a-1: If node has more than one
			 * Element child or has a Text child.
			 */
			$count_text = 0;
			$count_element = 0;

			for ( $n = $node->getFirstChild(); $n !== null; $n = $n->getNextSibling() ) {
				if ( $n->getNodeType() === Node::TEXT_NODE ) {
					$count_text++;
				}
				if ( $n->getNodeType() === Node::ELEMENT_NODE ) {
					$count_element++;
				}
				if ( $count_text > 0 && $count_element > 1 ) {
					Util::error( "HierarchyRequestError" );
					// TODO: break ? return ?
				}
			}
			/*
			 * DOM-LS #6a-2: If node has one Element
			 * child and either:
			 */
			if ( $count_element === 1 ) {
				/* DOM-LS #6a-2a: child is a DocumentType */
				if ( $child !== null && $child->getNodeType() === Node::DOCUMENT_TYPE_NODE ) {
					Util::error( "HierarchyRequestError" );
				}
				/*
				 * DOM-LS #6a-2b: child is not NULL and a
				 * DocumentType is following child.
				 */
				if ( $child !== null ) {
					for ( $n = $child->getNextSibling(); $n !== null; $n = $n->getNextSibling() ) {
						if ( $n->getNodeType() === Node::DOCUMENT_TYPE_NODE ) {
							Util::error( "HierarchyRequestError" );
						}
					}
				}
				/* DOM-LS #6a-2c: parent has an Element child */
				for ( $n = $parent->getFirstChild(); $n !== null; $n = $n->getNextSibling() ) {
					if ( $n->getNodeType() === Node::ELEMENT_NODE ) {
						Util::error( "HierarchyRequestError" );
					}
				}
			}
			break;
		case Node::ELEMENT_NODE:
			/* DOM-LS #6b-1: child is a DocumentType */
			if ( $child !== null && $child->getNodeType() === Node::DOCUMENT_TYPE_NODE ) {
				Util::error( "HierarchyRequestError" );
			}
			/* DOM-LS #6b-2: child not NULL and DocumentType is following child. */
			if ( $child !== null ) {
				for ( $n = $child->getNextSibling(); $n !== null; $n = $n->getNextSibling() ) {
					if ( $n->getNodeType() === Node::DOCUMENT_TYPE_NODE ) {
						Util::error( "HierarchyRequestError" );
					}
				}
			}
			/* DOM-LS #6b-3: parent has an Element child */
			for ( $n = $parent->getFirstChild(); $n !== null; $n = $n->getNextSibling() ) {
				if ( $n->getNodeType() === Node::ELEMENT_NODE ) {
					Util::error( "HierarchyRequestError" );
				}
			}
			break;
		case Node::DOCUMENT_TYPE_NODE:
			/* DOM-LS #6c-1: parent has a DocumentType child */
			for ( $n = $parent->getFirstChild(); $n !== null; $n = $n->getNextSibling() ) {
				if ( $n->getNodeType() === Node::DOCUMENT_TYPE_NODE ) {
					Util::error( "HierarchyRequestError" );
				}
			}
			/*
			 * DOM-LS #6c-2: child is not NULL and an Element
			 * is preceding child,
			 */
			if ( $child !== null ) {
				for ( $n = $child->previousSibling(); $n !== null; $n = $n->previousSibling() ) {
					if ( $n->getNodeType() === Node::ELEMENT_NODE ) {
						Util::error( "HierarchyRequestError" );
					}
				}
			}
			/*
			 * DOM-LS #6c-3: child is NULL and parent has
			 * an Element child.
			 */
			if ( $child === null ) {
				for ( $n = $parent->getFirstChild(); $n !== null; $n = $n->getNextSibling() ) {
					if ( $n->getNodeType() === Node::ELEMENT_NODE ) {
						Util::error( "HierarchyRequestError" );
					}
				}
			}

			break;
		}
	}

	/**
	 * @param Node $node
	 * @param Node $parent
	 * @param Node $child
	 */
	public static function ensure_replace_valid( Node $node, Node $parent, Node $child ): void {
		/*
		 * DOM-LS: #1: If parent is not a Document, DocumentFragment,
		 * or Element node, throw a HierarchyRequestError.
		 */
		switch ( $parent->nodeType ) {
		case Node::DOCUMENT_NODE:
		case Node::DOCUMENT_FRAGMENT_NODE:
		case Node::ELEMENT_NODE:
			break;
		default:
			Util::error( "HierarchyRequestError" );
		}

		/*
		 * DOM-LS #2: If node is a host-including inclusive ancestor
		 * of parent, throw a HierarchyRequestError.
		 */
		if ( $node === $parent ) {
			Util::error( "HierarchyRequestError" );
		}
		if ( $node->_nodeDocument() === $parent->_nodeDocument() && $node->_isRooted() === $parent->_isRooted() ) {
			/*
			 * If the conditions didn't figure it out, then check
			 * by traversing parentNode chain.
			 */
			for ( $n = $parent; $n !== null; $n = $n->getParentNode() ) {
				if ( $n === $node ) {
					Util::error( "HierarchyRequestError" );
				}
			}
		}

		/*
		 * DOM-LS #3: If child's parentNode is not parent
		 * throw a NotFoundError
		 */
		if ( $child->_parentNode !== $parent ) {
			Util::error( "NotFoundError" );
		}

		/*
		 * DOM-LS #4: If node is not a DocumentFragment, DocumentType,
		 * Element, Text, ProcessingInstruction, or Comment Node,
		 * throw a HierarchyRequestError.
		 */
		switch ( $node->getNodeType() ) {
		case Node::DOCUMENT_FRAGMENT_NODE:
		case Node::DOCUMENT_TYPE_NODE:
		case Node::ELEMENT_NODE:
		case Node::TEXT_NODE:
		case Node::PROCESSING_INSTRUCTION_NODE:
		case Node::COMMENT_NODE:
			break;
		default:
			Util::error( "HierarchyRequestError" );
		}

		/*
		 * DOM-LS #5. If either:
		 *      -node is a Text and parent is a Document
		 *      -node is a DocumentType and parent is not a Document
		 * throw a HierarchyRequestError
		 */
		if ( ( $node->getNodeType() === Node::TEXT_NODE && $parent->getNodeType() === Node::DOCUMENT_NODE )
			 || ( $node->getNodeType() === Node::DOCUMENT_TYPE_NODE && $parent->getNodeType() !== Node::DOCUMENT_NODE ) ) {
			Util::error( "HierarchyRequestError" );
		}

		/*
		 * DOM-LS #6: If parent is a Document, and any of the
		 * statements below, switched on node, are true, throw a
		 * HierarchyRequestError.
		 */
		if ( $parent->getNodeType() !== Node::DOCUMENT_NODE ) {
			return;
		}

		switch ( $node->getNodeType() ) {
		case Node::DOCUMENT_FRAGMENT_NODE:
			/*
			 * #6a-1: If node has more than one Element child
			 * or has a Text child.
			 */
			$count_text = 0;
			$count_element = 0;

			for ( $n = $node->getFirstChild(); $n !== null; $n = $n->getNextSibling() ) {
				if ( $n->getNodeType() === Node::TEXT_NODE ) {
					$count_text++;
				}
				if ( $n->getNodeType() === Node::ELEMENT_NODE ) {
					$count_element++;
				}
				if ( $count_text > 0 && $count_element > 1 ) {
					Util::error( "HierarchyRequestError" );
				}
			}
			/* #6a-2: If node has one Element child and either: */
			if ( $count_element === 1 ) {
				/* #6a-2a: parent has an Element child that is not child */
				for ( $n = $parent->getFirstChild(); $n !== null; $n = $n->getNextSibling() ) {
					if ( $n->getNodeType() === Node::ELEMENT_NODE && $n !== $child ) {
						Util::error( "HierarchyRequestError" );
					}
				}
				/* #6a-2b: a DocumentType is following child. */
				for ( $n = $child->getNextSibling(); $n !== null; $n = $n->getNextSibling() ) {
					if ( $n->getNodeType() === Node::DOCUMENT_TYPE_NODE ) {
						Util::error( "HierarchyRequestError" );
					}
				}
			}
			break;
		case Node::ELEMENT_NODE:
			/* #6b-1: parent has an Element child that is not child */
			for ( $n = $parent->getFirstChild(); $n !== null; $n = $n->getNextSibling() ) {
				if ( $n->getNodeType() === Node::ELEMENT_NODE && $n !== $child ) {
					Util::error( "HierarchyRequestError" );
				}
			}
			/* #6b-2: DocumentType is following child. */
			for ( $n = $child->getNextSibling(); $n !== null; $n = $n->getNextSibling() ) {
				if ( $n->nodeType === Node::DOCUMENT_TYPE_NODE ) {
					Util::error( "HierarchyRequestError" );
				}
			}
			break;
		case Node::DOCUMENT_TYPE_NODE:
			/* #6c-1: parent has a DocumentType child */
			for ( $n = $parent->getFirstChild(); $n !== null; $n = $n->getNextSibling() ) {
				if ( $n->getNodeType() === Node::DOCUMENT_TYPE_NODE ) {
					Util::error( "HierarchyRequestError" );
				}
			}
			/* #6c-2: an Element is preceding child */
			for ( $n = $child->previousSibling(); $n !== null; $n = $n->previousSibling() ) {
				if ( $n->getNodeType() === Node::ELEMENT_NODE ) {
					Util::error( "HierarchyRequestError" );
				}
			}
			break;
		}
	}

	/******************************************************************************
	 * SERIALIZATION
	 */

	/**
	 * PORT NOTES
	 *      The `serializeOne()` function used to live on the `Node.prototype`
	 *      as a private method `Node#_serializeOne(child)`, however that requires
	 *      a megamorphic property access `this._serializeOne` just to get to the
	 *      method, and this is being done on lots of different `Node` subclasses,
	 *      which puts a lot of pressure on V8's megamorphic stub cache. So by
	 *      moving the helper off of the `Node.prototype` and into a separate
	 *      function in this helper module, we get a monomorphic property access
	 *      `NodeUtils.serializeOne` to get to the function and reduce pressure
	 *      on the megamorphic stub cache.
	 *      See https://github.com/fgnass/domino/pull/142 for more information.
	 */
	/* http://www.whatwg.org/specs/web-apps/current-work/multipage/the-end.html#serializing-html-fragments */

	/** @var array<string,bool> */
	private static $hasRawContent = [
		"STYLE" => true,
		"SCRIPT" => true,
		"XMP" => true,
		"IFRAME" => true,
		"NOEMBED" => true,
		"NOFRAMES" => true,
		"PLAINTEXT" => true
	];

	/** @var array<string,bool> */
	private static $emptyElements = [
		"area" => true,
		"base" => true,
		"basefont" => true,
		"bgsound" => true,
		"br" => true,
		"col" => true,
		"embed" => true,
		"frame" => true,
		"hr" => true,
		"img" => true,
		"input" => true,
		"keygen" => true,
		"link" => true,
		"meta" => true,
		"param" => true,
		"source" => true,
		"track" => true,
		"wbr" => true
	];

	/** @var array<string,bool> */
	private static $extraNewLine = [
		/* Removed in https://github.com/whatwg/html/issues/944 */
		/*
		  "pre" => true,
		  "textarea" => true,
		  "listing" => true
		*/
	];

	/**
	 * @param string $s
	 * @return string
	 */
	public static function _helper_escape( $s ) {
		return str_replace(
			/* PORT: PHP7: \u{00a0} */
			/*
			 * NOTE: '&'=>'&amp;' must come first! Processing done LTR,
			 * so otherwise we will recursively replace the &'s.
			 */
			[ "&","<",">","\u{00A0}" ],
			[ "&amp;", "&lt;", "&gt;", "&nbsp;" ],
			$s
		);
	}

	/**
	 * @param string $s
	 * @return string
	 */
	public static function _helper_escapeAttr( $s ) {
		return str_replace(
			[ "&", "\"", "\u{00A0}" ],
			[ "&amp;", "&quot;", "&nbsp;" ],
			$s
		);

		/* TODO: Is there still a fast path in PHP? (see NodeUtils.js) */
	}

	/**
	 * @param Attr $a
	 * @return string
	 */
	public static function _helper_attrname( Attr $a ) {
		$ns = $a->getNamespaceURI();

		if ( !$ns ) {
			return $a->getLocalName();
		}

		if ( $ns === Util::NAMESPACE_XML ) {
			return 'xml:' . $a->getLocalName();
		}
		if ( $ns === Util::NAMESPACE_XLINK ) {
			return 'xlink:' . $a->getLocalName();
		}
		if ( $ns === Util::NAMESPACE_XMLNS ) {
			if ( $a->getLocalName() === 'xmlns' ) {
				return 'xmlns';
			} else {
				return 'xmlns:' . $a->getLocalName();
			}
		}

		return $a->getName();
	}

	/**
	 * @param Node $child
	 * @param Node $parent
	 * @return string
	 */
	public static function serialize_node( Node $child, Node $parent ) {
		$s = "";

		switch ( $child->getNodeType() ) {
		case Node::ELEMENT_NODE:
			$ns = $child->getNamespaceURI();
			$html = ( $ns === Util::NAMESPACE_HTML );

			if ( $html || $ns === Util::NAMESPACE_SVG || $ns === Util::NAMESPACE_MATHML ) {
				$tagname = $child->getLocalName();
			} else {
				$tagname = $child->getTagName();
			}

			$s .= '<' . $tagname;

			foreach ( $child->attributes as $a ) {
				$s .= ' ' . self::_helper_attrname( $a );

				/*
				 * PORT: TODO: Need to ensure this value is NULL
				 * rather than undefined?
				 */
				if ( $a->getValue() !== null ) {
					$s .= '="' . self::_helper_escapeAttr( $a->getValue() ) . '"';
				}
			}

			$s .= '>';

			if ( !( $html && isset( self::$emptyElements[$tagname] ) ) ) {
				/* PORT: TODO: Check this serialize function */
				$ss = $child->_node_serialize();
				if ( $html && isset( self::$extraNewLine[$tagname] ) && $ss[0] === '\n' ) {
					$s .= '\n';
				}
				/* Serialize children and add end tag for all others */
				$s .= $ss;
				$s .= '</' . $tagname . '>';
			}
			break;

		case Node::TEXT_NODE:
		case Node::CDATA_SECTION_NODE:
			if ( $parent->getNodeType() === Node::ELEMENT_NODE && $parent->getNamespaceURI() === Util::NAMESPACE_HTML ) {
				$parenttag = $parent->getTagName();
			} else {
				$parenttag = '';
			}

			if ( isset( self::$hasRawContent[$parenttag] ) || ( $parenttag === 'NOSCRIPT' && $parent->getOwnerDocument()->_scripting_enabled ) ) {
				$s .= $child->getData();
			} else {
				$s .= self::_helper_escape( $child->getData() );
			}
			break;

		case Node::COMMENT_NODE:
			$s .= '<!--' . $child->getData() . '-->';
			break;

		case Node::PROCESSING_INSTRUCTION_NODE:
			$s .= '<?' . $child->getTarget() . ' ' . $child->getData() . '?>';
			break;

		case Node::DOCUMENT_TYPE_NODE:
			$s .= '<!DOCTYPE ' . $child->getName();

            // phpcs:ignore Generic.CodeAnalysis.UnconditionalIfStatement.Found
			if ( false ) {
				// Latest HTML serialization spec omits the public/system ID
				if ( $child->_publicID ) {
					$s .= ' PUBLIC "' . $child->_publicId . '"';
				}

				if ( $child->_systemId ) {
					$s .= ' "' . $child->_systemId . '"';
				}
			}

			$s .= '>';
			break;
		default:
			Util::error( "InvalidStateError" );
		}

		return $s;
	}

	/******************************************************************************
	 * XML NAMES
	 */
	/******************************************************************************
	 * In XML, valid names for Elements or Attributes are governed by a
	 * number of overlapping rules, reflecting a gradual standardization
	 * process.
	 *
	 * If terms like 'qualified name,' 'local name', 'namespace', and
	 * 'prefix' are unfamiliar to you, consult:
	 *
	 *      https://www.w3.org/TR/xml/#NT-Name
	 *      https://www.w3.org/TR/xml-names/#NT-QName
	 *
	 * This grammar is from the XML and XML Namespace specs. It specifies whether
	 * a string (such as an element or attribute name) is a valid Name or QName.
	 *
	 * Name           ::= NameStartChar (NameChar)*
	 * NameStartChar  ::= ":" | [A-Z] | "_" | [a-z] |
	 *                    [#xC0-#xD6] | [#xD8-#xF6] | [#xF8-#x2FF] |
	 *                    [#x370-#x37D] | [#x37F-#x1FFF] |
	 *                    [#x200C-#x200D] | [#x2070-#x218F] |
	 *                    [#x2C00-#x2FEF] | [#x3001-#xD7FF] |
	 *                    [#xF900-#xFDCF] | [#xFDF0-#xFFFD] |
	 *                    [#x10000-#xEFFFF]
	 *
	 * NameChar       ::= NameStartChar | "-" | "." | [0-9] |
	 *                    #xB7 | [#x0300-#x036F] | [#x203F-#x2040]
	 *
	 * QName          ::= PrefixedName| UnprefixedName
	 * PrefixedName   ::= Prefix ':' LocalPart
	 * UnprefixedName ::= LocalPart
	 * Prefix         ::= NCName
	 * LocalPart      ::= NCName
	 * NCName         ::= Name - (Char* ':' Char*)
	 *                    # An XML Name, minus the ":"
	 */
	/* TODO: PHP /u unicode matching? */

	/*
	 * Most names will be ASCII only. Try matching against simple regexps first
	 *
	 * [HTML-5] Attribute names may be written with any mix of ASCII lowercase
	 * and ASCII uppercase alphanumerics.
	 *
	 * Recall:
	 *      \w matches any alphanumeric character A-Za-z0-9
	 */
	/*
	 * TODO: PORT NOTE: in Domino, this pattern was '/^[_:A-Za-z][-.:\w]+$/',
	 * which fails for one-letter tagnames (e.g. <p>). This was not a problem
	 * because <p> is an HTML element and is thus instantiated differently, but
	 * I think one-letter tagnames is still valid, right?
	 *
	 * Also, in PHP, sending 'p' as the name will not add '\n' to the end of
	 * the string, while sending "p" DOES add the newline. The newline is
	 * matched by \w and will thus allow a match, but it depends on whether
	 * the string was single or double-quoted.
	 *
	 * To avoid this complication, we switched the '+' to a '*'.
	 *
	 * Interestingly, in the regex patterns in the next section, it seems that
	 * we do indeed use '*' in Domino, so why was '+' being preferred here?
	 */
	private const pattern_ascii_name = '/^[_:A-Za-z][-.:\w]*$/';
	private const pattern_ascii_qname = '/^([_A-Za-z][-.\w]*|[_A-Za-z][-.\w]*:[_A-Za-z][-.\w]*)$/';

	/*
	 * If the regular expressions above fail, try more complex ones that work
	 * for any identifiers using codepoints from the Unicode BMP
	 */
	private const start = '_A-Za-z\\x{00C0}-\\x{00D6}\\x{00D8}-\\x{00F6}\\x{00F8}-\\x{02ff}\\x{0370}-\\x{037D}\\x{037F}-\\x{1FFF}\\x{200C}-\\x{200D}\\x{2070}-\\x{218F}\\x{2C00}-\\x{2FEF}\\x{3001}-\\x{D7FF}\\x{F900}-\\x{FDCF}\\x{FDF0}-\\x{FFFD}';
	private const char = '-._A-Za-z0-9\\x{00B7}\\x{00C0}-\\x{00D6}\\x{00D8}-\\x{00F6}\\x{00F8}-\\x{02ff}\\x{0300}-\\x{037D}\\x{037F}-\\x{1FFF}\\x{200C}\\x{200D}\\x{203f}\\x{2040}\\x{2070}-\\x{218F}\\x{2C00}-\\x{2FEF}\\x{3001}-\\x{D7FF}\\{F900}-\\x{FDCF}\\x{FDF0}-\\x{FFFD}';

	private const pattern_name = '/^[' . self::start . ']' . '[:' . self::char . ']*$/';
	private const pattern_qname = '/^([' . self::start . '][' . self::char . ']*|[' . self::start . '][' . self::char . ']*:[' . self::start . '][' . self::char . ']*)$/';

	/*
	 * XML says that these characters are also legal:
	 * [#x10000-#xEFFFF].  So if the patterns above fail, and the
	 * target string includes surrogates, then try the following
	 * patterns that allow surrogates and then run an extra validation
	 * step to make sure that the surrogates are in valid pairs and in
	 * the right range.  Note that since the characters \uf0000 to \u1f0000
	 * are not allowed, it means that the high surrogate can only go up to
	 * \uDB7f instead of \uDBFF.
	 */
	private const surrogates = '\\x{D800}-\\x{DB7F}\\x{DC00}-\\x{DFFF}';

	private const pattern_has_surrogates = '/[' . self::surrogates . ']/';
	private const pattern_surrogate_chars = '/[' . self::surrogates . ']/';
	private const pattern_surrogate_pairs = '/[\\x{D800}-\\x{DB7F}][\\x{DC00}-\\x{DFFF}]/';

	private const surrogate_start = self::start . self::surrogates;
	private const surrogate_char = self::char . self::surrogates;

	private const pattern_surrogate_name = '/^[' . self::surrogate_start . ']' . '[:' . self::surrogate_char . ']*$/';
	private const pattern_surrogate_qname = '/^([' . self::surrogate_start . '][' . self::surrogate_char . ']*|[' . self::surrogate_start . '][' . self::surrogate_char . ']*:[' . self::surrogate_start . '][' . self::surrogate_char . ']*)$/';

	/**
	 * @param string $s
	 * @return bool
	 */
	public static function is_valid_xml_name( $s ) {
		if ( preg_match( self::pattern_ascii_name, $s ) ) {
			return true; // Plain ASCII
		}
		if ( preg_match( self::pattern_name, $s ) ) {
			return true; // Unicode BMP
		}

		/*
		 * Maybe the tests above failed because s includes surrogate pairs
		 * Most likely, though, they failed for some more basic syntax problem
		 */
		if ( !preg_match( self::pattern_has_surrogates, $s ) ) {
			return false;
		}

		/* Is the string a valid name if we allow surrogates? */
		if ( !preg_match( self::pattern_surrogate_name, $s ) ) {
			return false;
		}

		/* Finally, are the surrogates all correctly paired up? */
		$matches_chars = [];
		$matches_pairs = [];

		$ret0 = preg_match( self::pattern_surrogate_chars, $s, $matches_chars );
		$ret1 = preg_match( self::pattern_surrogate_pairs, $s, $matches_pairs );

		return ( $ret0 && $ret1 ) && ( ( 2 * count( $matches_pairs ) ) === count( $matches_chars ) );
	}

	/**
	 * @param string $s
	 * @return bool
	 */
	public static function is_valid_xml_qname( $s ) {
		if ( preg_match( self::pattern_ascii_qname, $s ) ) {
			return true; // Plain ASCII
		}
		if ( preg_match( self::pattern_ascii_qname, $s ) ) {
			return true; // Unicode BMP
		}

		/*
		 * Maybe the tests above failed because s includes surrogate pairs
		 * Most likely, though, they failed for some more basic syntax problem
		 */
		if ( !preg_match( self::pattern_has_surrogates, $s ) ) {
			return false;
		}

		/* Is the string a valid name if we allow surrogates? */
		if ( !preg_match( self::pattern_surrogate_qname, $s ) ) {
			return false;
		}

		/* Finally, are the surrogates all correctly paired up? */
		$matches_chars = [];
		$matches_pairs = [];

		$ret0 = preg_match( self::pattern_surrogate_chars, $s, $matches_chars );
		$ret1 = preg_match( self::pattern_surrogate_pairs, $s, $matches_pairs );

		return ( $ret0 && $ret1 ) && ( ( 2 * count( $matches_pairs ) ) === count( $matches_chars ) );
	}

	/**
	 * Validate and extract a namespace and qualifiedName
	 *
	 * Used to map (namespace, qualifiedName) => (namespace, prefix, localName)
	 *
	 * spec https://dom.spec.whatwg.org/#validate-and-extract
	 *
	 * @param ?string $ns
	 * @param string $qname
	 * @param ?string &$prefix reference (will be NULL or contain prefix string)
	 * @param ?string &$lname reference (will be qname or contain lname string)
	 * @return void
	 * @throws DOMException("NamespaceError")
	 */
	public static function validate_and_extract( ?string $ns, string $qname, ?string &$prefix, ?string &$lname ): void {
		/*
		 * See https://github.com/whatwg/dom/issues/671
		 * and https://github.com/whatwg/dom/issues/319
		 */
		if ( !self::is_valid_xml_qname( $qname ) ) {
			Util::error( "InvalidCharacterError" );
		}

		if ( $ns === "" ) {
			$ns = null; /* Per spec */
		}

		$pos = strpos( $qname, ':' );
		if ( $pos === false ) {
			$prefix = null;
			$lname = $qname;
		} else {
			$prefix = substr( $qname, 0, $pos );
			$lname  = substr( $qname, $pos + 1 );
		}

		if ( $prefix !== null && $ns === null ) {
			Util::error( "NamespaceError" );
		}
		if ( $prefix === "xml" && $namespace !== Util::NAMESPACE_XML ) {
			Util::error( "NamespaceError" );
		}
		if ( ( $prefix === "xmlns" || $qname === "xmlns" ) && $ns !== Util::NAMESPACE_XMLNS ) {
			Util::error( "NamespaceError" );
		}
		if ( $ns === Util::NAMESPACE_XMLNS && !( $prefix === "xmlns" || $qname === "xmlns" ) ) {
			Util::error( "NamespaceError" );
		}
	}
}
