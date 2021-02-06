<?php

declare( strict_types = 1 );
// phpcs:disable MediaWiki.Commenting.FunctionComment.MissingDocumentationPublic
// phpcs:disable MediaWiki.NamingConventions.LowerCamelFunctionsName.FunctionName

namespace Wikimedia\Dodo;

/*
 * We have to use this because PHP is single-inheritance, so DocumentType
 * can't inherit from ChildNode and Leaf at once.
 *
 * We could use traits...................nah
 *
 * This class selectively overrides Node, providing an alternative
 * (more performant) base class for Node subclasses that can never
 * have children, such as those derived from the abstract CharacterData
 * class.
 */
abstract class ChildNodeLeaf extends ChildNode {

	final public function hasChildNodes(): bool {
		return false;
	}

	final public function firstChild(): ?Node {
		return null;
	}

	final public function lastChild(): ?Node {
		return null;
	}

	final public function insertBefore( Node $node, ?Node $refChild ):?Node {
		Util::error( "NotFoundError" );
		return null;
	}

	final public function replaceChild( Node $node, ?Node $refChild ):?Node {
		Util::error( "HierarchyRequestError" );
		return null;
	}

	final public function removeChild( ChildNode $node ):?Node {
		Util::error( "NotFoundError" );
		return null;
	}

	final public function __remove_children() {
		/* no-op */
	}

	final public function childNodes(): ?NodeList {
		if ( $this->_childNodes === null ) {
			$this->_childNodes = new NodeList();
		}
		return $this->_childNodes;
	}
}
