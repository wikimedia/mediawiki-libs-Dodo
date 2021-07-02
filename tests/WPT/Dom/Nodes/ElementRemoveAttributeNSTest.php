<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom\Nodes;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Attr;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Element-removeAttributeNS.html.
class ElementRemoveAttributeNSTest extends WPTTestHarness
{
    public function attrIs($attr, $v, $ln, $ns, $p, $n)
    {
        $this->wptAssertEquals($attr->value, $v);
        $this->wptAssertEquals($attr->nodeValue, $v);
        $this->wptAssertEquals($attr->textContent, $v);
        $this->wptAssertEquals($attr->localName, $ln);
        $this->wptAssertEquals($attr->namespaceURI, $ns);
        $this->wptAssertEquals($attr->prefix, $p);
        $this->wptAssertEquals($attr->name, $n);
        $this->wptAssertEquals($attr->nodeName, $n);
        $this->wptAssertEquals($attr->specified, true);
    }
    public function attributesAre($el, $l)
    {
        for ($i = 0, $il = count($l); $i < $il; $i++) {
            $this->attrIs($el->attributes[$i], $l[$i][1], $l[$i][0], count($l[$i]) < 3 ? null : $l[$i][2], null, $l[$i][0]);
            $this->wptAssertEquals($el->attributes[$i]->ownerElement, $el);
        }
    }
    public function testElementRemoveAttributeNS()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/Element-removeAttributeNS.html');
        $XML = 'http://www.w3.org/XML/1998/namespace';
        $this->assertTest(function () use(&$XML) {
            $el = $this->doc->createElement('foo');
            $el->setAttributeNS($XML, 'a:bb', 'pass');
            $this->attrIs($el->attributes[0], 'pass', 'bb', $XML, 'a', 'a:bb');
            $el->removeAttributeNS($XML, 'a:bb');
            $this->wptAssertEquals(count($el->attributes), 1);
            $this->attrIs($el->attributes[0], 'pass', 'bb', $XML, 'a', 'a:bb');
        }, 'removeAttributeNS should take a local name.');
    }
}
