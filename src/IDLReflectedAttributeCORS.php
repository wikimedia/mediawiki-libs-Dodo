<?php

declare( strict_types = 1 );
// phpcs:disable MediaWiki.Commenting.FunctionComment.MissingDocumentationPrivate
// phpcs:disable MediaWiki.Commenting.FunctionComment.MissingDocumentationPublic
// phpcs:disable MediaWiki.Commenting.PropertyDocumentation.MissingDocumentationProtected
// phpcs:disable MediaWiki.NamingConventions.LowerCamelFunctionsName.FunctionName
// phpcs:disable PSR2.Classes.PropertyDeclaration.Underscore

namespace Wikimedia\Dodo;

class IDLReflectedAttributeCORS {
	protected $_elem = null;
	protected $_name = null;

	public function __construct( Element $elem, $spec ) {
		$this->_elem = $elem;
		$this->_name = $spec['name'];
	}

	public function get() {
		$v = $this->_elem->getAttribute( $this->_name );
		if ( $v === null ) {
			return null;
		}
		if ( strtolower( $v ) === 'use-credentials' ) {
			return 'use-credentials';
		}
		return 'anonymous';
	}

	public function set( $value = null ) {
		if ( $value === null ) {
			$this->_elem->removeAttribute( $this->_name );
		} else {
			$this->_elem->setAttribute( $this->_name, $value );
		}
	}
}
