<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom\Nodes;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/ParentNode-querySelectors-namespaces.html.
class ParentNodeQuerySelectorsNamespacesTest extends WPTTestHarness
{
    public function testParentNodeQuerySelectorsNamespaces()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/ParentNode-querySelectors-namespaces.html');
        $el = $this->doc->getElementById('thesvg');
        $this->wptAssertEquals($this->doc->querySelector('[*|href]'), $el);
        $this->wptAssertArrayEquals($this->doc->querySelectorAll('[*|href]'), [$el]);
        $this->done();
    }
}
