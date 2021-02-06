<?php

declare( strict_types = 1 );
// phpcs:disable Generic.Files.LineLength.TooLong
// phpcs:disable MediaWiki.Commenting.FunctionComment.MissingDocumentationPrivate
// phpcs:disable MediaWiki.Commenting.FunctionComment.MissingDocumentationPublic
// phpcs:disable MediaWiki.Commenting.FunctionComment.MissingParamTag
// phpcs:disable MediaWiki.Commenting.FunctionComment.WrongStyle
// phpcs:disable MediaWiki.NamingConventions.LowerCamelFunctionsName.FunctionName
// phpcs:disable PSR2.Classes.PropertyDeclaration.Underscore
// phpcs:disable PSR2.Methods.MethodDeclaration.Underscore
// phpcs:disable Squiz.Scope.MethodScope.Missing

namespace Wikimedia\Dodo;

/******************************************************************************
 * ChildNode.php
 * -------------
 */

/**
 * DOM-LS
 * Node objects that can have a parent must
 * implement the ChildNode class. These include:
 *
 *      Element
 *      CharacterData
 *      DocumentType
 *
 * The additional methods defined by this class
 * are practical conveniences, but are not really
 * required to get full DOM functionality.
 *
 * TODO
 * That being the case, perhaps DODO should choose
 * not to implement them.
 */
abstract class ChildNode extends Node {
	static function _fragment_from_arguments( $document, array $args ) {
		$fragment = $document->createDocumentFragment();

		foreach ( $args as $item ) {
			if ( !( $item instanceof Node ) ) {
				/* In particular, you can't have NULLs */
				$item = $document->createTextNode( strval( $item ) );
			}

			$fragment->appendChild( $item );
		}

		return $fragment;
	}

	public function __construct() {
		parent::__construct();
	}

	/**
	 * Insert any number of Nodes or
	 * DOMStrings after $this.
	 *
	 * NOTE
	 * DOMStrings are inserted as
	 * equivalent Text nodes.
	 *
	 * TODO: after and before()
	 * are very very similar and
	 * could probably be factored
	 * nicely..
	 */
	public function after( ...$args /* DOMStrings and/or Nodes */ ) {
		$parentNode = $this->_parentNode;
		$nextSibling = $this->nextSibling();

		if ( $parentNode === null ) {
			/*
			 * If $this has no parent,
			 * then it is not actually
			 * part of a document, and
			 * according to DOM-LS,
			 * this method has no effect.
			 */
			return;
		}

		// Find "viable next sibling"; that is, next one not in $args
		while ( $nextSibling !== null && in_array( $nextSibling, $args, true ) ) {
			$nextSibling = $nextSibling->nextSibling();
		}
		// ok, parent and sibling are saved away since this node could itself
		// appear in $args and we're about to move $args to a document fragment.

		/*
		 * Turn the arguments into
		 * a DocumentFragment.
		 */
		$frag = self::_fragment_from_arguments( $this->__node_document(), $args );

		/*
		 * Insert the DocumentFragment
		 * at the determined location.
		 */
		$parentNode->insertBefore( $frag, $nextSibling );
	}

	/**
	 * Insert any number of Nodes or
	 * DOMStrings after $this.
	 *
	 * NOTE
	 * DOMStrings are inserted as
	 * equivalent Text nodes.
	 */
	public function before( ...$args /* DOMStrings and/or Nodes */ ) {
		$parentNode = $this->_parentNode;
		$prevSibling = $this->previousSibling();

		if ( $this->_parentNode === null ) {
			/*
			 * If $this has no parent,
			 * then it is not actually
			 * part of a document, and
			 * according to DOM-LS,
			 * this method has no effect.
			 */
			return;
		}
		// Find "viable prev sibling"; that is, prev one not in $args
		while ( $prevSibling !== null && in_array( $prevSibling, $args, true ) ) {
			$prevSibling = $prevSibling->previousSibling();
		}
		// ok, parent and sibling are saved away since this node could itself
		// appear in $args and we're about to move $args to a document fragment.

		/*
		 * Turn the arguments into
		 * a DocumentFragment.
		 */
		$frag = self::_fragment_from_arguments( $this->__node_document(), $args );

		$nextSibling = $prevSibling ? $prevSibling->nextSibling() : $parentNode->firstChild();

		/*
		 * Insert the DocumentFragment
		 * at the determined location.
		 */
		$parentNode->insertBefore( $frag, $nextSibling );
	}

	/*
	 * Remove $this from its parent.
	 */
	public function remove() {
		if ( $this->_parentNode === null ) {
			/*
			 * If $this has no parent,
			 * according to DOM-LS,
			 * this method has no effect.
			 */
			return;
		}

		$doc = $this->__node_document();
		// @phan-suppress-next-line PhanRedundantCondition phan is probably right to complain here: "all nodes have a node document"
		if ( $doc ) {
			/*
			 * Un-associate $this
			 * with its document,
			 * if it has one.
			 */
			if ( $this->__is_rooted() ) {
				$doc->__mutate_remove( $this );
				$doc->__uproot();
			}
		}

		/*
		 * Remove this node from its parents array of children
		 * and update the structure id for all ancestors
		 */
		$this->_remove();

		/* Forget this node's parent */
		$this->_parentNode = null;
	}

	/**
	 * Remove this node w/o uprooting or sending mutation events
	 * This is like a 'soft remove' - it's used in whatwg stuff.
	 */
	protected function _remove() {
		if ( $this->_parentNode === null ) {
			return;
		}

		$parent = $this->_parentNode;

		if ( $parent->_childNodes !== null ) {
			array_splice( $parent->_childNodes, $this->__sibling_index(), 1 );
		} elseif ( $parent->_firstChild === $this ) {
			$parent->_firstChild = $this->nextSibling();
		}

		LinkedList::ll_remove( $this );
	}

	/**
	 * Replace this node with the nodes or strings provided as arguments.
	 */
	public function replaceWith( ...$args /* Nodes or DOMStrings */ ) {
		$parentNode = $this->parentNode();
		$nextSibling = $this->nextSibling();

		if ( $this->parentNode() === null ) {
			return;
		}

		/*
		 * Find "viable next sibling"; that is, next one
		 * not in $arguments
		 */
		while ( $nextSibling !== null && in_array( $nextSibling, $args, true ) ) {
			$nextSibling = $nextSibling->nextSibling();
		}

		/*
		 * ok, parent and sibling are saved away since this node
		 * could itself appear in $arguments and we're about to
		 * move $arguments to a document fragment.
		 */
		$frag = self::_fragment_from_arguments( $this->__node_document(), $args );

		if ( $this->_parentNode === $parentNode ) {
			$parentNode->replaceChild( $frag, $this );
		} else {
			/* `this` was inserted into docFrag */
			$parentNode->insertBefore( $frag, $nextSibling );
		}
	}
}
