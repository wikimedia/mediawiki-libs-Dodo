# PHP WebIDL Binding

This document describes the PHP WebIDL binding used by Dodo.

Since there does not seem to be an official WebIDL binding for PHP,
this documents the implementation choices the Dodo library has made to
map WebIDL names and types to PHP.  Where possible correspondence has
been maintained with the PHP `DOMDocument` classes, although the PHP
`DOMDocument` classes appear to be an adhoc binding of the `libxml` C
library not a rigorous WebIDL mapping.

## Namespace

All WebIDL classes and interfaces are defined in the namespace
`\Wikimedia\Dodo\`.  For example, the WebIDL interface 'Document'
corresponds to the PHP class `\Wikimedia\Dodo\Document`.

It is expected that users can use type aliasing (ie, PHP
[`class_alias`](https://www.php.net/manual/en/function.class-alias.php)
to bind a particular compatible DOM implementation.  For code which
attempts to maintain implementation-independence, we recommend the use
of the namespace `\WebIDL` (ie, `\WebIDL\Document`) as the top-level
name used in client code.

TODO: provide a `class_alias` file binding Dodo to `WebIDL` in the
recommended way?

## Names

Since PHP has a number of reserved words in the language, identifiers
of PHP constructs corresponding to IDL definitions need to be escaped
to avoid conflicts. A name is PHP escaped as follows:

> If the name is a PHP reserved word, then the PHP escaped name is the
> name prefixed with a U+005F LOW LINE ("_") character, unless the IDL
> name already begins with `_`, in which case the prefix `idl_` is
> used.  Otherwise, the name is not a PHP reserved word, and the PHP
> escaped name is simply the name.

The PHP reserved words include any identifier starting with two
underscores (`__`), which are [reserved in PHP as
magical](https://www.php.net/manual/en/language.oop5.magic.php).  All
[PHP keywords and compile-time
constants](https://www.php.net/manual/en/reserved.keywords.php),
[predefined
constants](https://www.php.net/manual/en/reserved.constants.php), and
[other reserved
words](https://www.php.net/manual/en/reserved.other-reserved-words.php)
are also reserved.

Any special cases of particular note will be documented here.

## Types

This sub-section describes how types in the IDL map to types in PHP.

### any

The `any` IDL type corresponds to a PHP `mixed` value.  No boxing of
types is required in PHP.

### void

The only place that the `void` type may appear in IDL is as the return
type of an operation. Methods on PHP objects that implement an
operation whose IDL specifies a `void` return type must be declared to
have a return type of `void`.

### boolean

The IDL `boolean` type maps exactly to the PHP
[`bool`](https://www.php.net/manual/en/language.types.boolean.php)
type.

### integer types

The IDL `octet`, `short`, `unsigned short`, `long` and `unsigned long`
types correspond to the PHP
[`int`](https://www.php.net/manual/en/language.types.integer.php)
type.

Note that while the IDL `unsigned long` type is unsigned, with a range
of [0, 4294967295], the PHP `int` type is signed, and on 32-bit
platforms may have a range of [−2147483648, 2147483647].  To encode an
IDL `unsigned long` type in a PHP int, the following steps are
followed *on all platforms regardless of the value of `PHP_INT_MAX` on
that platform`:

1. Let `x` be the IDL `unsigned long` value to encode.
2. If `x` < 2147483648, return a PHP `int` whose value is `x`.
3. Otherwise `x` ≥ 2147483648. Return a PHP int whose value is `x − 4294967296`.

Note that this is the same as casting to a 32-bit int in most languages.

To decode an IDL `unsigned long` value from a PHP `int`, the following
steps must be followed:

1. Let `x` be the PHP int value to decode.
2. If `x` ≥ 0, return an IDL `unsigned long` whose value is `x`.
3. Otherwise `x` < 0. Return an IDL `unsigned long` whose value is `x + 4294967296`.

