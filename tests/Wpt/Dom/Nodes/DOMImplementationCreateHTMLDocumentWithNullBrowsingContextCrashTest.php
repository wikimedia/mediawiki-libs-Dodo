<?php 
namespace Wikimedia\Dodo\Tests\Wpt\Dom;
use Wikimedia\Dodo\Tests\Wpt\Harness\WptTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/DOMImplementation-createHTMLDocument-with-null-browsing-context-crash.html.
class DOMImplementationCreateHTMLDocumentWithNullBrowsingContextCrashTest extends WptTestHarness
{
    public function testDOMImplementationCreateHTMLDocumentWithNullBrowsingContextCrash()
    {
        $this->source_file = 'vendor/web-platform-tests/wpt/dom/nodes/DOMImplementation-createHTMLDocument-with-null-browsing-context-crash.html';
        $doc = $i->getOwnerDocument();
        $i->remove();
        $doc->implementation->createHTMLDocument();
    }
}
