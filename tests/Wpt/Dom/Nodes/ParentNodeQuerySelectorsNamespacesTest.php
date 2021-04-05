<?php 
namespace Wikimedia\Dodo\Tests\Wpt\Dom;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\Wpt\Harness\WptTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/ParentNode-querySelectors-namespaces.html.
class ParentNodeQuerySelectorsNamespacesTest extends WptTestHarness
{
    public function testParentNodeQuerySelectorsNamespaces()
    {
        $this->source_file = 'vendor/web-platform-tests/wpt/dom/nodes/ParentNode-querySelectors-namespaces.html';
        $el = $this->doc->getElementById('thesvg');
        $this->assertEqualsData($this->doc->querySelector('[*|href]'), $el);
        $this->assertArrayEqualsData($this->doc->querySelectorAll('[*|href]'), [$el]);
        $this->done();
    }
}
