<?php 
namespace Wikimedia\Dodo\Tests\Wpt\Dom;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Tests\Wpt\Harness\WptTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Node-cloneNode-on-inactive-document-crash.html.
class NodeCloneNodeOnInactiveDocumentCrashTest extends WptTestHarness
{
    public function testNodeCloneNodeOnInactiveDocumentCrash()
    {
        $this->source_file = 'vendor/web-platform-tests/wpt/dom/nodes/Node-cloneNode-on-inactive-document-crash.html';
        $doc = $i->getOwnerDocument();
        $i->remove();
        $doc->cloneNode();
    }
}
