<?php

declare( strict_types = 1 );

namespace Wikimedia\Dodo;

class XMLDocument extends Document implements \Wikimedia\IDLeDOM\XMLDocument {

	use \Wikimedia\IDLeDOM\Helper\XMLDocument;

	/**
	 * @param Document $originDoc
	 * @param string $contentType
	 */
	public function __construct( Document $originDoc, string $contentType ) {
		parent::__construct( $originDoc, 'xml', null );
		$this->_contentType = $contentType;
	}
}