<?php

declare( strict_types = 1 );

namespace Wikimedia\Dodo;

use Wikimedia\Dodo\Internal\UnimplementedTrait;

/**
 * DocumentFragment
 */
class DocumentFragment extends ContainerNode implements \Wikimedia\IDLeDOM\DocumentFragment {
	// DOM mixins
	use NonElementParentNode;
	use ParentNode;

	// Stub out methods not yet implemented.
	use \Wikimedia\IDLeDOM\Stub\DocumentFragment;
	use UnimplementedTrait;

	// Helper functions from IDLeDOM
	use \Wikimedia\IDLeDOM\Helper\DocumentFragment;

	/** @inheritDoc */
	public function __construct( Document $nodeDocument ) {
		parent::__construct( $nodeDocument );
	}

	/**
	 * @inheritDoc
	 */
	final public function getNodeType() : int {
		return Node::DOCUMENT_FRAGMENT_NODE;
	}

	/**
	 * @inheritDoc
	 */
	final public function getNodeName() : string {
		return "#document-fragment";
	}

	/** @return DocumentFragment */
	protected function _subclassCloneNodeShallow(): Node {
		return new DocumentFragment( $this->_nodeDocument );
	}

	/** @inheritDoc */
	protected function _subclassIsEqualNode( Node $node ): bool {
		// Any two document fragments are shallowly equal.
		// Node.isEqualNode() will test their children for equality
		return true;
	}

	// Non-standard, but useful (github issue #73)

	/** @return string the inner HTML of this DocumentFragment */
	public function getInnerHTML() : string {
		return $this->_node_serialize();
	}

	/** @return string the outer HTML of this DocumentFragment */
	public function getOuterHTML(): string {
		return $this->_node_serialize();
	}
}
