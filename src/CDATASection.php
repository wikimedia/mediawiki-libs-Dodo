<?php

declare( strict_types = 1 );

namespace Wikimedia\Dodo;

class CDATASection extends Text implements \Wikimedia\IDLeDOM\CDATASection {
	// Stub out methods not yet implemented.
	use \Wikimedia\IDLeDOM\Stub\CDATASection;
	use UnimplementedTrait;

	// Helper functions from IDLeDOM
	use \Wikimedia\IDLeDOM\Helper\CDATASection;

	/**
	 * @inheritDoc
	 */
	final public function getNodeType() : int {
		return Node::CDATA_SECTION_NODE;
	}

	/**
	 * @inheritDoc
	 */
	public function getNodeName() : string {
		return "#cdata-section";
	}
}
