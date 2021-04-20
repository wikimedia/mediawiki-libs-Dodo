<?php

declare( strict_types = 1 );
// @phan-file-suppress PhanTypeMismatchReturn
// phpcs:disable MediaWiki.Commenting.FunctionComment.MissingDocumentationPublic
// phpcs:disable MediaWiki.Commenting.FunctionComment.MissingParamTag
// phpcs:disable MediaWiki.Commenting.PropertyDocumentation.MissingDocumentationPrivate
// phpcs:disable PSR2.Classes.PropertyDeclaration.Underscore

namespace Wikimedia\Dodo;

/******************************************************************************
 * DOMImplementation.php
 * ---------------------
 * The DOMImplementation interface represents an object providing methods
 * which are not dependent on any particular document. Such an object is
 * available in the Document->implementation property.
 *
 * PORT NOTES:
 *
 * Removes:
 *       public function mozSetOutputMutationHandler($doc, $handler)
 *       public function mozGetInputMutationHandler($doc)
 *       public $mozHTMLParser = HTMLParser;
 *
 * Renames:
 * Changes:
 *      - supportedFeatures array was moved to a static variable inside of
 *        DOMImplementation->hasFeature(), and renamed to $supported.
 *
 */

/*
 * Each Document must have its own instance of
 * a DOMImplementation object
 */
class DOMImplementation implements \Wikimedia\IDLeDOM\DOMImplementation {
	// Stub out methods not yet implemented.
	use \Wikimedia\IDLeDOM\Stub\DOMImplementation;
	use UnimplementedTrait;

	private $_contextObject;

	public function __construct( $contextObject = null ) {
		$this->_contextObject = $contextObject;
	}

	/**
	 * hasFeature()
	 * @param string $feature a string corresponding to a key in $supportedFeatures
	 * @param string $version [optional] a string corresponding to a version in $supportedFeatures
	 * @return False if arg (pair) not in $supportedFeatures, else True
	 * NOTE[TODO]
	 *      It returns false due to the data structure having no
	 *      "" member in the primary array. This is not very
	 *      defensive programming.
	 */
	public function hasFeature( string $feature = "", string $version = "" ) : bool {
		/*
		 * Feature/version pairs that DOMImplementation->hasFeature()
		 * returns true for. It returns false for anything else.
		 */
		static $supported = [
			"xml"   => [ "" => true, "1.0" => true, "2.0" => true ],
			"core"  => [ "" => true, "2.0" => true ],
			"html"  => [ "" => true, "1.0" => true, "2.0" => true ],
			"xhtml" => [ "" => true, "1.0" => true, "2.0" => true ]
		];

		if ( !isset( $supported[$feature] ) ) {
			return false;
		} else {
			if ( !isset( $supported[$feature][$version] ) ) {
				return false;
			}
		}
		return true;
	}

	public function createDocumentType( $qualifiedName, $publicId, $systemId ) {
		if ( !$this->isValidQName( $qualifiedName ) ) {
			Util::error( 'Invalid qualified name.', 'InvalidCharacterError' );
		}

		$contextObject = $this->_contextObject ?? new Document( $qualifiedName );

		return new DocumentType( $contextObject,
			$qualifiedName,
			$publicId,
			$systemId );
		/* TEMPORARY STUB */
	}

	/**
	 * @param string $qualifiedName
	 * @return bool
	 */
	private function isValidQName( string $qualifiedName ) : bool {
		// $qualifiedName = $this->checkEncoding($qualifiedName);

		return preg_match(
			'/^([a-z_\x80-\xff]+[a-z0-9._\x80-\xff-]*:)?[a-z_\x80-\xff]+[a-z0-9._\x80-\xff-]*$/i',
			$qualifiedName
		);
	}

	/**
	 * TODO implement this
	 *
	 * @param string $str
	 *
	 * @return mixed
	 */
	private function checkEncoding( $str ) {
		/**
		 *
		 */

		return $str;
	}

	/** @inheritDoc */
	public function createDocument( ?string $namespace, ?string $qualifiedName = '', $doctype = null ) {
		/*
		 * Note that the current DOMCore spec makes it impossible
		 * to create an HTML document with this function, even if
		 * the namespace and doctype are properly set. See thread:
		 * http://lists.w3.org/Archives/Public/www-dom/2011AprJun/0132.html
		 *
		 * TODO PORT: Okay....so...
		 */
		$d = new Document( 'xml', null );

		if ( $qualifiedName ) {
			$e = $d->createElementNS( $namespace, $qualifiedName );
		} else {
			$e = null;
		}

		if ( $doctype ) {
			$d->appendChild( $doctype );
		}

		if ( $e ) {
			$d->appendChild( $e );
		}

		if ( $namespace === Util::NAMESPACE_HTML ) {
			$d->_contentType = "application/xhtml+xml";
		} elseif ( $namespace === Util::NAMESPACE_SVG ) {
			$d->_contentType = "image/svg+xml";
		} else {
			$d->_contentType = "application/xml";
		}

		return $d;
	}

	public function createHTMLDocument( ?string $titleText = null ) {
		$d = new Document( 'html', null );

		$d->appendChild( new DocumentType( $d, "html" ) );

		$html = $d->createElement( "html" );

		$d->appendChild( $html );

		$head = $d->createElement( "head" );

		$html->appendChild( $head );

		if ( $titleText !== null ) {
			$title = $d->createElement( "title" );
			$head->appendChild( $title );
			$title->appendChild( $d->createTextNode( $titleText ) );
		}

		$html->appendChild( $d->createElement( "body" ) );

		return $d;
	}
}
