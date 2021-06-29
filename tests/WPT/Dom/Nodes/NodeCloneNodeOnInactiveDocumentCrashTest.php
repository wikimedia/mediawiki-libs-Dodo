<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Node-cloneNode-on-inactive-document-crash.html.
class NodeCloneNodeOnInactiveDocumentCrashTest extends WPTTestHarness
{
    public function testNodeCloneNodeOnInactiveDocumentCrash()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/Node-cloneNode-on-inactive-document-crash.html');
        $doc = $i->getOwnerDocument();
        $i->remove();
        $doc->cloneNode();
    }
}
