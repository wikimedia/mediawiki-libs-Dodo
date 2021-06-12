<?php

declare( strict_types = 1 );

namespace Wikimedia\Dodo;

use RemexHtml\DOM\DOMBuilder;
use RemexHtml\Tokenizer\Tokenizer;
use RemexHtml\TreeBuilder\Dispatcher;
use RemexHtml\TreeBuilder\TreeBuilder;
use Wikimedia\Dodo\Internal\UnimplementedException;
use Wikimedia\IDLeDOM\DOMParserSupportedType;

/**
 * DOMParser
 * @see https://dom.spec.whatwg.org/#interface-domparser
 */
class DOMParser implements \Wikimedia\IDLeDOM\DOMParser {

	/**
	 * @param string $string
	 * @param string $type
	 * @return Document
	 */
	public function parseFromString( string $string, /* DOMParserSupportedType */ string $type ) {
		$type = DOMParserSupportedType::cast( $type );
		if ( $type !== DOMParserSupportedType::text_html ) {
			throw new UnimplementedException();
		}
		$domBuilder = new class( [
			'suppressHtmlNamespace' => true,
			'suppressIdAttribute' => true,
			'domExceptionClass' => DOMException::class,
		] ) extends DOMBuilder {
				/** @var Document */
				private $doc;

				/** @inheritDoc */
				protected function createDocument(
					string $doctypeName = null,
					string $public = null,
					string $system = null
				) {
					// Force this to be an HTML document (not an XML document)
					$this->doc = new Document( null, 'html' );
					return $this->doc;
				}

				/** @inheritDoc */
				public function doctype( $name, $public, $system, $quirks, $sourceStart, $sourceLength ) {
					parent::doctype( $name, $public, $system, $quirks, $sourceStart, $sourceLength );
					// Set quirks mode on our document.
					switch ( $quirks ) {
					case TreeBuilder::NO_QUIRKS:
						$this->doc->_setQuirksMode( 'no-quirks' );
						break;
					case TreeBuilder::LIMITED_QUIRKS:
						$this->doc->_setQuirksMode( 'limited-quirks' );
						break;
					case TreeBuilder::QUIRKS:
						$this->doc->_setQuirksMode( 'quirks' );
						break;
					}
				}
		};
		$treeBuilder = new TreeBuilder( $domBuilder, [
			'ignoreErrors' => true
		] );
		$dispatcher = new Dispatcher( $treeBuilder );
		$tokenizer = new Tokenizer( $dispatcher, $string, [
			'ignoreErrors' => true ]
		);
		$tokenizer->execute( [] );

		$result = $domBuilder->getFragment();
		return $result;
	}

}
