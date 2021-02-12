<?php

declare( strict_types = 1 );
// @phan-file-suppress PhanTypeMismatchArgumentNullable
// @phan-file-suppress PhanUndeclaredMethod
// phpcs:disable MediaWiki.NamingConventions.LowerCamelFunctionsName.FunctionName
// phpcs:disable PSR2.Classes.PropertyDeclaration.Underscore
// phpcs:disable PSR2.Methods.MethodDeclaration.Underscore

namespace Wikimedia\Dodo;

use Exception;

/**
 * Attr.php
 * --------
 * The Attr class represents a single attribute node.
 *
 * NOTE
 * The definition of the Attr class has undergone some changes in recent
 * revisions of the DOM spec.
 *
 *      DOM-2: Introduced namespaces, and the properties 'namespaceURI',
 *             'localName', and 'prefix' were defined on the Node class.
 *             As a subclass of Node, Attr inherited these.
 *
 *      DOM-4: Attr was no longer classified as a Node. The properties
 *             'namespaceURI', 'localName', and 'prefix' were removed
 *             from the Node class. They were now defined on the Attr
 *             class itself, as well as on the Element class, which
 *             remained a subclass of Node.
 *
 *      DOM-LS: Attr was re-classified as a Node, but the properties
 *              'namespaceURI', 'localName', and 'prefix' remained on
 *              the Attr class (and Element class), and did not re-appear
 *              on the Node class..
 */
/*
 * Qualified Names, Local Names, and Namespace Prefixes
 *
 * An Element or Attribute's qualified name is its local name if its
 * namespace prefix is null, and its namespace prefix, followed by ":",
 * followed by its local name, otherwise.
 */
/*
 * NOTES (taken from Domino.js)
 *
 * Attributes in the DOM are tricky:
 *
 * - there are the 8 basic get/set/has/removeAttribute{NS} methods
 *
 * - but many HTML attributes are also 'reflected' through IDL
 *   attributes which means that they can be queried and set through
 *   regular properties of the element.  There is just one attribute
 *   value, but two ways to get and set it.
 *
 * - Different HTML element types have different sets of reflected
 *   attributes.
 *
 * - attributes can also be queried and set through the .attributes
 *   property of an element.  This property behaves like an array of
 *   Attr objects.  The value property of each Attr is writeable, so
 *   this is a third way to read and write attributes.
 *
 * - for efficiency, we really want to store attributes in some kind
 *   of name->attr map.  But the attributes[] array is an array, not a
 *   map, which is kind of unnatural.
 *
 * - When using namespaces and prefixes, and mixing the NS methods
 *   with the non-NS methods, it is apparently actually possible for
 *   an attributes[] array to have more than one attribute with the
 *   same qualified name.  And certain methods must operate on only
 *   the first attribute with such a name.  So for these methods, an
 *   inefficient array-like data structure would be easier to
 *   implement.
 *
 * - The attributes[] array is live, not a snapshot, so changes to the
 *   attributes must be immediately visible through existing arrays.
 *
 * - When attributes are queried and set through IDL properties
 *   (instead of the get/setAttributes() method or the attributes[]
 *   array) they may be subject to type conversions, URL
 *   normalization, etc., so some extra processing is required in that
 *   case.
 *
 * - But access through IDL properties is probably the most common
 *   case, so we'd like that to be as fast as possible.
 *
 * - We can't just store attribute values in their parsed idl form,
 *   because setAttribute() has to return whatever string is passed to
 *   getAttribute even if it is not a legal, parseable value. So
 *   attribute values must be stored in unparsed string form.
 *
 * - We need to be able to send change notifications or mutation
 *   events of some sort to the renderer whenever an attribute value
 *   changes, regardless of the way in which it changes.
 *
 * - Some attributes, such as id and class affect other parts of the
 *   DOM API, like getElementById and getElementsByClassName and so
 *   for efficiency, we need to specially track changes to these
 *   special attributes.
 *
 * - Some attributes like class have different names (className) when
 *   reflected.
 *
 * - Attributes whose names begin with the string 'data-' are treated
 *   specially.
 *
 * - Reflected attributes that have a boolean type in IDL have special
 *   behavior: setting them to false (in IDL) is the same as removing
 *   them with removeAttribute()
 *
 * - numeric attributes (like HTMLElement.tabIndex) can have default
 *   values that must be returned by the idl getter even if the
 *   content attribute does not exist. (The default tabIndex value
 *   actually varies based on the type of the element, so that is a
 *   tricky one).
 *
 * See
 * http://www.whatwg.org/specs/web-apps/current-work/multipage/urls.html#reflect
 * for rules on how attributes are reflected.
 */

/*
 * SPEC NOTE
 * Attr has gone back and forth between
 * extending Node and being its own
 * class in recent specs. As of the
 * most recent DOM-LS at the time of this
 * writing (2021-02-11), it extends Node.
 */
class Attr extends Node implements \Wikimedia\IDLeDOM\Attr {
	// Stub out methods not yet implemented.
	use \Wikimedia\IDLeDOM\Stub\Attr;
	use UnimplementedTrait;

	// Helper functions from IDLeDOM
	use \Wikimedia\IDLeDOM\Helper\Attr;

	/**
	 * @var string|null
	 * Should be considered readonly, if a string its non-empty
	 */
	protected $_namespaceURI = null;

	/**
	 * @var string|null
	 * Should be considered readonly, if a string its non-empty
	 */
	protected $_prefix = null;

