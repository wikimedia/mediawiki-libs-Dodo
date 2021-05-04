<?php 
namespace Wikimedia\Dodo\Tests\Wpt\Dom;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\Wpt\Harness\WptTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Element-matches-namespaced-elements.html.
class ElementMatchesNamespacedElementsTest extends WptTestHarness
{
    public function testElementMatchesNamespacedElements()
    {
        $this->doc = $this->loadWptHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/Element-matches-namespaced-elements.html');
        foreach (['matches', 'webkitMatchesSelector'] as $method => $___) {
            $this->assertTest(function () use(&$method) {
                $this->assertTrueData($this->doc->createElementNS('', 'element')[$method]('element'));
            }, "empty string namespace, {$method}");
            $this->assertTest(function () use(&$method) {
                $this->assertTrueData($this->doc->createElementNS('urn:ns', 'h')[$method]('h'));
            }, "has a namespace, {$method}");
            $this->assertTest(function () use(&$method) {
                $this->assertTrueData($this->doc->createElementNS('urn:ns', 'h')[$method]('*|h'));
            }, "has a namespace, *|, {$method}");
        }
    }
}
