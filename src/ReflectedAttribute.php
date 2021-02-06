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
}
