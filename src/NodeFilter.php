<?php

declare( strict_types = 1 );

// phpcs:disable Generic.NamingConventions.CamelCapsFunctionName.MethodDoubleUnderscore
// phpcs:disable Generic.NamingConventions.CamelCapsFunctionName.ScopeNotCamelCaps
// phpcs:disable MediaWiki.Commenting.FunctionComment.MissingDocumentationPublic
// phpcs:disable MediaWiki.Commenting.FunctionComment.WrongStyle
// phpcs:disable MediaWiki.Commenting.PropertyDocumentation.MissingDocumentationPublic
// phpcs:disable MediaWiki.Commenting.PropertyDocumentation.WrongStyle

namespace Wikimedia\Dodo;

/**
 * NodeFilter.php
 * --------
 * Stub for https://dom.spec.whatwg.org/#interface-nodefilter
 *
 * @package Wikimedia\Dodo
 */
abstract class NodeFilter {
	use UnimplementedTrait;

	// Constants for acceptNode()
	public const FILTER_ACCEPT = 1;
	public const FILTER_REJECT = 2;
	public const FILTER_SKIP = 3;

	// Constants for whatToShow
	public const SHOW_ALL = 0xFFFFFFFF;
	public const SHOW_ELEMENT = 0x1;
	public const SHOW_ATTRIBUTE = 0x2;
	public const SHOW_TEXT = 0x4;
	public const SHOW_CDATA_SECTION = 0x8;
	public const SHOW_ENTITY_REFERENCE = 0x10; // legacy
	public const SHOW_ENTITY = 0x20; // legacy
	public const SHOW_PROCESSING_INSTRUCTION = 0x40;
	public const SHOW_COMMENT = 0x80;
	public const SHOW_DOCUMENT = 0x100;
	public const SHOW_DOCUMENT_TYPE = 0x200;
	public const SHOW_DOCUMENT_FRAGMENT = 0x400;
	public const SHOW_NOTATION = 0x800; // legacy

	/**
	 * @param Node $node
	 */
	public function acceptNode( Node $node ) {
		throw $this->_unimplemented();
	}
}
