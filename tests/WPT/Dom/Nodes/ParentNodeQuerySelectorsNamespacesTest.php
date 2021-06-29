<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\WPT\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/ParentNode-querySelectors-namespaces.html.
class ParentNodeQuerySelectorsNamespacesTest extends WPTTestHarness
{
    public function testParentNodeQuerySelectorsNamespaces()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/ParentNode-querySelectors-namespaces.html');
        $el = $this->doc->getElementById('thesvg');
        $this->assertEqualsData($this->doc->querySelector('[*|href]'), $el);
        $this->assertArrayEqualsData($this->doc->querySelectorAll('[*|href]'), [$el]);
        $this->done();
    }
}
