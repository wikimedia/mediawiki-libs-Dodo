# Change Log

## Dodo x.x.x (not yet released)

## Dodo 0.6.0 (2025-06-05)
* Update dependencies.
  * Require PHPUnit 9.6.21, mediawiki-phan-config 0.15.1,
    mediawiki-codesniffer 47.0.0
  * Require wikimedia/remex-html >= 5.0.0.
  * Require wikimedia/zest-css >= 4.0.0.

## Dodo 0.5.0 (2024-11-09)
* Drop PHP 7.2 and PHP 7.3 support.
* Move to PHPUnit 9.
* Update dependencies.
  * Require IdleDOM 2.x, Remex 4.x, and Zest 3.x.

## Dodo 0.4.0 (2021-10-25)
* Allow use of wikimedia/remex-html 3.0.0.
* Use 'code point' units for CharacterData methods, not 'UTF-16 code units'
  * This is compatible with modern PHP, which has used 'code point' units
    since PHP5.
* Window now implements the proper IDLeDOM interfaces.
* Dodo classes now use @phan-forbid-undeclared-magic-properties.
  * This may cause new phan warnings in user code.
* Test case generation improvements.

## Dodo 0.3.0 (2021-08-08)
* Update to wikimedia/zest-css 2.0.1.
* Update to wikimedia/idle-dom 0.10.0.
* Update to wikimedia/remex-html 2.3.2.
* Implement the following non-standard methods for PHP compatibility:
  * Document::loadHTML()
  * Document::loadXML()
  * Document::saveHTML()
  * Document::saveXML()
  * DocumentFragment::appendXML()
  * Element::setIdAttribute() (stubbed out)
* Allow a final string argument to Document::createElement() and
  Document::createElementNS() for PHP compatibility.
* Bug fixes to element attribute maintenance; improved compliance with
  DOM specifications.

## Dodo 0.2.0 (2021-07-26)
* Update to IDLeDOM 0.7.2.
* Fix doctype creation and "significant whitespace" handling in DOMParser.
* Ensure that Document::documentElement is always populated.
* Ensure that Document::documentElement matches FilteredElementList where
  appropriate.
* EXPERIMENTAL: Added Node::getExtensionData() and Node::setExtensionData()
  methods to allow end-users to associate additional non-spec data off of
  Nodes.
* Implement DocumentFragment::querySelector(), ::querySelectorAll(), and
  ::getElementById().
* Implement Node::getNodePath() for PHP compatibility.
* Don't export .phan directory in composer package.

## Dodo 0.1.0 (2021-07-04)
Initial release.
