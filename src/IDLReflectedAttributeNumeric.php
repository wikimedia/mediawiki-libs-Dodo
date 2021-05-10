<?php

declare( strict_types = 1 );
// @phan-file-suppress PhanPluginDuplicateExpressionAssignmentOperation
// @phan-file-suppress PhanSuspiciousValueComparison
// @phan-file-suppress PhanUndeclaredProperty
// phpcs:disable MediaWiki.Commenting.PropertyDocumentation.MissingDocumentationPublic
// phpcs:disable PSR2.Classes.PropertyDeclaration.Underscore

namespace Wikimedia\Dodo;

use Wikimedia\Dodo\Internal\Util;

// See http://www.whatwg.org/specs/web-apps/current-work/#reflect
//
// defval is the default value. If it is a function, then that function
// will be invoked as a method of the element to obtain the default.
// If no default is specified for a given attribute, then the default
// depends on the type of the attribute, but since this function handles
// 4 integer cases, you must specify the default value in each call
//
// min and max define a valid range for getting the attribute.
//
// setmin defines a minimum value when setting.  If the value is less
// than that, then throw INDEX_SIZE_ERR.
//
// Conveniently, JavaScript's parseInt function appears to be
// compatible with HTML's 'rules for parsing integers'

/*
 * define({
 *   tag: 'progress',
 *   ctor: function HTMLProgressElement(doc, localName, prefix) {
 *     HTMLFormElement.call(this, doc, localName, prefix);
 *   },
 *   props: formAssociatedProps,
 *   attributes: {
 *     max: {type: 'number', subtype: 'float', 'default': 1.0, 'min': 0}
 *   }
 * });
 */

/**
 * ReflectedAttribute exposes two protected values, $element and $attributeName
 *
 * TODO clean this up a lot more
 */
class IDLReflectedAttributeNumeric extends ReflectedAttribute {
	public $_subtype;

	public $_default;
	public $_default_value;
	public $_max = null;
	public $_min = null;
	public $_setmin = null;

	/**
	 * @param Element $elem
	 * @param array $spec
	 */
	public function __construct( Element $elem, $spec ) {
		parent::__construct( $elem, $spec );

		$this->_type = $spec['type'] ?? 'number';
		$this->_subtype = $spec['subtype'] ?? 'integer';
		$this->_setmin = $spec['setmin'] ?? null;

		if ( is_callable( $spec['default'] ) ) {
			$this->_default = $spec['default'];
		} elseif ( is_numeric( $spec['default'] ) ) {
			$this->_default_value = $spec['default'];
			$this->_default = function ( $ctx ) {
				return $ctx->_default_value;
			};
		}

		if ( isset( $spec['min'] ) ) {
			$this->_min = $spec['min'];
		} else {
			switch ( $spec['type'] ) {
			case 'unsigned long':
				$this->_min = 0;
				break;
			case 'long':
				$this->_min = -0x80000000;
				break;
			case 'limited unsigned long with fallback':
				$this->_min = 1;
				break;
			}
		}

		if ( isset( $spec['max'] ) ) {
			$this->_max = $spec['max'];
		} else {
			switch ( $spec['type'] ) {
			case 'unsigned long':
			case 'long':
			case 'limited unsigned long with fallback':
				$this->_max = 0x7FFFFFFF;
				break;
			}
		}
	}

	/**
	 * @return mixed
	 */
	public function get() {
		/* TODO: This was the fast path _getattr() */
		$v = $this->element->getAttribute( $this->attributeName );

		$n = ( $this->_subtype === 'float' ) ? floatval( $v ) : intval( $v, 10 );

		if ( $v === null
			 || !is_finite( $n )
			 || ( $this->_min !== null && $n < $this->_min )
			 || ( $this->_max !== null && $n > $this->_max )
		) {
			return ( $this->_default )( $this );
		}

		switch ( $this->_type ) {
			case 'unsigned long':
			case 'long':
			case 'limited unsigned long with fallback':
				if ( !preg_match( '/^[ \t\n\f\r]*[-+]?[0-9]/', $v ) ) {
					return ( $this->_default )( $this );
				}
				break;
			default:
				$n = $n | 0;
				break;
		}

		return $n;
	}

	/**
	 * TODO why do some setters return?
	 *
	 * @param mixed $v
	 * @return mixed
	 */
	public function set( $v ) {
		if ( !$this->_subtype === 'float' ) {
			$v = floor( $v );
		}

		if ( $this->_setmin !== null && $v < $this->_setmin ) {
			Util::error( "IndexSizeError", $this->_name . ' set to ' . $v );
		}

		switch ( $this->_type ) {
			case 'unsigned_long':
				if ( $v < 0 || $v > 0x7FFFFFFF ) {
					$v = ( $this->_default )( $this );
				} else {
					$v = $v | 0;
				}
				break;
			case 'limited unsigned long with fallback':
				if ( $v < 1 || $v > 0x7FFFFFFF ) {
					$v = ( $this->_default )( $this );
				} else {
					$v = $v | 0;
				}
			case 'long':
				if ( $v < -0x80000000 || $v > 0x7FFFFFFF ) {
					$v = ( $this->_default )( $this );
				} else {
					$v = $v | 0;
				}
		}

		return $this->element->setAttribute( $this->attributeName, strval( $v ) );
	}
}
