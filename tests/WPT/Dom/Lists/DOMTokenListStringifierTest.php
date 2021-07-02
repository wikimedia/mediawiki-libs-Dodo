<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom\Lists;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Attr;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/lists/DOMTokenList-stringifier.html.
class DOMTokenListStringifierTest extends WPTTestHarness
{
    public function testDOMTokenListStringifier()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/lists/DOMTokenList-stringifier.html');
        $this->assertTest(function () {
            $this->wptAssertEquals(strval($this->doc->createElement('span')->classList), '', 'String(classList) should return the empty list for an undefined class attribute');
            $span = $this->doc->querySelector('span');
            $this->wptAssertEquals($span->getAttribute('class'), '   a  a b ', 'getAttribute should return the literal value');
            $this->wptAssertEquals($span->className, '   a  a b ', 'className should return the literal value');
            $this->wptAssertEquals(strval($span->classList), '   a  a b ', 'String(classList) should return the literal value');
            $this->wptAssertEquals($span->classList, '   a  a b ', 'classList.toString() should return the literal value');
            $this->wptAssertClassString($span->classList, 'DOMTokenList');
        });
    }
}
