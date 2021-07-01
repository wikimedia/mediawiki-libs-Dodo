<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom;
use Wikimedia\Dodo\DocumentType;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/DocumentType-literal.html.
class DocumentTypeLiteralTest extends WPTTestHarness
{
    public function testDocumentTypeLiteral()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/DocumentType-literal.html');
        $this->assertTest(function () {
            $doctype = $this->doc->firstChild;
            $this->wptAssertTrue($doctype instanceof DocumentType);
            $this->wptAssertEquals($doctype->name, 'html');
            $this->wptAssertEquals($doctype->publicId, 'STAFF');
            $this->wptAssertEquals($doctype->systemId, 'staffNS.dtd');
        });
    }
}
