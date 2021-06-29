<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Attr;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Element-setAttribute-crbug-1138487.html.
class ElementSetAttributeCrbug1138487Test extends WPTTestHarness
{
    public function testElementSetAttributeCrbug1138487()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/Element-setAttribute-crbug-1138487.html');
        // Regression test for crbug.com/1138487.
        //
        // It was possible for a non-ASCII-lowercase string to be used when inserting
        // into the attribute collection if a hashtable encountered it during probing
        // while looking for the ASCII-lowercase equivalent.
        //
        // This caused such a string to be illegally used as an attribute name, thus
        // causing inconsistent behavior in future attribute lookup.
        $this->assertTest(function () {
            $el = $this->doc->createElement('div');
            $el->setAttribute('labelXQL', 'abc');
            $el->setAttribute('_valueXQL', 'def');
            $this->assertEqualsData($el->getAttribute('labelXQL'), 'abc');
            $this->assertEqualsData($el->getAttribute('labelxql'), 'abc');
            $this->assertEqualsData($el->getAttribute('_valueXQL'), 'def');
            $this->assertEqualsData($el->getAttribute('_valuexql'), 'def');
        }, 'Attributes first seen in mixed ASCII case should not be corrupted.');
    }
}
