<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\WPT\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Element-tagName.html.
class ElementTagNameTest extends WPTTestHarness
{
    public function testElementTagName()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/Element-tagName.html');
        $this->assertTest(function () {
            $HTMLNS = 'http://www.w3.org/1999/xhtml';
            $this->assertEqualsData($this->doc->createElementNS($HTMLNS, 'I')->tagName, 'I');
            $this->assertEqualsData($this->doc->createElementNS($HTMLNS, 'i')->tagName, 'I');
            $this->assertEqualsData($this->doc->createElementNS($HTMLNS, 'x:b')->tagName, 'X:B');
        }, 'tagName should upper-case for HTML elements in HTML documents.');
        $this->assertTest(function () {
            $SVGNS = 'http://www.w3.org/2000/svg';
            $this->assertEqualsData($this->doc->createElementNS($SVGNS, 'svg')->tagName, 'svg');
            $this->assertEqualsData($this->doc->createElementNS($SVGNS, 'SVG')->tagName, 'SVG');
            $this->assertEqualsData($this->doc->createElementNS($SVGNS, 's:svg')->tagName, 's:svg');
            $this->assertEqualsData($this->doc->createElementNS($SVGNS, 's:SVG')->tagName, 's:SVG');
            $this->assertEqualsData($this->doc->createElementNS($SVGNS, 'textPath')->tagName, 'textPath');
        }, 'tagName should not upper-case for SVG elements in HTML documents.');
        $this->assertTest(function () {
            $el2 = $this->doc->createElementNS('http://example.com/', 'mixedCase');
            $this->assertEqualsData($el2->tagName, 'mixedCase');
        }, 'tagName should not upper-case for other non-HTML namespaces');
        $this->assertTest(function () {
            if (isset($this->window['DOMParser'])) {
                $xmlel = $this->parseFromString('<div xmlns="http://www.w3.org/1999/xhtml">Test</div>', 'text/xml')->documentElement;
                $this->assertEqualsData($xmlel->tagName, 'div', 'tagName should be lowercase in XML');
                $htmlel = $this->doc->importNode($xmlel, true);
                $this->assertEqualsData($htmlel->tagName, 'DIV', 'tagName should be uppercase in HTML');
            }
        }, 'tagName should be updated when changing ownerDocument');
        $this->assertTest(function () {
            $xmlel = $this->doc->implementation->createDocument('http://www.w3.org/1999/xhtml', 'div', null)->documentElement;
            $this->assertEqualsData($xmlel->tagName, 'div', 'tagName should be lowercase in XML');
            $htmlel = $this->doc->importNode($xmlel, true);
            $this->assertEqualsData($htmlel->tagName, 'DIV', 'tagName should be uppercase in HTML');
        }, 'tagName should be updated when changing ownerDocument (createDocument without prefix)');
        $this->assertTest(function () {
            $xmlel = $this->doc->implementation->createDocument('http://www.w3.org/1999/xhtml', 'foo:div', null)->documentElement;
            $this->assertEqualsData($xmlel->tagName, 'foo:div', 'tagName should be lowercase in XML');
            $htmlel = $this->doc->importNode($xmlel, true);
            $this->assertEqualsData($htmlel->tagName, 'FOO:DIV', 'tagName should be uppercase in HTML');
        }, 'tagName should be updated when changing ownerDocument (createDocument with prefix)');
    }
}
