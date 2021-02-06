<?php

declare( strict_types = 1 );

namespace Wikimedia\Dodo;

/**
 * Parent constructor in ReflectedAttribute is fine, no need to override
 * ReflectedAttribute exposes two protected values, $element and $attributeName
 */
class IDLReflectedAttributeBoolean extends ReflectedAttribute {

	/**
	 * @return bool
	 */
	public function get() {
		return $this->element->hasAttribute( $this->attributeName );
	}

	/**
	 * @param mixed $value
	 */
	public function set( $value ) {
		if ( $value ) {
			$this->element->setAttribute( $this->attributeName, '' );
		} else {
			$this->element->removeAttribute( $this->attributeName );
		}
	}
}
