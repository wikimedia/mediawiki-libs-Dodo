<?php

declare( strict_types = 1 );
// phpcs:disable MediaWiki.NamingConventions.LowerCamelFunctionsName.FunctionName
// phpcs:disable PSR2.Classes.PropertyDeclaration.Underscore
// phpcs:disable Squiz.PHP.NonExecutableCode.Unreachable

namespace Wikimedia\Dodo;

use ArrayObject;
use Exception;

/******************************************************************************
 * NamedNodeMap.php
 * ----------------
 * Implements a NamedNodeMap. Used to represent Element::attributes.
 *
 * NOTE: Why is it called NamedNodeMap?
 *
 *      NamedNodeMap has nothing to do with Nodes, it's a collection
 *      of Attrs. But once upon a time, an Attr was a type of Node called a
 *      NamedNode. But then DOM-4 came along and said that an Attr is no
 *      longer a subclass of Node. But then DOM-LS came and change it again,
 *      and said it was a subclass of Node. NamedNode was forgotten, but it
 *      lives on in this interface's name! How confusing!
 *
 * NOTE: This looks different from Domino.js!
 *
 *      In Domino.js, NamedNodeMap was only implemented to satisfy
 *      'instanceof' type-checking. Almost all of the methods were
 *      stubbed, except for 'length' and 'item'. The tables that
 *      stored an Element's attributes were located directly on the
 *      Element itself.
 *
 *      Because there are so many attribute handling methods on an
 *      Element, each with little differences, this meant replicating
 *      a bunch of the book-keeping inside those methods. The negative
 *      impact on code maintainability was pronounced, so the book-keeping
 *      was transferred to the NamedNodeMap itself, and its methods were
 *      properly implemented, which made it much easier to read and write
 *      the attribute methods on the Element class.
 *
 */
class NamedNodeMap extends ArrayObject {
	/**
	 * qname => Attr
	 *
	 * @var array entries are either Attr objects, or arrays of Attr objects on collisions
	 */
	private $__qname_to_attr = [];

	/**
	 * ns|lname => Attr
	 *
	 * @var Attr[]
	 */
	private $__lname_to_attr = [];

	/**
	 * ns|lname => index number
	 *
	 * @var int[]
	 */
	private $__lname_to_index = [];

	/* NOW IMPLEMENTED AS $this[], the default */
	// public $index_to_attr = array(); [> N => Attr <]

	/**
	 * DOM-LS associated element, defined in spec but not given property.
	 *
	 * @var ?Element
	 */
	public $_element = null;

	/**
	 * @param ?Element $element
	 */
	public function __construct( ?Element $element = null ) {
		$this->_element = $element;
	}

	/**********************************************************************
	 * Dodo INTERNAL BOOK-KEEPING
	 */

	/**
	 * @param Attr $a
	 */
	private function __append( Attr $a ) {
		$qname = $a->name();

		/* NO COLLISION */
		if ( !isset( $this->__qname_to_attr[$qname] ) ) {
			$this->__qname_to_attr[$qname] = $a;
			/* COLLISION */
		} else {
			if ( is_array( $this->__qname_to_attr[$qname] ) ) {
				$this->__qname_to_attr[$qname][] = $a;
			} else {
				$this->__qname_to_attr[$qname] = [
					$this->__qname_to_attr[$qname],
					$a
				];
			}
		}

		$key = $a->namespaceURI() . '|' . $a->localName();

		$this->__lname_to_attr[$key] = $a;
		$this->__lname_to_index[$key] = count( $this );
		$this[] = $a;
	}

	/**
	 * @param Attr $a
	 */
	private function __replace( Attr $a ) {
		$qname = $a->name();

		/* NO COLLISION */
		if ( !isset( $this->__qname_to_attr[$qname] ) ) {
			$this->__qname_to_attr[$qname] = $a;
			/* COLLISION */
		} else {
			if ( is_array( $this->__qname_to_attr[$qname] ) ) {
				$this->__qname_to_attr[$qname][] = $a;
			} else {
				$this->__qname_to_attr[$qname] = [
					$this->__qname_to_attr[$qname],
					$a
				];
			}
		}

		$key = $a->namespaceURI() . '|' . $a->localName();

		$this->__lname_to_attr[$key] = $a;
		$this[$this->__lname_to_index[$key]] = $a;
	}

