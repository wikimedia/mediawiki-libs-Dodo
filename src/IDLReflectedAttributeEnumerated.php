<?php

declare( strict_types = 1 );
// @phan-file-suppress PhanUndeclaredProperty
// phpcs:disable MediaWiki.Commenting.FunctionComment.MissingDocumentationPublic
// phpcs:disable MediaWiki.Commenting.PropertyDocumentation.MissingDocumentationProtected
// phpcs:disable PSR2.Classes.PropertyDeclaration.Underscore

namespace Wikimedia\Dodo;

/*
 * If a reflecting IDL attribute is a nullable DOMString attribute whose
 * content attribute is an enumerated attribute, then, on getting, if the
 * corresponding content attribute is in its missing value default then the
 * IDL attribute must return null, otherwise, the IDL attribute must return
 * the conforming value associated with the state the attribute is in
 * (in its canonical case). On setting, if the new value is null, the content
 * attribute must be removed, and otherwise, the content attribute must be set
 * to the specified new value.
 *
 * array(
 *      'name' => 'foo'
 *      'type' => array('ltr', 'rtl', 'auto'),
 *      ---------------------------------------- optional
 *      'missing_value_default' => ''
 *      'invalid_value_default' => ''
 *      'is_nullable' => [true|false]
 *      'alias' => 'fooalias'
 * )
 *
 *  'type' => array(array('value'=>'ltr', 'alias'=>'a'), 'auto'),
 */
/* TODO: WE do not implement nullable enumerated attributes yet */
class IDLReflectedAttributeEnumerated {
	protected $_valid = [];
	protected $_missing_value_default = null;
	protected $_invalid_value_default = null;
	protected $_is_nullable = false;

	public function __construct( Element $elem, $spec ) {
		$this->_elem = $elem;
		$this->_name = $spec['name'];
		$this->_is_nullable = $spec['is_nullable'] ?? false;

		foreach ( $spec['type'] as $t ) {
			$this->_valid[$t['value'] ?? $t] = $t['alias'] ?? $t;
		}

		$this->_missing_value_default = $spec['missing_value_default'] ?? null;
		$this->_invalid_value_default = $spec['invalid_value_default'] ?? null;
	}

	public function get() {
		/* TODO: used to be _getattr fast path */
		$v = $this->_elem->getAttribute( $this->_name );

		if ( $v === null ) {
			return $this->_default_if_missing;
		}

		if ( isset( $this->_valid[strtolower( $v )] ) ) {
			return $v;
		}

		return $this->_default_if_invalid ?? $v;
	}

	public function set( $value ) {
		/* TODO: used to be _setattr fast path */
		return $this->_elem->setAttribute( $this->_name, $value );
	}
}