Note that in PHP this is the same as performing a bit-wise AND of the
`int` value with the long constant `0xffffffff`.

### long long and unsigned long long

The IDL `long long` and `unsigned long long` types map to a PHP
arbitrary-length integer using either the
[GMP](https://www.php.net/manual/en/book.gmp.php) or
[BCMath](https://www.php.net/manual/en/book.bc.php).  Precise details
will be determined when there is need.

### float

The IDL `float` type maps exactly to the PHP `float` type.

### sequence<T>

The IDL `sequence<T>` type corresponds to an object implementing the
PHP `ArrayAccess` interface, which may (or may not) be an actual PHP
`array` object but will respond to accesses like one.  The type
declaration for return values should be `array|ArrayAccess`.  For
parameters, the type can be declared as either `array|ArrayAccess` or
(where appropriate) as simply `array`, since PHP can transparently
cast an `ArrayAccess` to an `array`.

TODO: Figure out if there's a better way to allow flexibility for live
arrays.  There's apparently [no more elegant
way](https://stackoverflow.com/questions/14806696/php-type-hinting-to-allow-array-or-arrayaccess)
to declare a type as either `array` or `ArrayAccess`.

A PHP object implementing an interface with an operation declared to
return a `sequence<T>` value must not return `null` from the
corresponding method. Similarly, a getter method for an IDL attribute
must not return `null` if the attribute is declared to be of type
`sequence<T>`.

### sequence<octet> and sequence<unsigned short>

As a special case, IDL `sequence<octet>` and `sequence<unsigned
short>` are represented by PHP `string`s in ASCII and UTF-16
encodings, respectively.  As with other `sequence` types, `null` is
not a valid value of these IDL types.

### Object

The IDL `Object` type maps to the PHP [`object`
type])(https://www.php.net/manual/en/language.types.object.php).

### Object implementing an interface

An IDL interface type maps exactly to the corresponding PHP interface type.

### Modules

Every IDL
[`module`](https://www.w3.org/TR/2007/WD-DOM-Bindings-20071017/#dfn-module)
corresponds to a PHP namespace.

XXX: Are modules still part of the IDL space?

## Interfaces

Every IDL interface corresponds to a PHP interface in the appropriate
namespace, as defined above.

### Constants

For each [constant](https://www.w3.org/TR/WebIDL-1/#idl-constants) defined
on the IDL [interface], there is a corresponding
PHP class constant:

* The constant has `public` visibility.
* The type is the PHP type that corresponds to the type of the constant,
  as defined in the [type section above].
* The name is the [PHP escaped] [identifier] of the constant.
* The value is the PHP values that is equivalent to the constant's IDL
  value, as defined in the [type section above].

### Operations

For each [operation] defined on the IDL [interface], there must be a
corresponding method declared on the PHP interface with the following
properties:

* The method has `public` visibility.
* The return type of the method is the PHP type that corresponds to
  the operation [return
  type](https://heycam.github.io/webidl/#dfn-return-type), according
  to the [type section above].
* The name of the method is the [PHP Escaped] [identifier] of the
  operation.  The [Overloads] extended attribute is ignored, as PHP
  does not have method overloading in the sense meant by the IDL spec.
* The method has an argument for each argument on the operation, with
  PHP types corresponding to the type of each IDL argument type as
  defined in the [type section above].  PHP [variable-length argument
  lists](https://www.php.net/manual/en/functions.arguments.php#functions.variable-arg-list)
  will be used where the IDL method argument uses the Variadic
  extended attribute.

In addition, the method should have a throws clause specifying all of
the PHP exception classes that correspond to the [IDL exceptions] that
are listed in the Raises clause of the operation, and no others.

XXX: PHP distinguishes between class constants (`Foo::x`), properties
(`Foo::$x`), and methods (`Foo::x()`) defined in the same class, so no
further name disambiguation seems to be necessary in case of IDL
conflicts.  If there is further name mangling required to resolve
conflicts, that would be described here.

### Attributes

For each [attribute] defined on the IDL [interface], there must be a
corresponding *getter method* declared on the PHP interface with the
following properties:

* The method has `public` visibility.
* The return type of the method is the PHP type that corresponds to
  the attribute type, according to the rules in the [type section
  above].
* The tentative name of the method is `get`, followed by the first
  character of the [identifier] of the attribute uppercased (as if
  passed to the
  (`strtoupper`)[https://www.php.net/manual/en/function.strtoupper]
  function), followed by the remaining characters from the identifier
  of the attribute, and then [PHP Escaped].  If the resulting
  tentative name is not the same as a constant or method declared on
  the PHP interface, it shall be the name of the method.  Otherwise,
  it will be prefixed by `idl_` and the smallest number of U+005F LOW
  LINE ("_") characters required to make the name not equal to the
  name of a constant or method declared on the PHP interface.
* The method has no arguments.

In addition, the method should have a `throws` clause specifying all
of the PHP exception classes that correspond to the [IDL exceptions]
that are listed in the `GetRaises` clause of the attribute, and no
others.

For each [attribute] defined on the IDL [interface] that is not [read
only], there must be a corresponding *setter method* declared on the
PHP interface with the following properties:

* The method has `public` visibility.
* The return type of the method is `void`.
* The tentative name of the method is `set`, followed by the first
  character of the [identifier] of the attribute uppercased (as if
  passed to the
  (`strtoupper`)[https://www.php.net/manual/en/function.strtoupper]
  function), followed by the remaining characters from the identifier
  of the attribute, and then [PHP Escaped].  If the resulting
  tentative name is not the same as a constant or method declared on
  the PHP interface, it shall be the name of the method.  Otherwise,
  it will be prefixed by `idl_` and the smallest number of U+005F LOW
  LINE ("_") characters required to make the name not equal to the
  name of a constant or method declared on the PHP interface.
* The method has a single argument whose type is the PHP type that
  corresponds to the attribute type, according to the rules in the
  [type section above].

In addition, the method should have a `throws` clause specifying all
of the PHP exception classes that correspond to the [IDL exceptions]
that are listed in the `SetRaises` clause of the attribute, and no
others.

For each [attribute] defined on the IDL [interface] that is [read
only] and is declared with the [`PutForwards`] [extended attribute],
there must be a corresponding setter method declared on the PHP
interface with the following properties:

* The method has `public` visibility.
* The return type of the method is `void`.
* The tentative name of the method is `set`, followed by the first
  character of the [identifier] of the attribute uppercased (as if
  passed to the
  (`strtoupper`)[https://www.php.net/manual/en/function.strtoupper]
  function), followed by the remaining characters from the identifier
  of the attribute, and then [PHP Escaped].  If the resulting
  tentative name is not the same as a constant or method declared on
  the PHP interface, it shall be the name of the method.  Otherwise,
  it will be prefixed by `idl_` and the smallest number of U+005F LOW
  LINE ("_") characters required to make the name not equal to the
  name of a constant or method declared on the PHP interface.
* The method has a single argument whose type is the PHP type that
  corresponds to the type of the attribute identified by the
  [`PutForwards`] extended attribute on the interface type that this
  attribute is declared to be of, according to the rules in the [type
  section above].

In addition, the method should have a `throws` clause specifying all
of the PHP exception classes that correspond to the [IDL exceptions]
that are listed in the `SetRaises` clause of the attribute identified
by the [`PutForwards`] extended attribute on the interface type that
this attribute is declared to be of, and no others.

#### Attribute compatibility

In every interface with attributes, the PHP [magic
methods](https://www.php.net/manual/en/language.oop5.magic.php)
`__get()`, `__set()`, `__isset()`, and `__unset()` should be defined
with the following behavior:
* The `__get($name)` method should invoke and return the value of the
PHP getter method for `$name`.
* The `__set($name, $value)` method should invoke the PHP setter method
for `$name` if the attribute, unless the attribute is declared [read only],
in which case an appropriate exception should be thrown.
* The `__isset($name)` method should invoke `__get($name)` and return `false`
if the value returned is `null`, or `true` otherwise.
* The `__unset($name)` method should invoke `__set($name, null)`.

The behavior of `__get` and `__set` if `$name` is not a valid attribute
of the interface is undefined.  Implementations may elect to throw an
exception, or else to permit creation of dynamic properties by storing
and fetching values indexed by these names in an auxilliary array.

XXX: should we be specific here and choose one behavior?

*The use of these compatibility methods is not recommended in
performance-critical code*.  They are provided to provide
compatibliity with existing code written to use the built-in
`\DOMDocument` interface and for convience, but the invocation of
'magic methods' has a steep performance cost in PHP.

XXX: ensure that there is no cost associated with simply *defining* the
magic methods, even if they are not used.

XXX: We will probably define a standard PHP trait to implement this
functionality.

## Objects implementing interfaces

A PHP object that implements an IDL [interface] must be of a PHP class
that implements the PHP interface that corresponds to the IDL
interface.

## Exceptions

A conforming PHP implementation must have a PHP class corresponding to
every [IDL exception] that is supported, whose name is the [PHP Escaped]
[identifier] of the IDL exception and which resides in the PHP
namespace corresponding to the exception’s [enclosing
module](https://www.w3.org/TR/WebIDL-1/#dfn-enclosing-module).

The PHP class must have only the `public` modifier, and be declared to extend
the PHP base class `\Exception`.

### Exception constants

For each [constant] defined on the [enclosing module] of the [IDL
exception], where that module has been declared with the
`[ExceptionConsts]` [extended attribute], there must be a
corresponding constant declared on the PHP class with the following
properties:

* The constant has `public` visibility.
* The type of the constant is the PHP type that corresponds to the
  type of the IDL constant, according to the rules in the [type
  section above].
* The name of the constant is the [PHP Escaped] [identifier] of the
  constant.
* The value of the constant is the PHP value that is equivalent to the
  constant’s IDL value, according to the rules in the [type section
  above].

### Exception members

For each [exception member] defined on the [IDL exception], there must
be a corresponding instance variable declared on the PHP class with
the following properties:

* The instance variable has `public` visibility.
* The type of the instance variable is the PHP type that corresponds
  to the type of the IDL exception member, according to the rules in
  the [type section above].
* The name of the instance variable is the [PHP Escaped] [identifier]
  of the exception member.
* The instance variable is not declared with an initializer.

# Compatibility

XXX: In this section we should describe some specific differences between
the binding as described above, and the names resulting from the
`\DOMDocument` classes, on one hand, and the JavaScript binding, on the
other.

[PHP Escaped]: #Names
[type section above]: #Types
[operation]: https://www.w3.org/TR/WebIDL-1/#idl-operations
[identifier]: https://www.w3.org/TR/WebIDL-1/#idl-names
[interface]: https://www.w3.org/TR/WebIDL-1#idl-interfaces
[attribute]: https://www.w3.org/TR/WebIDL-1/#idl-attributes
[IDL exception]: https://www.w3.org/TR/WebIDL-1/#idl-exceptions
[IDL exceptions]: https://www.w3.org/TR/WebIDL-1/#idl-exceptions
[read only]: https://www.w3.org/TR/WebIDL-1/#dfn-read-only
[extended attribute]: https://www.w3.org/TR/WebIDL-1/#dfn-extended-attribute
[`PutForwards`]: https://www.w3.org/TR/WebIDL-1/#PutForwards
[constant]: https://www.w3.org/TR/WebIDL-1/#dfn-constant
[exception member]: https://www.w3.org/TR/WebIDL-1/#exception-member
