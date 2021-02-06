<?php

declare( strict_types = 1 );
// phpcs:disable MediaWiki.Commenting.FunctionComment.MissingDocumentationPrivate
// phpcs:disable MediaWiki.Commenting.FunctionComment.MissingDocumentationPublic
// phpcs:disable MediaWiki.Commenting.FunctionComment.WrongStyle
// phpcs:disable MediaWiki.Commenting.PropertyDocumentation.MissingDocumentationPrivate
// phpcs:disable MediaWiki.Commenting.PropertyDocumentation.MissingDocumentationProtected
// phpcs:disable MediaWiki.NamingConventions.LowerCamelFunctionsName.FunctionName
// phpcs:disable PSR2.Classes.PropertyDeclaration.Underscore
// phpcs:disable PSR2.Methods.MethodDeclaration.Underscore

namespace Wikimedia\Dodo;

/*
 * If a reflecting IDL attribute is a USVString attribute whose content
 * attribute is defined to contain a URL, then on getting, if the content
 * attribute is absent, the IDL attribute must return the empty string.
 * Otherwise, the IDL attribute must parse the value of the content attribute
 * relative to the element's node document and if that is successful, return
 * the resulting URL string. If parsing fails, then the value of the content
 * attribute must be returned instead, converted to a USVString. On setting,
 * the content attribute must be set to the specified new value.
 */
class IDLReflectedAttributeURL {
	protected $_elem = null;
	protected $_name = null;

	public function __construct( Element $elem, $spec ) {
		$this->_elem = $elem;
		$this->_name = $spec['name'];
	}

	public function get() {
		$v = $this->_elem->getAttribute( $this->_name );

		if ( $v === null ) {
			return '';
		}

		if ( $this->_elem ) {
			$url = new URL( $this->_elem->__node_document()->URL() );
			return $url->resolve( $v );
		} else {
			return $v;
		}
	}

	public function set( $value ) {
		return $this->_elem->setAttribute( $this->_name, $value );
	}
}
