<?php 
namespace Wikimedia\Dodo\Tests\Wpt\Dom;
use Wikimedia\Dodo\DocumentType;
use Wikimedia\Dodo\Tests\Wpt\Harness\WptTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/DocumentType-literal.html.
class DocumentTypeLiteralTest extends WptTestHarness
{
    public function testDocumentTypeLiteral()
    {
        $this->doc = $this->loadWptHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/DocumentType-literal.html');
        $this->assertTest(function () {
            $doctype = $this->doc->firstChild;
            $this->assertTrueData($doctype instanceof DocumentType);
            $this->assertEqualsData($doctype->name, 'html');
            $this->assertEqualsData($doctype->publicId, 'STAFF');
            $this->assertEqualsData($doctype->systemId, 'staffNS.dtd');
        });
    }
}
