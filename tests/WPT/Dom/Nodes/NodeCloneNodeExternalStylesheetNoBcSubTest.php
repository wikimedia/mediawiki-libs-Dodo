<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom\Nodes;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\URL;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Node-cloneNode-external-stylesheet-no-bc.sub.html.
class NodeCloneNodeExternalStylesheetNoBcSubTest extends WPTTestHarness
{
    public function testNodeCloneNodeExternalStylesheetNoBcSub()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/Node-cloneNode-external-stylesheet-no-bc.sub.html');
        $doc = $this->doc->implementation->createHTMLDocument();
        // Bug was only triggered by absolute URLs, for some reason...
        $absoluteURL = new URL('/common/canvas-frame.css', $this->getLocation()->href);
        $doc->head->innerHTML = "<link rel=\"stylesheet\" href=\"\">{$absoluteURL}";
        // Test passes if this does not throw/crash
        $doc->cloneNode(true);
        $this->done();
    }
}
