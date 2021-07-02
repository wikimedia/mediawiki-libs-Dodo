<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom\Nodes;
use Wikimedia\Dodo\DOMImplementation;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Document-implementation.html.
class DocumentImplementationTest extends WPTTestHarness
{
    public function testDocumentImplementation()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/Document-implementation.html');
        $this->assertTest(function () {
            $implementation = $this->doc->implementation;
            $this->wptAssertTrue($implementation instanceof DOMImplementation, 'implementation should implement DOMImplementation');
            $this->wptAssertEquals($this->doc->implementation, $implementation);
        }, 'Getting implementation off the same document');
        $this->assertTest(function () {
            $doc = $this->doc->implementation->createHTMLDocument();
            $this->wptAssertNotEquals($this->doc->implementation, $doc->implementation);
        }, 'Getting implementation off different documents');
    }
}
