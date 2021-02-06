<?php

declare( strict_types = 1 );
// @phan-file-suppress PhanParamTooFew
// @phan-file-suppress PhanParamTooMany
// @phan-file-suppress PhanTypeMismatchArgumentReal
// @phan-file-suppress PhanUndeclaredClassMethod
// @phan-file-suppress PhanUndeclaredVariable
// phpcs:disable MediaWiki.Commenting.FunctionComment.MissingDocumentationPrivate
// phpcs:disable MediaWiki.Commenting.FunctionComment.MissingDocumentationPublic
// phpcs:disable MediaWiki.Commenting.FunctionComment.WrongStyle
// phpcs:disable MediaWiki.Commenting.PropertyDocumentation.MissingDocumentationPublic
// phpcs:disable MediaWiki.NamingConventions.LowerCamelFunctionsName.FunctionName
// phpcs:disable PSR2.Classes.PropertyDeclaration.Underscore
// phpcs:disable PSR2.Methods.MethodDeclaration.Underscore

namespace Wikimedia\Dodo;

/******************************************************************************
 * DocumentFragment.php
 * --------------------
 */
class DocumentFragment extends Node {
	public $_nodeType = Node::DOCUMENT_FRAGMENT_NODE;
	public $_nodeName = '#document-fragment';
	public $_nodeValue = null;

	public function __construct( Document $doc ) {
		parent::__construct( $doc );

		$this->_ownerDocument = $doc;
	}

	/* TODO: Same as Element's. Factor? */
	public function textContent( ?string $value = null ) {
		/* GET */
		if ( $value === null ) {
			$text = [];
			Algorithm::descendant_text_content( $this, $text );
			return implode( "", $text );
			/* SET */
		} else {
			$this->__remove_children();
			if ( $value !== "" ) {
				/* Equivalent to Node:: appendChild without checks! */
				WhatWG::insert_before_or_replace( $node, $this->_ownerDocument->createTextNode( $value ), null );
			}
		}
	}

	public function querySelector( $selector ) {
		// implement in terms of querySelectorAll
		/* TODO stub */
		$nodes = $this->querySelectorAll( $selector );
		return count( $nodes ) ? $nodes[0] : null;
	}

	public function querySelectorAll( $selector ) {
		/* TODO: Stub */
		//// create a context
		//var context = Object.create(this);
		//// add some methods to the context for zest implementation, without
		//// adding them to the public DocumentFragment API
		//context.isHTML = true; // in HTML namespace (case-insensitive match)
		//context.getElementsByTagName = Element.prototype.getElementsByTagName;
		//context.nextElement =
		//Object.getOwnPropertyDescriptor(Element.prototype, 'firstElementChild').get;
		//// invoke zest
		//var nodes = select(selector, context);
		//return nodes.item ? nodes : new NodeList(nodes);
	}

	/* TODO DELEGATED FROM NODE */
	public function _subclass_cloneNodeShallow(): ?Node {
		return new DocumentFragment( $this->_ownerDocument );
	}

	public function _subclass_isEqualNode( Node $node ): bool {
		// Any two document fragments are shallowly equal.
		// Node.isEqualNode() will test their children for equality
		return true;
	}

	// Non-standard, but useful (github issue #73)
	public function innerHTML() {
		return $this->_node_serialize();
	}

	public function outerHTML( ?string $value = null ) {
		return $this->_node_serialize();
	}
}
