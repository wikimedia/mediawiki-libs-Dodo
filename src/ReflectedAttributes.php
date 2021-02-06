<?php

declare( strict_types = 1 );
// @phan-file-suppress PhanUndeclaredClassMethod
// phpcs:disable MediaWiki.Commenting.FunctionComment.MissingDocumentationPrivate
// phpcs:disable MediaWiki.Commenting.FunctionComment.MissingDocumentationPublic
// phpcs:disable MediaWiki.Commenting.PropertyDocumentation.MissingDocumentationPrivate
// phpcs:disable MediaWiki.NamingConventions.LowerCamelFunctionsName.FunctionName
// phpcs:disable PSR2.Classes.PropertyDeclaration.Underscore
// phpcs:disable Squiz.Scope.MethodScope.Missing

namespace Wikimedia\Dodo;

/******************************************************************************
 * Reflecting content attributes in IDL attributes
 *
 * http://html.spec.whatwg.org/#reflecting-content-attributes-in-idl-attributes
 *
 * From the spec:
 * "Some IDL attributes are defined to reflect a particular content attribute.
 * This means that on getting, the IDL attribute returns the current value of
 * the content attribute, and on setting, the IDL attribute changes the value
 * of the content attribute to the given value."
 *
 * Many HTML Elements have well-defined interfaces expressed as attributes.
 * These attributes may be typed, have an enumerated set of allowed values,
 * have default values, and so on.
 *
 * This family of classes allows us to implement these reflected attributes.
 */
/*
 * USAGE:
 * For each specialized attribute on an Element, build a reflected
 * attribute object and add its access functions to the magic
 * __get() and/or __set() functions.
 *
 * (As of PHP 7 this is the only way to implement implicit accessors,
 * which must be done to comply with spec.)
 *
 * The switch in Element::__get()/Element::__set() will call
 * ReflectedAttr::get or ReflectedAttr::set after looking the
 * object up in a table or as a class member like
 * Element::$__attr_<attrname>.
 */
class ReflectedAttributes {
	static function reflected_attribute( $owner, $spec ) {
		if ( is_array( $spec['type'] ) ) {
			return new IDLReflectedAttributeEnumerated( $owner, $spec );
		} else {
			switch ( $spec['type'] ) {
			case 'CORS':
				return new IDLReflectedAttributeCORS( $owner, $spec );
			case 'URL':
				return new IDLReflectedAttributeURL( $owner, $spec );
			case 'boolean':
				return new IDLReflectedAttributeBoolean( $owner, $spec );
			case 'number':
			case 'long':
			case 'unsigned long':
			case 'limited unsigned long with fallback':
				return new IDLReflectedAttributeNumeric( $owner, $spec );
			case 'function':
				return new IDLReflectedAttributeFunction( $owner, $spec );
			case 'string':
			default:
				return new IDLReflectedAttributeString( $owner, $spec );
			}
		}
	}
}

/*
 * IDLReflectedAttribute SPEC
 *
 * DOMString
 * If a reflecting IDL attribute is a DOMString attribute whose content
 * attribute is an enumerated attribute, and the IDL attribute is limited
 * to only known values, then, on getting, the IDL attribute must return
 * the conforming value associated with the state the attribute is in
 * (in its canonical case), if any, or the empty string if the attribute
 * is in a state that has no associated keyword value or if the attribute
 * is not in a defined state (e.g. the attribute is missing and there is
 * no missing value default). On setting, the content attribute must be
 * set to the specified new value.
 *
 * If a reflecting IDL attribute is a nullable DOMString attribute whose
 * content attribute is an enumerated attribute, then, on getting, if the
 * corresponding content attribute is in its missing value default then the
 * IDL attribute must return null, otherwise, the IDL attribute must return
 * the conforming value associated with the state the attribute is in
 * (in its canonical case). On setting, if the new value is null, the content
 * attribute must be removed, and otherwise, the content attribute must be set
 * to the specified new value.
 *
 * array(
 *      'type' => array('ltr', 'rtl', 'auto'),
 *      'missing' => ''
 *      'invalid' => ''
 *      'nullable' => [true|false]
 * )
 *
 * DOMString; get/set done in "transparent, case-preserving manner" (spec)
 * array(
 *      'type' => string,
 *      'treat_null_as_empty_string' => [true|false]
 * )
 *

 title: String,
 lang: String,
 dir: {type: ["ltr", "rtl", "auto"], missing: ''},
 accessKey: String,
 hidden: Boolean,
 tabIndex: {type: "long", default: function() {
 if (this.tagName in focusableElements ||
 this.contentEditable)
 return 0;
 else
 return -1;
 }}


 /*
 * NOTE
 * There are HTML Elements whose default values are a function
 * of the Element. Therefore this spec must allow for default
 * values to be specified as callback functions!
 *
 * In some cases, e.g. 'tabIndex' attribute of an HTML Element,
 * the value of the attribute is *always* computed from the Element,
 * and so the 'default' function is actually just computing the
 * value!
 */
