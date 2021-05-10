<?php

declare( strict_types = 1 );

namespace Wikimedia\Dodo;

class XMLDocument extends Document implements \Wikimedia\IDLeDOM\XMLDocument {

	use \Wikimedia\IDLeDOM\Helper\XMLDocument;

	/**
	 * @param ?Document $contextObject
	 * @param string $contentType
	 */
	public function __construct( ?Document $contextObject, string $contentType ) {
		parent::__construct( $contextObject, 'xml', null );
		$this->_contentType = $contentType;
	}
}
