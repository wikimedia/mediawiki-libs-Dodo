<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/lists/DOMTokenList-Iterable.html.
class DOMTokenListIterableTest extends WPTTestHarness
{
    public function testDOMTokenListIterable()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/lists/DOMTokenList-Iterable.html');
        $elementClasses = null;
        // setup()
        $elementClasses = $this->doc->querySelector('span')->classList;
        $this->assertTest(function () use(&$elementClasses) {
            $this->assertTrueData(isset($elementClasses['length']));
        }, 'DOMTokenList has length method.');
        $this->assertTest(function () use(&$elementClasses) {
            $this->assertTrueData(isset($elementClasses['values']));
        }, 'DOMTokenList has values method.');
        $this->assertTest(function () use(&$elementClasses) {
            $this->assertTrueData(isset($elementClasses['entries']));
        }, 'DOMTokenList has entries method.');
        $this->assertTest(function () use(&$elementClasses) {
            $this->assertTrueData(isset($elementClasses['forEach']));
        }, 'DOMTokenList has forEach method.');
        $this->assertTest(function () use(&$elementClasses) {
            // $this->assertTrueData(isset($elementClasses[Symbol::iterator]));
        }, 'DOMTokenList has Symbol.iterator.');
        $this->assertTest(function () use(&$elementClasses) {
            $classList = [];
            foreach ($elementClasses as $className => $___) {
                $classList[] = $className;
            }
            $this->assertArrayEqualsData($classList, ['foo', 'Foo']);
        }, 'DOMTokenList is iterable via for-of loop.');
    }
}
