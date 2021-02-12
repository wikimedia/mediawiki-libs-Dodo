<?php

declare( strict_types = 1 );

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
 *
 * Parent constructor in ReflectedAttribute is fine, no need to override
 * ReflectedAttribute exposes two protected values, $element and $attributeName
 */
class IDLReflectedAttributeURL extends ReflectedAttribute {

	/**
	 * @return mixed
	 */
	public function get() {
		$v = $this->element->getAttribute( $this->attributeName );

		if ( $v === null ) {
			return '';
		}

		// FIXME $this->element should always be truthy?
		if ( $this->element ) {
			$url = new URL( $this->element->__node_document()->getURL() );
			return $url->resolve( $v );
		} else {
			return $v;
		}
	}

	/**
	 * TODO why do some setters return?
	 *
	 * @param mixed $value
	 * @return mixed
	 */
	public function set( $value ) {
		return $this->element->setAttribute( $this->attributeName, $value );
	}
}
