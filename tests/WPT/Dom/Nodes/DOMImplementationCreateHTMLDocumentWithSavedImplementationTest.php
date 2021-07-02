<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom\Nodes;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/DOMImplementation-createHTMLDocument-with-saved-implementation.html.
class DOMImplementationCreateHTMLDocumentWithSavedImplementationTest extends WPTTestHarness
{
    public function testDOMImplementationCreateHTMLDocumentWithSavedImplementation()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/DOMImplementation-createHTMLDocument-with-saved-implementation.html');
        // Test the document location getter is null outside of browser context
        $this->assertTest(function () {
            $iframe = $this->doc->createElement('iframe');
            $this->doc->body->appendChild($iframe);
            $implementation = $iframe->getOwnerDocument()->implementation;
            $iframe->remove();
            $this->wptAssertNotEquals($implementation->createHTMLDocument(), null);
        }, 'createHTMLDocument(): from a saved and detached implementation does not return null');
    }
}
