<?php

declare( strict_types = 1 );
// @phan-file-suppress PhanCoalescingNeverNull
// @phan-file-suppress PhanCoalescingNeverUndefined
// @phan-file-suppress PhanUndeclaredMethod
// @phan-file-suppress PhanUndeclaredProperty
// phpcs:disable MediaWiki.Commenting.FunctionComment.MissingDocumentationPrivate
// phpcs:disable MediaWiki.Commenting.FunctionComment.MissingDocumentationPublic
// phpcs:disable MediaWiki.Commenting.FunctionComment.WrongStyle
// phpcs:disable MediaWiki.Commenting.PropertyDocumentation.MissingDocumentationPublic
// phpcs:disable MediaWiki.NamingConventions.LowerCamelFunctionsName.FunctionName
// phpcs:disable PSR2.Classes.PropertyDeclaration.Underscore
// phpcs:disable PSR2.Methods.MethodDeclaration.Underscore

namespace Wikimedia\Dodo;

/******************************************************************************
 * Text.php
 * --------
 */
class Text extends CharacterData {
	public $_nodeType = Node::TEXT_NODE;
	public $_nodeName = '#text';

	public function __construct( Document $doc, $data ) {
		parent::__construct();
		$this->_ownerDocument = $doc;
		$this->_data = $data;
	}

	/* Overrides Node::nodeValue */
	public function nodeValue( ?string $value = null ) {
		/* GET */
		if ( $value === null ) {
			return $this->_data;
			/* SET */
		} else {
			$value = ( $value === null ) ? '' : strval( $value );

			if ( $value === $this->_data ) {
				return;
			}

			$this->_data = $value;

			if ( $this->__is_rooted() ) {
				$this->_ownerDocument->__mutate_value( $this );
			}

			if ( $this->_parentNode && $this->_parentNode->_textchangehook ?? null ) {
				$this->_parentNode->_textchangehook( $this );
			}
		}
	}

	public function _subclass_isEqualNode( Node $node ): bool {
		return ( $this->_data === $node->_data );
	}

	public function _subclass_cloneNodeShallow(): ?Node {
		return new Text( $this->_ownerDocument, $this->_data );
	}

	/* Per spec */
	public function textContent( $value = null ) {
		return $this->nodeValue( $value );
	}

	public function data( $value = null ) {
		return $this->nodeValue( $value );
	}

	public function splitText( $offset ) {
		if ( $offset > strlen( $this->_data ) || $offset < 0 ) {
			Util::error( "IndexSizeError" );
		}

		$newdata = substr( $this->_data, $offset );
		$newnode = $this->_ownerDocument->createTextNode( $newdata );
		$this->nodeValue( substr( $this->_data, 0, $offset ) );

		$parent = $this->parentNode();

		if ( $parent !== null ) {
			$parent->insertBefore( $newnode, $this->nextSibling() );
		}
		return $newnode;
	}

	public function wholeText() {
		$result = $this->textContent();

		for ( $n = $this->nextSibling(); $n !== null; $n = $n->nextSibling() ) {
			if ( $n->_nodeType !== Node::TEXT_NODE ) {
				break;
			}
			$result .= $n->textContent();
		}
		return $result;
	}
}
