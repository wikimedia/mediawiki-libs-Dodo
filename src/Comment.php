<?php

declare( strict_types = 1 );
// phpcs:disable MediaWiki.Commenting.FunctionComment.MissingDocumentationPrivate
// phpcs:disable MediaWiki.Commenting.FunctionComment.MissingDocumentationPublic
// phpcs:disable MediaWiki.Commenting.FunctionComment.WrongStyle
// phpcs:disable MediaWiki.Commenting.PropertyDocumentation.MissingDocumentationPrivate
// phpcs:disable MediaWiki.Commenting.PropertyDocumentation.MissingDocumentationProtected
// phpcs:disable MediaWiki.Commenting.PropertyDocumentation.MissingDocumentationPublic
// phpcs:disable MediaWiki.NamingConventions.LowerCamelFunctionsName.FunctionName
// phpcs:disable PSR2.Classes.PropertyDeclaration.Underscore
// phpcs:disable PSR2.Methods.MethodDeclaration.Underscore

namespace Wikimedia\Dodo;

/******************************************************************************
 * Comment.php
 * -----------
 */
class Comment extends CharacterData {
	public $_nodeType = Node::COMMENT_NODE;
	public $_nodeName = '#comment';

	public function __construct( Document $doc, $data ) {
		parent::__construct();
		$this->_ownerDocument = $doc;
		$this->_data = $data;
	}

	public function _subclass_cloneNodeShallow(): ?Node {
		return new Comment( $this->_ownerDocument, $this->_data );
	}

	public function _subclass_isEqualNode( Node $node ): bool {
		'@phan-var Comment $node'; /** @var Comment $node */
		return ( $this->_data === $node->_data );
	}

	public function nodeValue( ?string $value = null ) {
		if ( $value === null ) {
			return $this->_data;
		} else {
			$value = ( $value === null ) ? '' : strval( $value );

			$this->_data = $value;

			if ( $this->__is_rooted() ) {
				$this->_ownerDocument->__mutate_value( $this );
			}
		}
	}

	public function textContent( ?string $value = null ) {
		return $this->nodeValue( $value );
	}

	public function data( ?string $value = null ) {
		return $this->nodeValue( $value );
	}

	/*
	 * TODO: Does this override directly?
	 * Or should we use _subclass_clone_shallow?
	 */
	public function clone(): Comment {
		return new Comment( $this->_ownerDocument, $this->_data );
	}
}
