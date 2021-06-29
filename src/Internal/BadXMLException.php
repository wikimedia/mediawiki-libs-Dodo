<?php

declare( strict_types = 1 );

namespace Wikimedia\Dodo\Internal;

/**
 * Placeholder exception for errors encountered while executing the
 * XML serialization algorithm.
 * @see https://w3c.github.io/DOM-Parsing/#xml-serialization
 */
class BadXMLException extends \Exception implements \Wikimedia\IDLeDOM\SimpleException {
	/**
	 * Create a BadXMLException
	 */
	public function __construct() {
		parent::__construct( "Invalid XML characters" );
	}
}
