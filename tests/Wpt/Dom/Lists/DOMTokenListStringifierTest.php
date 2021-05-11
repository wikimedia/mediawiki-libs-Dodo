<?php 
namespace Wikimedia\Dodo\Tests\Wpt\Dom;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Attr;
use Wikimedia\Dodo\Tests\Wpt\Harness\WptTestHarness;
// @see vendor/web-platform-tests/wpt/dom/lists/DOMTokenList-stringifier.html.
class DOMTokenListStringifierTest extends WptTestHarness
{
    public function testDOMTokenListStringifier()
    {
        $this->doc = $this->loadWptHtmlFile('vendor/web-platform-tests/wpt/dom/lists/DOMTokenList-stringifier.html');
        $this->assertTest(function () {
            $this->assertEqualsData(strval($this->doc->createElement('span')->classList), '', 'String(classList) should return the empty list for an undefined class attribute');
            $span = $this->doc->querySelector('span');
            $this->assertEqualsData($span->getAttribute('class'), '   a  a b ', 'getAttribute should return the literal value');
            $this->assertEqualsData($span->className, '   a  a b ', 'className should return the literal value');
            $this->assertEqualsData(strval($span->classList), '   a  a b ', 'String(classList) should return the literal value');
            $this->assertEqualsData($span->classList, '   a  a b ', 'classList.toString() should return the literal value');
            $this->assertClassStringData($span->classList, 'DOMTokenList');
        });
    }
}