<?php 
namespace Wikimedia\Dodo\Tests\Wpt\Dom;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Attr;
use Wikimedia\Dodo\Tests\Wpt\Harness\WptTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Element-removeAttributeNS.html.
class ElementRemoveAttributeNSTest extends WptTestHarness
{
    public function attrIs($attr, $v, $ln, $ns, $p, $n)
    {
        $this->assertEqualsData($attr->value, $v);
        $this->assertEqualsData($attr->nodeValue, $v);
        $this->assertEqualsData($attr->textContent, $v);
        $this->assertEqualsData($attr->localName, $ln);
        $this->assertEqualsData($attr->namespaceURI, $ns);
        $this->assertEqualsData($attr->prefix, $p);
        $this->assertEqualsData($attr->name, $n);
        $this->assertEqualsData($attr->nodeName, $n);
        $this->assertEqualsData($attr->specified, true);
    }
    public function attributesAre($el, $l)
    {
        for ($i = 0, $il = count($l); $i < $il; $i++) {
            $this->attrIs($el->attributes[$i], $l[$i][1], $l[$i][0], count($l[$i]) < 3 ? null : $l[$i][2], null, $l[$i][0]);
            $this->assertEqualsData($el->attributes[$i]->ownerElement, $el);
        }
    }
    public function testElementRemoveAttributeNS()
    {
        $this->source_file = 'vendor/web-platform-tests/wpt/dom/nodes/Element-removeAttributeNS.html';
        $XML = 'http://www.w3.org/XML/1998/namespace';
        $this->assertTest(function () use(&$XML) {
            $el = $this->doc->createElement('foo');
            $el->setAttributeNS($XML, 'a:bb', 'pass');
            $this->attrIs($el->attributes[0], 'pass', 'bb', $XML, 'a', 'a:bb');
            $el->removeAttributeNS($XML, 'a:bb');
            $this->assertEqualsData(count($el->attributes), 1);
            $this->attrIs($el->attributes[0], 'pass', 'bb', $XML, 'a', 'a:bb');
        }, 'removeAttributeNS should take a local name.');
    }
}
