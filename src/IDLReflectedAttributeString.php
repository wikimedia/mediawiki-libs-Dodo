<?php

declare( strict_types = 1 );
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
 *
 * ReflectedAttribute exposes two protected values, $element and $attributeName
 */
class IDLReflectedAttributeString extends ReflectedAttribute {
	/** @var bool */
	private $treatNullAsEmpty = true;

	/**
	 * @param Element $elem
	 * @param array $spec
	 */
	public function __construct( Element $elem, $spec ) {
		parent::__construct( $elem, $spec );

		if ( isset( $spec['is_nullable'] ) ) {
			$this->treatNullAsEmpty = $spec['is_nullable'];
		}
	}

	/**
	 * @return mixed
	 */
	public function get() {
		return $this->element->getAttribute( $this->attributeName ) ?? '';
	}

	/**
	 * TODO why do some setters return?
	 *
	 * @param mixed $value
	 * @return mixed
	 */
	public function set( $value ) {
		if ( $value === null && $this->treatNullAsEmpty ) {
			$value = '';
		}
		return $this->element->setAttribute( $this->attributeName, $value );
	}
}
