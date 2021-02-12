<?php

declare( strict_types = 1 );
// phpcs doesn't like @copyDoc, apparently:
// phpcs:disable MediaWiki.Commenting.FunctionAnnotations.UnrecognizedAnnotation
// phpcs:disable MediaWiki.Commenting.FunctionComment.MissingParamTag
// phpcs:disable MediaWiki.Commenting.FunctionComment.MissingReturn
// phpcs:disable MediaWiki.NamingConventions.LowerCamelFunctionsName.FunctionName

namespace Wikimedia\Dodo;

/*
 * This trait selectively overrides Node, providing an alternative
 * (more performant) base class for Node subclasses that can never
 * have children, such as those derived from the abstract CharacterData
 * class.
 */
trait Leaf /* domino helper */ {

	/**
	 * @copyDoc Node::hasChildNodes()
	 */
	final public function hasChildNodes(): bool {
		return false;
	}

	/**
	 * @copyDoc Node::getFirstChild()
	 */
	final public function getFirstChild() {
		return null;
	}

	/**
	 * @copyDoc Node::getLastChild()
	 */
	final public function getLastChild() {
		return null;
	}

	/**
	 * @copyDoc Node::insertBefore()
	 */
	final public function insertBefore( $node, $refChild ) {
		Util::error( "NotFoundError" );
	}

	/**
	 * @copyDoc Node::replaceChild()
	 */
	final public function replaceChild( $node, $refChild ) {
		Util::error( "HierarchyRequestError" );
	}

	/**
	 * @copyDoc Node::removeChild()
	 */
	final public function removeChild( $node ) {
		Util::error( "NotFoundError" );
	}

	/**
	 * @copyDoc Node::__remove_children()
	 */
	final public function __remove_children() {
		/* no-op */
	}

	/**
	 * @copyDoc Node::getChildNodes()
	 */
	final public function getChildNodes() {
		'@phan-var Node $this'; // @var Node $this
		if ( $this->_childNodes === null ) {
			$this->_childNodes = new NodeList();
		}
		return $this->_childNodes;
	}
}
