<?php

declare( strict_types = 1 );

namespace Wikimedia\Dodo;

/**
 * Abstract class for the different attribute types.
 * $element and $attributeName are protected for use in subclasses
 *
 * @author DannyS712
 */
abstract class ReflectedAttribute {
	/** @var Element */
	protected $element;

	/** @var string */
	protected $attributeName;

	/**
	 * @param Element $elem
	 * @param array $spec
	 */
	public function __construct( Element $elem, $spec ) {
		$this->element = $elem;
		$this->attributeName = $spec['name'];
	}

	/**
	 * @return mixed
	 */
	abstract public function get();

	/**
	 * Some of the attribute classes return something here, others don't - why?
	 *
	 * @param mixed $value
	 */
	abstract public function set( $value );

	// Factory methods

	/**
	 * Get multiple reflected attributes, based on a spec
	 *
	 * @param HTMLImgElement $owner
	 * @param array $specs
	 * @return ReflectedAttribute[]
	 */
	final public static function buildAttributes( HTMLImgElement $owner, array $specs ) : array {
		$attribs = [];
		foreach ( $specs as $name => $spec ) {
			if ( !is_array( $spec ) ) {
				$spec = [ 'type' => $spec, 'name' => $name ];
			}
			if ( !isset( $spec['name'] ) ) {
				$spec['name'] = $name;
			}
			$attribs[$name] = self::factory( $owner, $spec );
		}
		return $attribs;
	}

	/******************************************************************************
	 * Reflecting content attributes in IDL attributes
	 *
	 * http://html.spec.whatwg.org/#reflecting-content-attributes-in-idl-attributes
	 *
	 * From the spec:
	 * "Some IDL attributes are defined to reflect a particular content attribute.
	 * This means that on getting, the IDL attribute returns the current value of
	 * the content attribute, and on setting, the IDL attribute changes the value
	 * of the content attribute to the given value."
	 *
	 * Many HTML Elements have well-defined interfaces expressed as attributes.
	 * These attributes may be typed, have an enumerated set of allowed values,
	 * have default values, and so on.
	 *
	 * This family of classes allows us to implement these reflected attributes.
	 *
	 * USAGE:
	 * For each specialized attribute on an Element, build a reflected
	 * attribute object and add its access functions to the magic
	 * __get() and/or __set() functions.
	 *
	 * (As of PHP 7 this is the only way to implement implicit accessors,
	 * which must be done to comply with spec.)
	 *
	 * The switch in Element::__get()/Element::__set() will call
	 * ReflectedAttr::get or ReflectedAttr::set after looking the
	 * object up in a table or as a class member like
	 * Element::$__attr_<attrname>.
	 *
	 * @param HTMLImgElement $owner
	 * @param array $spec
	 * @return ReflectedAttribute one of the different subclasses
	 */
	final public static function factory( $owner, $spec ) {
		if ( is_array( $spec['type'] ) ) {
			return new IDLReflectedAttributeEnumerated( $owner, $spec );
		}
		switch ( $spec['type'] ) {
			case 'CORS':
				return new IDLReflectedAttributeCORS( $owner, $spec );
			case 'URL':
				return new IDLReflectedAttributeURL( $owner, $spec );
			case 'boolean':
				return new IDLReflectedAttributeBoolean( $owner, $spec );
			case 'number':
			case 'long':
			case 'unsigned long':
			case 'limited unsigned long with fallback':
				return new IDLReflectedAttributeNumeric( $owner, $spec );
			case 'function':
				// FIXME IDLReflectedAttributeFunction does not exist
				// @phan-suppress-next-line PhanUndeclaredClassMethod
				return new IDLReflectedAttributeFunction( $owner, $spec );
			case 'string':
			default:
				return new IDLReflectedAttributeString( $owner, $spec );
		}
	}
}
