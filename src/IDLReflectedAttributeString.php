<?php

declare( strict_types = 1 );
// phpcs:disable MediaWiki.Commenting.FunctionComment.MissingDocumentationPrivate
// phpcs:disable MediaWiki.Commenting.FunctionComment.MissingDocumentationPublic
// phpcs:disable MediaWiki.Commenting.PropertyDocumentation.MissingDocumentationProtected
// phpcs:disable PSR2.Classes.PropertyDeclaration.Underscore

namespace Wikimedia\Dodo;

/*
 * If a reflecting IDL attribute is a DOMString attribute whose content
 * attribute is an enumerated attribute, and the IDL attribute is limited
 * to only known values, then, on getting, the IDL attribute must return
 * the conforming value associated with the state the attribute is in
 * (in its canonical case), if any, or the empty string if the attribute
 * is in a state that has no associated keyword value or if the attribute
 * is not in a defined state (e.g. the attribute is missing and there is
 * no missing value default). On setting, the content attribute must be
 * set to the specified new value.
 */
class IDLReflectedAttributeString {
	protected $_elem = null;
	protected $_name = null;
	protected $_treat_null_as_empty = true;

	public function __construct( Element $elem, $spec ) {
		$this->_elem = $elem;
		$this->_name = $spec['name'];

		if ( isset( $spec['is_nullable'] ) ) {
			$this->_treat_null_as_empty = $spec['is_nullable'];
		}
	}

	public function get() {
		return $this->_elem->getAttribute( $this->_name ) ?? '';
	}

	public function set( $value ) {
		if ( $value === null && $this->_treat_null_as_empty ) {
			$value = '';
		}
		return $this->_elem->setAttribute( $this->_name, $value );
	}
}
