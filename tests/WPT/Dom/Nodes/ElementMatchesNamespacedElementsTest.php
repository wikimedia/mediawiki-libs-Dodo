<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Element-matches-namespaced-elements.html.
class ElementMatchesNamespacedElementsTest extends WPTTestHarness
{
    public function testElementMatchesNamespacedElements()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/Element-matches-namespaced-elements.html');
        foreach (['matches', 'webkitMatchesSelector'] as $method => $___) {
            $this->assertTest(function () use(&$method) {
                $this->wptAssertTrue($this->doc->createElementNS('', 'element')->{$method}('element'));
            }, "empty string namespace, {$method}");
            $this->assertTest(function () use(&$method) {
                $this->wptAssertTrue($this->doc->createElementNS('urn:ns', 'h')->{$method}('h'));
            }, "has a namespace, {$method}");
            $this->assertTest(function () use(&$method) {
                $this->wptAssertTrue($this->doc->createElementNS('urn:ns', 'h')->{$method}('*|h'));
            }, "has a namespace, *|, {$method}");
        }
    }
}
