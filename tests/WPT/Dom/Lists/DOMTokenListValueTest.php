<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/lists/DOMTokenList-value.html.
class DOMTokenListValueTest extends WPTTestHarness
{
    public function testDOMTokenListValue()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/lists/DOMTokenList-value.html');
        $this->assertTest(function () {
            $this->assertEqualsData(strval($this->doc->createElement('span')->classList->value), '', 'classList.value should return the empty list for an undefined class attribute');
            $span = $this->doc->querySelector('span');
            $this->assertEqualsData($span->classList->value, '   a  a b ', 'value should return the literal value');
            $span->classList->value = ' foo bar foo ';
            $this->assertEqualsData($span->classList->value, ' foo bar foo ', 'assigning value should set the literal value');
            $this->assertEqualsData(count($span->classList), 2, 'length should be the number of tokens');
            $this->assertClassStringData($span->classList, 'DOMTokenList');
            $this->assertClassStringData($span->classList->value, 'String');
        });
    }
}
