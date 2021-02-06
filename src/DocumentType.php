<?php

declare( strict_types = 1 );
// @phan-file-suppress PhanUndeclaredProperty
// phpcs:disable Generic.NamingConventions.UpperCaseConstantName.ClassConstantNotUpperCase
// phpcs:disable MediaWiki.Commenting.FunctionComment.MissingDocumentationPrivate
// phpcs:disable MediaWiki.Commenting.FunctionComment.MissingDocumentationPublic
// phpcs:disable MediaWiki.Commenting.FunctionComment.WrongStyle
// phpcs:disable MediaWiki.NamingConventions.LowerCamelFunctionsName.FunctionName
// phpcs:disable PSR2.Classes.PropertyDeclaration.Underscore
// phpcs:disable PSR2.Methods.MethodDeclaration.Underscore

namespace Wikimedia\Dodo;

/******************************************************************************
 * DocumentType.php
 * ----------------
 */
class DocumentType extends ChildNodeLeaf {
	protected const _nodeType = Node::DOCUMENT_TYPE_NODE;

	public function __construct( Document $doc, string $name, string $publicId = '', string $systemId = '' ) {
		parent::__construct();

		$this->_ownerDocument = $doc;
		$this->_name = $name;
		$this->_nodeName = $name; // spec; let Node::nodeName handle
		$this->_nodeValue = null; // spec; let Node::nodeValue handle
		$this->_publicId = $publicId;
		$this->_systemId = $systemId;
	}

	public function name() {
		return $this->_name;
	}

	public function publicId() {
		return $this->_publicId;
	}

	public function systemId() {
		return $this->_systemId;
	}

	/* Methods delegated in Node */
	public function _subclass_cloneNodeShallow(): ?Node {
		return new DocumentType( $this->_ownerDocument, $this->_name, $this->_publicId, $this->_systemId );
	}

	public function _subclass_isEqualNode( Node $node ): bool {
		'@phan-var DocumentType $node'; /** @var DocumentType $node */
		return (
			$this->_name === $node->_name &&
			$this->_publicId === $node->_publicId &&
			$this->_systemId === $node->_systemId
		);
	}

	public function textContent( ?string $value = null ) {
		return null;
	}
}
