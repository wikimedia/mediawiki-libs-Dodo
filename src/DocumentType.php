<?php

declare( strict_types = 1 );
// phpcs:disable MediaWiki.Commenting.FunctionComment.MissingDocumentationPublic
// phpcs:disable MediaWiki.Commenting.FunctionComment.WrongStyle

namespace Wikimedia\Dodo;

use Wikimedia\Dodo\Internal\UnimplementedTrait;

/******************************************************************************
 * DocumentType.php
 * ----------------
 */
class DocumentType extends Leaf implements \Wikimedia\IDLeDOM\DocumentType {
	// DOM mixins
	use ChildNode;

	// Stub out methods not yet implemented.
	use \Wikimedia\IDLeDOM\Stub\DocumentType;
	use UnimplementedTrait;

	// Helper functions from IDLeDOM
	use \Wikimedia\IDLeDOM\Helper\DocumentType;

	/**
	 * @var string
	 */
	private $_name;
	/**
	 * @var string
	 */
	private $_publicId;
	/**
	 * @var string
	 */
	private $_systemId;

	/**
	 * @param Document $nodeDocument
	 * @param string $name
	 * @param string $publicId
	 * @param string $systemId
	 */
	public function __construct( Document $nodeDocument, string $name, string $publicId = '', string $systemId = '' ) {
		parent::__construct( $nodeDocument );

		$this->_name = $name;
		$this->_publicId = $publicId;
		$this->_systemId = $systemId;
	}

	/**
	 * @inheritDoc
	 */
	final public function getNodeType() : int {
		return Node::DOCUMENT_TYPE_NODE;
	}

	/**
	 * @inheritDoc
	 */
	final public function getNodeName() : string {
		return $this->_name;
	}

	/**
	 * @inheritDoc
	 */
	public function getName(): string {
		return $this->_name;
	}

	/**
	 * @inheritDoc
	 */
	public function getPublicId(): string {
		return $this->_publicId;
	}

	/**
	 * @inheritDoc
	 */
	public function getSystemId(): string {
		return $this->_systemId;
	}

	/* Methods delegated in Node */

	/** @return DocumentType */
	protected function _subclassCloneNodeShallow(): Node {
		return new DocumentType( $this->_nodeDocument, $this->_name, $this->_publicId, $this->_systemId );
	}

	/** @inheritDoc */
	protected function _subclassIsEqualNode( Node $node ): bool {
		'@phan-var DocumentType $node'; /** @var DocumentType $node */
		return (
			$this->_name === $node->_name &&
			$this->_publicId === $node->_publicId &&
			$this->_systemId === $node->_systemId
		);
	}
}
