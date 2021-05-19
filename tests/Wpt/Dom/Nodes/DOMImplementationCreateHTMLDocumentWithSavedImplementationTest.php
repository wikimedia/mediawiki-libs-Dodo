<?php 
namespace Wikimedia\Dodo\Tests\Wpt\Dom;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\Wpt\Harness\WptTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/DOMImplementation-createHTMLDocument-with-saved-implementation.html.
class DOMImplementationCreateHTMLDocumentWithSavedImplementationTest extends WptTestHarness
{
    public function testDOMImplementationCreateHTMLDocumentWithSavedImplementation()
    {
        $this->doc = $this->loadWptHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/DOMImplementation-createHTMLDocument-with-saved-implementation.html');
        // Test the document location getter is null outside of browser context
        $this->assertTest(function () {
            $iframe = $this->doc->createElement('iframe');
            $this->getDocBody( $this->doc )->appendChild($iframe);
            $implementation = $iframe->getOwnerDocument()->implementation;
            $iframe->remove();
            $this->assertNotEqualsData($implementation->createHTMLDocument(), null);
        }, 'createHTMLDocument(): from a saved and detached implementation does not return null');
    }
}
