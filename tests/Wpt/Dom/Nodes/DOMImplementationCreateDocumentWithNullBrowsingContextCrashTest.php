<?php 
namespace Wikimedia\Dodo\Tests\Wpt\Dom;
use Wikimedia\Dodo\Tests\Wpt\Harness\WptTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/DOMImplementation-createDocument-with-null-browsing-context-crash.html.
class DOMImplementationCreateDocumentWithNullBrowsingContextCrashTest extends WptTestHarness
{
    public function testDOMImplementationCreateDocumentWithNullBrowsingContextCrash()
    {
        $this->doc = $this->loadWptHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/DOMImplementation-createDocument-with-null-browsing-context-crash.html');
        $doc = $i->getOwnerDocument();
        $i->remove();
        $doc->implementation->createDocument('', '');
    }
}
