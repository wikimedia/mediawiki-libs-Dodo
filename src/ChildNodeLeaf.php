<?php

declare( strict_types = 1 );

// phpcs:disable MediaWiki.NamingConventions.LowerCamelFunctionsName.FunctionName

namespace Wikimedia\Dodo;

/**
 * We have to use this because PHP is single-inheritance, so DocumentType
 * can't inherit from ChildNode and Leaf at once.
 *
 * This trait selectively overrides methods inherited from Node,
 * for Node subclasses that can never have children, such as those
 * derived from the abstract CharacterData class.
 *
 * @property NodeList|null $_childNodes
 */
trait ChildNodeLeaf {

	/** @return bool */
	public function hasChildNodes(): bool {
		return false;
	}

	/** @return ?Node always null */
	public function firstChild(): ?Node {
		return null;
	}

	/** @return ?Node always null */
	public function lastChild(): ?Node {
		return null;
	}

	/**
	 * @param Node $node
	 * @param ?Node $refChild
	 * @return ?Node always null
	 */
	public function insertBefore( Node $node, ?Node $refChild ) : ?Node {
		Util::error( "NotFoundError" );
		return null;
	}

	/**
	 * @param Node $node
	 * @param ?Node $refChild
	 * @return ?Node always null
	 */
	public function replaceChild( Node $node, ?Node $refChild ) : ?Node {
		Util::error( "HierarchyRequestError" );
		return null;
	}

	/**
	 * @param ChildNode $node
	 * @return ?Node always null
	 */
	public function removeChild( ChildNode $node ) : ?Node {
		Util::error( "NotFoundError" );
		return null;
	}

	/**
	 * Needed to override method in Node class, does nothing
	 */
	public function __remove_children() {
		/* no-op */
	}

	/**
	 * @return ?NodeList always a NodeList
	 */
	public function childNodes(): ?NodeList {
		if ( $this->_childNodes === null ) {
			$this->_childNodes = new NodeList();
		}
		return $this->_childNodes;
	}
}