	/**
	 * @var string
	 * Should be considered readonly, always non-empty
	 */
	protected $_localName = '';

	/**
	 * @var string
	 * Should be considered readonly, always non-empty
	 */
	protected $_name = '';

	/** @var string */
	protected $_value = '';

	/**
	 * @var Element|null
	 * Should be considered readonly
	 */
	protected $_ownerElement = null;

	/**
	 * @var bool
	 * Should be considered readonly, always true - TODO make a constant
	 */
	protected $_specified = true; /* readonly const true */

	/**
	 * @param ?Element $ownerElement
	 * @param string $localName
	 * @param ?string $prefix
	 * @param ?string $namespaceURI
	 * @param string $value
	 */
	public function __construct(
		?Element $ownerElement,
		string $localName,
		?string $prefix = null,
		?string $namespaceURI = null,
		string $value = ""
	) {
		$this->_nodeType = Node::ATTRIBUTE_NODE;
		if ( $localName !== '' ) {
			/* DOM-LS: Non-empty string */
			$this->_localName = $localName;
		} else {
			throw new Exception( "Attr local name must be non-empty" );
		}

		if ( $namespaceURI !== '' ) {
			/* DOM-LS: NULL or non-empty string */
			$this->_namespaceURI = $namespaceURI;
		}

		if ( $prefix !== '' ) {
			/* DOM-LS: null or non-empty string */
			$this->_prefix = $prefix;

			/* DOM-LS: qualified name:
			 *      namespace prefix, followed by ":",
			 *      followed by local name, if prefix is not null.
			 */
			$this->_name = "$prefix:$localName";
		} else {
			/* DOM-LS: qualified name: localName if prefix is null */
			$this->_name = $localName;
		}

		/* DOM-LS: null or Element */
		$this->_ownerElement = $ownerElement;

		/* DOM-LS: string */
		$this->_value = $value;
	}

	/*
	 * ACCESSORS
	 */

	/** @inheritDoc */
	public function getNamespaceURI(): ?string {
		return $this->_namespaceURI;
	}

	/** @inheritDoc */
	public function getSpecified(): bool {
		return $this->_specified;
	}

	/** @inheritDoc */
	public function getOwnerElement(): ?Element {
		return $this->_ownerElement;
	}

	/** @inheritDoc */
	public function getPrefix(): ?string {
		return $this->_prefix;
	}

	/** @inheritDoc */
	public function getLocalName(): string {
		return $this->_localName;
	}

	/** @inheritDoc */
	public function getName(): string {
		return $this->_name;
	}

	/** @inheritDoc */
	public function getValue() : string {
		return $this->_value;
	}

	/** @inheritDoc */
	public function setValue( string $value = null ) : void {
		/*
		 * NOTE
		 * You can unset an attribute by calling Attr::value("");
		 */

		if ( $this->_value === $value ) {
			return;
		}

		$old = $this->_value;
		$this->_value = $value;

		if ( $this->_ownerElement
			 && ( isset( $this->_ownerElement->__onchange_attr[$this->_localName] ) )
		) {
			/*
			 * Elements must take special action if the
			 * value of certain attributes are updated.
			 * This allows the Attr to inform the Element
			 * it has been updated, so the Element can
			 * take the appropriate steps.
			 *
			 * For example, updating the 'id' attribute
			 * will cause a rooted Element to delete its
			 * old id from and add its new id to its
			 * ownerDocument's node id cache.
			 *
			 * WARNING: This is only fired when we modify
			 * the attribute using .value(). This is not
			 * fired when we call Element::removeAttribute,
			 * but that's okay for 'id' and 'class'.
			 */
			$this->_ownerElement->__onchange_attr[$this->_localName](
				$this->_ownerElement,
				$old,
				$value
			);
		}

		if ( $this->_ownerElement->__is_rooted() ) {
			/*
			 * Documents must also sometimes take special action
			 * and be aware of mutations occurring in their tree.
			 * These methods are for that.
			 *
			 * WARNING: This is only fired when we modify
			 * the attribute using .value(). This is not
			 * fired when we call Element::removeAttribute,
			 * but that's okay for 'id' and 'class'.
			 *
			 * TODO: These two mutation handling things
			 * should be combined.
			 *
			 * TODO: Is this trying to implement spec,
			 * or are we just doing this for our own use?
			 */
			$this->_ownerElement->ownerDocument()->__mutate_attr( $this, $old );
		}
	}

	/**
	 * @inheritDoc
	 */
	public function getTextContent() : ?string {
		// https://dom.spec.whatwg.org/#dom-node-textcontent
		return $this->value( $value );
	}

	/**
	 * @inheritDoc
	 */
	public function setTextContent( ?string $val ) : void {
		// https://dom.spec.whatwg.org/#dom-node-textcontent
		$this->setValue( $val );
	}

	/**
	 * Delegated from Node
	 *
	 * @return ?Node always Attr
	 */
	public function _subclass_cloneNodeShallow(): ?Node {
		return new Attr(
			null,
			$this->_localName,
			$this->_prefix,
			$this->_namespaceURI,
			$this->_value
		);
	}

	/**
	 * Delegated from Node
	 *
	 * @param Node $node
	 * @return bool
	 */
	public function _subclass_isEqualNode( Node $node ): bool {
		'@phan-var Attr $node';
		/** @var Attr $node */
		return (
			$this->_namespaceURI === $node->_namespaceURI
			&& $this->_localName === $node->_localName
			&& $this->_value === $node->_value
		);
	}
}
