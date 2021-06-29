<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Document;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DocumentType;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Document-doctype.html.
class DocumentDoctypeTest extends WPTTestHarness
{
    public function testDocumentDoctype()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/Document-doctype.html');
        $this->assertTest(function () {
            $this->assertTrueData($this->doc->doctype instanceof DocumentType, 'Doctype should be a DocumentType');
            $this->assertEqualsData($this->doc->doctype, $this->doc->childNodes[1]);
        }, 'Window document with doctype');
        $this->assertTest(function () {
            $newdoc = new Document();
            $newdoc->appendChild($newdoc->createElement('html'));
            $this->assertEqualsData($newdoc->doctype, null);
        }, 'new Document()');
    }
}
