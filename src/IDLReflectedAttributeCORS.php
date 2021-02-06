<?php

declare( strict_types = 1 );

namespace Wikimedia\Dodo;

/**
 * Parent constructor in ReflectedAttribute is fine, no need to override
 * ReflectedAttribute exposes two protected values, $element and $attributeName
 */
class IDLReflectedAttributeCORS extends ReflectedAttribute {

	/**
	 * @return string|null
	 */
	public function get() {
		$v = $this->element->getAttribute( $this->attributeName );
		if ( $v === null ) {
			return null;
		}
		if ( strtolower( $v ) === 'use-credentials' ) {
			return 'use-credentials';
		}
		return 'anonymous';
	}

	/**
	 * @param mixed $value
	 */
	public function set( $value = null ) {
		if ( $value === null ) {
			$this->element->removeAttribute( $this->attributeName );
		} else {
			$this->element->setAttribute( $this->attributeName, $value );
		}
	}
}
