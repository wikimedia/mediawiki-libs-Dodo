<?php 
namespace Wikimedia\Dodo\Tests\Wpt\Dom;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DocumentType;
use Wikimedia\Dodo\Tests\Wpt\Harness\WptTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Document-adoptNode.html.
class DocumentAdoptNodeTest extends WptTestHarness
{
    public function testDocumentAdoptNode()
    {
        $this->doc = $this->loadWptHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/Document-adoptNode.html');
        $this->assertTest(function () {
            $y = $this->doc->getElementsByTagName('x<')[0];
            $child = $y->firstChild;
            $this->assertEqualsData($y->parentNode, $this->doc->body);
            $this->assertEqualsData($y->ownerDocument, $this->doc);
            $this->assertEqualsData($this->doc->adoptNode($y), $y);
            $this->assertEqualsData($y->parentNode, null);
            $this->assertEqualsData($y->firstChild, $child);
            $this->assertEqualsData($y->ownerDocument, $this->doc);
            $this->assertEqualsData($child->ownerDocument, $this->doc);
            $doc = $this->doc->implementation->createDocument(null, null, null);
            $this->assertEqualsData($doc->adoptNode($y), $y);
            $this->assertEqualsData($y->parentNode, null);
            $this->assertEqualsData($y->firstChild, $child);
            $this->assertEqualsData($y->ownerDocument, $doc);
            $this->assertEqualsData($child->ownerDocument, $doc);
        }, "Adopting an Element called 'x<' should work.");
        $this->assertTest(function () {
            $x = $this->doc->createElement(':good:times:');
            $this->assertEqualsData($this->doc->adoptNode($x), $x);
            $doc = $this->doc->implementation->createDocument(null, null, null);
            $this->assertEqualsData($doc->adoptNode($x), $x);
            $this->assertEqualsData($x->parentNode, null);
            $this->assertEqualsData($x->ownerDocument, $doc);
        }, "Adopting an Element called ':good:times:' should work.");
        $this->assertTest(function () {
            $doctype = $this->doc->doctype;
            $this->assertEqualsData($doctype->parentNode, $this->doc);
            $this->assertEqualsData($doctype->ownerDocument, $this->doc);
            $this->assertEqualsData($this->doc->adoptNode($doctype), $doctype);
            $this->assertEqualsData($doctype->parentNode, null);
            $this->assertEqualsData($doctype->ownerDocument, $this->doc);
        }, 'Explicitly adopting a DocumentType should work.');
        $this->assertTest(function () {
            $doc = $this->doc->implementation->createDocument(null, null, null);
            $this->assertThrowsDomData('NOT_SUPPORTED_ERR', function () use(&$doc) {
                $this->doc->adoptNode($doc);
            });
        }, 'Adopting a Document should throw.');
    }
}
