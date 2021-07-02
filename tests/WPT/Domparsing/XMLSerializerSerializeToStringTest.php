<?php 
namespace Wikimedia\Dodo\Tests\WPT\Domparsing;
use Wikimedia\Dodo\DocumentFragment;
use Wikimedia\Dodo\Document;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Attr;
use Wikimedia\Dodo\DOMParser;
use Wikimedia\Dodo\XMLSerializer;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/domparsing/XMLSerializer-serializeToString.html.
class XMLSerializerSerializeToStringTest extends WPTTestHarness
{
    public function createXmlDoc()
    {
        $input = '<?xml version="1.0" encoding="UTF-8"?><root><child1>value1</child1></root>';
        $parser = new DOMParser();
        return $parser->parseFromString($input, 'text/xml');
    }
    public function parse($xmlString)
    {
        return (new DOMParser())->parseFromString($xmlString, 'text/xml')->documentElement;
    }
    public function serialize($node)
    {
        return (new XMLSerializer())->serializeToString($node);
    }
    public function testXMLSerializerSerializeToString()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/domparsing/XMLSerializer-serializeToString.html');
        $XMLNS_URI = 'http://www.w3.org/2000/xmlns/';
        $this->assertTest(function () {
            $root = $this->createXmlDoc()->documentElement;
            $this->wptAssertEquals($this->serialize($root), '<root><child1>value1</child1></root>');
        }, 'check XMLSerializer.serializeToString method could parsing xmldoc to string');
        $this->assertTest(function () {
            $root = $this->parse('<html><head></head><body><div></div><span></span></body></html>');
            $this->wptAssertEquals($this->serialize($root->ownerDocument), '<html><head/><body><div/><span/></body></html>');
        }, 'check XMLSerializer.serializeToString method could parsing document to string');
        $this->assertTest(function () {
            $root = $this->createXmlDoc()->documentElement;
            $element = $root->ownerDocument->createElementNS('urn:foo', 'another');
            $child1 = $root->firstChild;
            $root->replaceChild($element, $child1);
            $element->appendChild($child1);
            $this->wptAssertEquals($this->serialize($root), '<root><another xmlns="urn:foo"><child1 xmlns="">value1</child1></another></root>');
        }, 'Check if the default namespace is correctly reset.');
        $this->assertTest(function () {
            $root = $this->parse('<root xmlns="urn:bar"><outer xmlns=""><inner>value1</inner></outer></root>');
            $this->wptAssertEquals($this->serialize($root), '<root xmlns="urn:bar"><outer xmlns=""><inner>value1</inner></outer></root>');
        }, 'Check if there is no redundant empty namespace declaration.');
        // https://github.com/w3c/DOM-Parsing/issues/47
        $this->assertTest(function () {
            $this->wptAssertEquals($this->serialize($this->parse('<root><child xmlns=""/></root>')), '<root><child/></root>');
            $this->wptAssertEquals($this->serialize($this->parse('<root xmlns=""><child xmlns=""/></root>')), '<root><child/></root>');
            $this->wptAssertEquals($this->serialize($this->parse('<root xmlns="u1"><child xmlns="u1"/></root>')), '<root xmlns="u1"><child/></root>');
        }, 'Check if redundant xmlns="..." is dropped.');
        $this->assertTest(function () use(&$XMLNS_URI) {
            $root = $this->parse('<root xmlns="uri1"/>');
            $child = $root->ownerDocument->createElement('child');
            $child->setAttributeNS($XMLNS_URI, 'xmlns', 'FAIL1');
            $root->appendChild($child);
            $child2 = $root->ownerDocument->createElementNS('uri2', 'child2');
            $child2->setAttributeNS($XMLNS_URI, 'xmlns', 'FAIL2');
            $root->appendChild($child2);
            $child3 = $root->ownerDocument->createElementNS('uri1', 'child3');
            $child3->setAttributeNS($XMLNS_URI, 'xmlns', 'FAIL3');
            $root->appendChild($child3);
            $child4 = $root->ownerDocument->createElementNS('uri4', 'child4');
            $child4->setAttributeNS($XMLNS_URI, 'xmlns', 'uri4');
            $root->appendChild($child4);
            $child5 = $root->ownerDocument->createElement('child5');
            $child5->setAttributeNS($XMLNS_URI, 'xmlns', '');
            $root->appendChild($child5);
            $this->wptAssertEquals($this->serialize($root), '<root xmlns="uri1"><child xmlns=""/><child2 xmlns="uri2"/><child3/><child4 xmlns="uri4"/><child5 xmlns=""/></root>');
        }, 'Check if inconsistent xmlns="..." is dropped.');
        $this->assertTest(function () {
            $root = $this->parse('<r xmlns:xx="uri"></r>');
            $root->setAttributeNS('uri', 'name', 'v');
            $this->wptAssertEquals($this->serialize($root), '<r xmlns:xx="uri" xx:name="v"/>');
            $root2 = $this->parse('<r xmlns:xx="uri"><b/></r>');
            $child = $root2->firstChild;
            $child->setAttributeNS('uri', 'name', 'v');
            $this->wptAssertEquals($this->serialize($root2), '<r xmlns:xx="uri"><b xx:name="v"/></r>');
            $root3 = $this->parse('<r xmlns:x0="uri" xmlns:x2="uri"><b xmlns:x1="uri"/></r>');
            $child3 = $root3->firstChild;
            $child3->setAttributeNS('uri', 'name', 'v');
            $this->wptAssertEquals($this->serialize($root3), '<r xmlns:x0="uri" xmlns:x2="uri"><b xmlns:x1="uri" x1:name="v"/></r>', 'Should choose the nearest prefix');
        }, 'Check if an attribute with namespace and no prefix is serialized with the nearest-declared prefix');
        // https://github.com/w3c/DOM-Parsing/issues/45
        $this->assertTest(function () {
            $root = $this->parse('<el1 xmlns:p="u1" xmlns:q="u1"><el2 xmlns:q="u2"/></el1>');
            $root->firstChild->setAttributeNS('u1', 'name', 'v');
            $this->wptAssertEquals($this->serialize($root), '<el1 xmlns:p="u1" xmlns:q="u1"><el2 xmlns:q="u2" q:name="v"/></el1>');
        }, 'Check if an attribute with namespace and no prefix is serialized with the nearest-declared prefix even if the prefix is assigned to another namespace.');
        $this->assertTest(function () {
            $root = $this->parse('<r xmlns:xx="uri"></r>');
            $root->setAttributeNS('uri', 'p:name', 'v');
            $this->wptAssertEquals($this->serialize($root), '<r xmlns:xx="uri" xx:name="v"/>');
            $root2 = $this->parse('<r xmlns:xx="uri"><b/></r>');
            $child = $root2->firstChild;
            $child->setAttributeNS('uri', 'p:name', 'value');
            $this->wptAssertEquals($this->serialize($root2), '<r xmlns:xx="uri"><b xx:name="value"/></r>');
        }, 'Check if the prefix of an attribute is replaced with another existing prefix mapped to the same namespace URI.');
        // https://github.com/w3c/DOM-Parsing/issues/29
        $this->assertTest(function () {
            $root = $this->parse('<r xmlns:xx="uri"></r>');
            $root->setAttributeNS('uri2', 'p:name', 'value');
            $this->wptAssertEquals($this->serialize($root), '<r xmlns:xx="uri" xmlns:ns1="uri2" ns1:name="value"/>');
        }, 'Check if the prefix of an attribute is NOT preserved in a case where neither its prefix nor its namespace URI is not already used.');
        $this->assertTest(function () {
            $root = $this->parse('<r xmlns:xx="uri"></r>');
            $root->setAttributeNS('uri2', 'xx:name', 'value');
            $this->wptAssertEquals($this->serialize($root), '<r xmlns:xx="uri" xmlns:ns1="uri2" ns1:name="value"/>');
        }, 'Check if the prefix of an attribute is replaced with a generated one in a case where the prefix is already mapped to a different namespace URI.');
        $this->assertTest(function () {
            $root = $this->parse('<root />');
            $root->setAttribute('attr', "\t");
            $this->wptAssertInArray($this->serialize($root), ['<root attr="&#9;"/>', '<root attr="&#x9;"/>']);
            $root->setAttribute('attr', "\n");
            $this->wptAssertInArray($this->serialize($root), ['<root attr="&#xA;"/>', '<root attr="&#10;"/>']);
            $root->setAttribute('attr', "\r");
            $this->wptAssertInArray($this->serialize($root), ['<root attr="&#xD;"/>', '<root attr="&#13;"/>']);
        }, 'check XMLSerializer.serializeToString escapes attribute values for roundtripping');
        $this->assertTest(function () use(&$XMLNS_URI) {
            $root = (new Document())->createElement('root');
            $root->setAttributeNS('uri1', 'p:foobar', 'value1');
            $root->setAttributeNS($XMLNS_URI, 'xmlns:p', 'uri2');
            $this->wptAssertEquals($this->serialize($root), '<root xmlns:ns1="uri1" ns1:foobar="value1" xmlns:p="uri2"/>');
        }, 'Check if attribute serialization takes into account of following xmlns:* attributes');
        $this->assertTest(function () {
            $root = $this->parse('<root xmlns:p="uri1"><child/></root>');
            $root->firstChild->setAttributeNS('uri2', 'p:foobar', 'v');
            $this->wptAssertEquals($this->serialize($root), '<root xmlns:p="uri1"><child xmlns:ns1="uri2" ns1:foobar="v"/></root>');
        }, 'Check if attribute serialization takes into account of the same prefix declared in an ancestor element');
        $this->assertTest(function () {
            $this->wptAssertEquals($this->serialize($this->parse('<root><child/></root>')), '<root><child/></root>');
            $this->wptAssertEquals($this->serialize($this->parse('<root xmlns="u1"><p:child xmlns:p="u1"/></root>')), '<root xmlns="u1"><child xmlns:p="u1"/></root>');
        }, 'Check if start tag serialization drops element prefix if the namespace is same as inherited default namespace.');
        $this->assertTest(function () {
            $root = $this->parse('<root xmlns:p1="u1"><child xmlns:p2="u1"/></root>');
            $child2 = $root->ownerDocument->createElementNS('u1', 'child2');
            $root->firstChild->appendChild($child2);
            $this->wptAssertEquals($this->serialize($root), '<root xmlns:p1="u1"><child xmlns:p2="u1"><p2:child2/></child></root>');
        }, 'Check if start tag serialization finds an appropriate prefix.');
        $this->assertTest(function () use(&$XMLNS_URI) {
            $root = (new Document())->createElementNS('uri1', 'p:root');
            $root->setAttributeNS($XMLNS_URI, 'xmlns:p', 'uri2');
            $this->wptAssertEquals($this->serialize($root), '<ns1:root xmlns:ns1="uri1" xmlns:p="uri2"/>');
        }, 'Check if start tag serialization takes into account of its xmlns:* attributes');
        $this->assertTest(function () use(&$XMLNS_URI) {
            $root = (new Document())->createElement('root');
            $root->setAttributeNS($XMLNS_URI, 'xmlns:p', 'uri2');
            $child = $root->ownerDocument->createElementNS('uri1', 'p:child');
            $root->appendChild($child);
            $this->wptAssertEquals($this->serialize($root), '<root xmlns:p="uri2"><p:child xmlns:p="uri1"/></root>');
        }, 'Check if start tag serialization applied the original prefix even if it is declared in an ancestor element.');
        // https://github.com/w3c/DOM-Parsing/issues/52
        $this->assertTest(function () {
            $this->wptAssertEquals($this->serialize($this->parse('<root xmlns:x="uri1"><table xmlns="uri1"></table></root>')), '<root xmlns:x="uri1"><x:table xmlns="uri1"/></root>');
        }, 'Check if start tag serialization does NOT apply the default namespace if its namespace is declared in an ancestor.');
        $this->assertTest(function () {
            $root = $this->parse('<root><child1/><child2/></root>');
            $root->firstChild->setAttributeNS('uri1', 'attr1', 'value1');
            $root->firstChild->setAttributeNS('uri2', 'attr2', 'value2');
            $root->lastChild->setAttributeNS('uri3', 'attr3', 'value3');
            $this->wptAssertEquals($this->serialize($root), '<root><child1 xmlns:ns1="uri1" ns1:attr1="value1" xmlns:ns2="uri2" ns2:attr2="value2"/><child2 xmlns:ns3="uri3" ns3:attr3="value3"/></root>');
        }, 'Check if generated prefixes match to "ns${index}".');
        // https://github.com/w3c/DOM-Parsing/issues/44
        // According to 'DOM Parsing and Serialization' draft as of 2018-12-11,
        // 'generate a prefix' result can conflict with an existing xmlns:ns* declaration.
        $this->assertTest(function () {
            $root = $this->parse('<root xmlns:ns2="uri2"><child xmlns:ns1="uri1"/></root>');
            $root->firstChild->setAttributeNS('uri3', 'attr1', 'value1');
            $this->wptAssertEquals($this->serialize($root), '<root xmlns:ns2="uri2"><child xmlns:ns1="uri1" xmlns:ns1="uri3" ns1:attr1="value1"/></root>');
        }, 'Check if "ns1" is generated even if the element already has xmlns:ns1.');
        $this->assertTest(function () {
            $root = (new Document())->createElement('root');
            $root->setAttributeNS('http://www.w3.org/1999/xlink', 'href', 'v');
            $this->wptAssertEquals($this->serialize($root), '<root xmlns:ns1="http://www.w3.org/1999/xlink" ns1:href="v"/>');
            $root2 = (new Document())->createElement('root');
            $root2->setAttributeNS('http://www.w3.org/1999/xlink', 'xl:type', 'v');
            $this->wptAssertEquals($this->serialize($root2), '<root xmlns:xl="http://www.w3.org/1999/xlink" xl:type="v"/>');
        }, 'Check if no special handling for XLink namespace unlike HTML serializer.');
        $this->assertTest(function () {
            $root = new DocumentFragment($this->doc);
            $root->append($this->doc->createElement('div'));
            $root->append($this->doc->createElement('span'));
            $this->wptAssertEquals($this->serialize($root), '<div xmlns="http://www.w3.org/1999/xhtml"></div><span xmlns="http://www.w3.org/1999/xhtml"></span>');
        }, 'Check if document fragment serializes.');
    }
}
