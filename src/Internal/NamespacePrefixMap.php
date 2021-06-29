<?php

declare( strict_types = 1 );

namespace Wikimedia\Dodo\Internal;

use Wikimedia\IDLeDOM\Element;

/**
 * A namespace prefix map.
 * @see https://w3c.github.io/DOM-Parsing/#dfn-namespace-prefix-map
 */
class NamespacePrefixMap {
	/** @var array<string,string[]> Backing storage for this map */
	private $map = [];

	/**
	 * Create a new empty namespace prefix map.
	 */
	public function __construct() {
	}

	/**
	 * Keys can include 'null'; use a prefix to ensure that we can
	 * represent all valid keys as a string.
	 * @param ?string $key Key to a namespace prefix map
	 * @return string Key that can be used for a PHP associative array
	 */
	private static function makeKey( ?string $key ) : string {
		return $key === null ? 'null' : "!$key";
	}

	/**
	 * Check if a prefix string is found in a namespace prefix map.
	 * @see https://w3c.github.io/DOM-Parsing/#dfn-found
	 * @param ?string $namespace
	 * @param string $prefix
	 * @return bool
	 */
	public function found( ?string $namespace, string $prefix ) {
		$candidatesList = $this->map[self::makeKey( $namespace )] ?? [];
		return in_array( $prefix, $candidatesList, true );
	}

	/**
	 * Add a prefix string to the namespace prefix map.
	 * @see https://w3c.github.io/DOM-Parsing/#dfn-add
	 * @param ?string $namespace
	 * @param string $prefix
	 */
	public function add( ?string $namespace, string $prefix ) {
		$key = self::makeKey( $namespace );
		if ( array_key_exists( $key, $this->map ) ) {
			$this->map[$key][] = $prefix;
		} else {
			$this->map[$key] = [ $prefix ];
		}
	}

	/**
	 * Record the namespace information for an element, given this namespace
	 * prefix map and a local prefixes map.
	 * @see https://w3c.github.io/DOM-Parsing/#dfn-recording-the-namespace-information
	 * @param Element $element
	 * @param array<string,string> &$localPrefixMap
	 * @return ?string
	 */
	public function recordNamespaceInformation(
		Element $element, array &$localPrefixMap
	) : ?string {
		$result = null;

		foreach ( $element->getAttributes() as $attr ) {
			$attrNamespace = $attr->getNamespaceURI();
			$attrPrefix = $attr->getPrefix();
			if ( $attrNamespace === Util::NAMESPACE_XMLNS ) {
				if ( $attrPrefix === null ) {
					// $attr is a default namespace declaration
					$result = $attr->getValue();
					continue;
				}
				// $attr is a namespace prefix definition
				$prefixDefinition = $attr->getLocalName();
				$namespaceDefinition = $attr->getValue();
				if ( $namespaceDefinition === Util::NAMESPACE_XML ) {
					continue;
				} elseif ( $namespaceDefinition === '' ) {
					$namespaceDefinition = null;
				}
				if ( $this->found( $namespaceDefinition, $prefixDefinition ) ) {
					continue;
				}
				$this->add( $namespaceDefinition, $prefixDefinition );
				$localPrefixMap[$prefixDefinition] = $namespaceDefinition ?? '';
			}
		}
		return $result;
	}

	/**
	 * Retrieve a preferred prefix string.
	 * @see https://w3c.github.io/DOM-Parsing/#dfn-retrieving-a-preferred-prefix-string
	 * @param ?string $namespace
	 * @param ?string $preferredPrefix
	 * @return ?string
	 */
	public function retrievePreferredPrefix(
		?string $namespace,
		?string $preferredPrefix
	) : ?string {
		if ( $preferredPrefix === null ) {
			return null;
		}
		$last = null;
		$candidatesList = $this->map[self::makeKey( $namespace )] ?? [];
		foreach ( $candidatesList as $prefix ) {
			$last = $prefix;
			if ( $prefix === $preferredPrefix ) {
				break;
			}
		}
		return $last;
	}

	/**
	 * Copy a namespace prefix map.
	 * @see https://w3c.github.io/DOM-Parsing/#dfn-copy-a-namespace-prefix-map
	 * @return NamespacePrefixMap
	 */
	public function clone() : NamespacePrefixMap {
		$c = new NamespacePrefixMap();
		$c->map = $this->map; // PHP handles the deep array copy for us
		return $c;
	}

	/**
	 * Generate a prefix given a map, a string new namespace, and a reference
	 * to a prefix index.
	 * @see https://w3c.github.io/DOM-Parsing/#generating-namespace-prefixes
	 * @param ?string $newNamespace
	 * @param int &$prefixIndex
	 * @return string the generated prefix
	 */
	public function generatePrefix(
		?string $newNamespace, int &$prefixIndex
	) {
		$generatedPrefix = 'ns' . $prefixIndex;
		$prefixIndex += 1;
		$this->add( $newNamespace, $generatedPrefix );
		return $generatedPrefix;
	}

}