	/**
	 * @param Attr $a
	 */
	private function __remove( Attr $a ) {
		$qname = $a->name();
		$key = $a->namespaceURI() . '|' . $a->localName();

		unset( $this->__lname_to_attr[$key] );
		$i = $this->__lname_to_index[$key];
		unset( $this->__lname_to_index[$key] );

		// XXX PORT FIX ME: array_splice doesn't work on ArrayObject
		// so either put back $index_to_array or else reimplement the
		// splice operation in terms of primitives.
		throw new Exception( "fixme" );
		$bogus = (array)$this;
		array_splice( $bogus /*was: $this*/, $i, 1 );

		if ( isset( $this->__qname_to_attr[$qname] ) ) {
			if ( is_array( $this->__qname_to_attr[$qname] ) ) {
				$i = array_search( $a, $this->__qname_to_attr[$qname] );
				if ( $i !== false ) {
					array_splice( $this->__qname_to_attr[$qname], $i, 1 );
				}
			} else {
				unset( $this->__qname_to_attr[$qname] );
			}
		}
	}

	/*
	 * DOM-LS Methods
	 */

	/**
	 * @return int
	 */
	public function length(): int {
		return count( $this );
	}

	/**
	 * @param int $index
	 * @return ?Attr
	 */
	public function item( int $index ): ?Attr {
		return $this[$index] ?? null;
	}

	/**
	 * @param string $qname
	 * @return bool
	 */
	public function hasNamedItem( string $qname ): bool {
		/*
		 * Per HTML spec, we normalize qname before lookup,
		 * even though XML itself is case-sensitive.
		 */
		if ( !ctype_lower( $qname ) && $this->_element->isHTMLElement() ) {
			$qname = Util::ascii_to_lowercase( $qname );
		}

		return isset( $this->__qname_to_attr[$qname] );
	}

	/**
	 * @param ?string $ns
	 * @param string $lname
	 * @return bool
	 */
	public function hasNamedItemNS( ?string $ns, string $lname ): bool {
		$ns = $ns ?? "";
		return isset( $this->__lname_to_attr["$ns|$lname"] );
	}

	/**
	 * @param string $qname
	 * @return ?Attr
	 */
	public function getNamedItem( string $qname ): ?Attr {
		/*
		 * Per HTML spec, we normalize qname before lookup,
		 * even though XML itself is case-sensitive.
		 */
		if ( !ctype_lower( $qname ) && $this->_element->isHTMLElement() ) {
			$qname = Util::ascii_to_lowercase( $qname );
		}

		if ( !isset( $this->__qname_to_attr[$qname] ) ) {
			return null;
		}

		if ( is_array( $this->__qname_to_attr[$qname] ) ) {
			return $this->__qname_to_attr[$qname][0];
		} else {
			return $this->__qname_to_attr[$qname];
		}
	}

	/**
	 * @param ?string $ns
	 * @param string $lname
	 * @return ?Attr
	 */
	public function getNamedItemNS( ?string $ns, string $lname ): ?Attr {
		$ns = $ns ?? "";
		return $this->__lname_to_attr["$ns|$lname"] ?? null;
	}

	/**
	 * @param Attr $attr
	 * @return ?Attr
	 */
	public function setNamedItem( Attr $attr ): ?Attr {
		$owner = $attr->ownerElement();

		if ( $owner !== null && $owner !== $this->_element ) {
			Util::error( "InUseAttributeError" );
		}

		$oldAttr = $this->getNamedItem( $attr->name() );

		if ( $oldAttr == $attr ) {
			return $attr;
		}

		if ( $oldAttr !== null ) {
			$this->__replace( $attr );
		} else {
			$this->__append( $attr );
		}

		return $oldAttr;
	}

	/**
	 * @param Attr $attr
	 * @return ?Attr
	 */
	public function setNamedItemNS( Attr $attr ): ?Attr {
		$owner = $attr->ownerElement();

		if ( $owner !== null && $owner !== $this->_element ) {
			Util::error( "InUseAttributeError" );
		}

		$oldAttr = $this->getNamedItemNS( $attr->namespaceURI(), $attr->localName() );

		if ( $oldAttr == $attr ) {
			return $attr;
		}

		if ( $oldAttr !== null ) {
			$this->__replace( $attr );
		} else {
			$this->__append( $attr );
		}

		return $oldAttr;
	}

	/**
	 * Note: qname may be lowercase or normalized in various ways
	 *
	 * @param string $qname
	 * @return ?Attr
	 */
	public function removeNamedItem( string $qname ): ?Attr {
		$attr = $this->getNamedItem( $qname );
		if ( $attr !== null ) {
			$this->__remove( $attr );
		} else {
			Util::error( "NotFoundError" );
		}
		return $attr;
	}

	/**
	 * Note: lname may be lowercase or normalized in various ways
	 *
	 * @param ?string $ns
	 * @param string $lname
	 * @return ?Attr
	 */
	public function removeNamedItemNS( ?string $ns, string $lname ): ?Attr {
		$attr = $this->getNamedItemNS( $ns, $lname );
		if ( $attr !== null ) {
			$this->__remove( $attr );
		} else {
			Util::error( "NotFoundError" );
		}
		return $attr;
	}
}
