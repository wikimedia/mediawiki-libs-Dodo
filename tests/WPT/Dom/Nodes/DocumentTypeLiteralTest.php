<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom;
use Wikimedia\Dodo\DocumentType;
use Wikimedia\Dodo\Tests\WPT\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/DocumentType-literal.html.
class DocumentTypeLiteralTest extends WPTTestHarness
{
    public function testDocumentTypeLiteral()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/DocumentType-literal.html');
        $this->assertTest(function () {
            $doctype = $this->doc->firstChild;
            $this->assertTrueData($doctype instanceof DocumentType);
            $this->assertEqualsData($doctype->name, 'html');
            $this->assertEqualsData($doctype->publicId, 'STAFF');
            $this->assertEqualsData($doctype->systemId, 'staffNS.dtd');
        });
    }
}
