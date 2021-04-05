<?php 
namespace Wikimedia\Dodo\Tests\Wpt\Dom;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\URL;
use Wikimedia\Dodo\Tests\Wpt\Harness\WptTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Node-cloneNode-external-stylesheet-no-bc.sub.html.
class NodeCloneNodeExternalStylesheetNoBcSubTest extends WptTestHarness
{
    public function testNodeCloneNodeExternalStylesheetNoBcSub()
    {
        $this->source_file = 'vendor/web-platform-tests/wpt/dom/nodes/Node-cloneNode-external-stylesheet-no-bc.sub.html';
        $doc = $this->doc->implementation->createHTMLDocument();
        // Bug was only triggered by absolute URLs, for some reason...
        $absoluteURL = new URL('/common/canvas-frame.css', $location->href);
        $doc->head->innerHTML = "<link rel=\"stylesheet\" href=\"\">{$absoluteURL}";
        // Test passes if this does not throw/crash
        $doc->cloneNode(true);
        $this->done();
    }
}
