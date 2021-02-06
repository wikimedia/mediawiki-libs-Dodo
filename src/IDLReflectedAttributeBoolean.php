<?php

declare( strict_types = 1 );
// phpcs:disable MediaWiki.Commenting.FunctionComment.MissingDocumentationPrivate
// phpcs:disable MediaWiki.Commenting.FunctionComment.MissingDocumentationPublic
// phpcs:disable MediaWiki.Commenting.PropertyDocumentation.MissingDocumentationProtected
// phpcs:disable PSR2.Classes.PropertyDeclaration.Underscore

namespace Wikimedia\Dodo;

class IDLReflectedAttributeBoolean {
	protected $_elem = null;
	protected $_name = null;

	public function __construct( Element $elem, $spec ) {
		$this->_elem = $elem;
		$this->_name = $spec['name'];
	}

	public function get() {
		return $this->_elem->hasAttribute( $this->_name );
	}

	public function set( $value ) {
		if ( $value ) {
			$this->_elem->setAttribute( $this->_name, '' );
		} else {
			$this->_elem->removeAttribute( $this->_name );
		}
	}
}
