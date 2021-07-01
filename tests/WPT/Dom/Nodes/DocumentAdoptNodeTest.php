<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DocumentType;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Document-adoptNode.html.
class DocumentAdoptNodeTest extends WPTTestHarness
{
    public function testDocumentAdoptNode()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/Document-adoptNode.html');
        $this->assertTest(function () {
            $y = $this->doc->getElementsByTagName('x<')[0];
            $child = $y->firstChild;
            $this->wptAssertEquals($y->parentNode, $this->doc->body);
            $this->wptAssertEquals($y->ownerDocument, $this->doc);
            $this->wptAssertEquals($this->doc->adoptNode($y), $y);
            $this->wptAssertEquals($y->parentNode, null);
            $this->wptAssertEquals($y->firstChild, $child);
            $this->wptAssertEquals($y->ownerDocument, $this->doc);
            $this->wptAssertEquals($child->ownerDocument, $this->doc);
            $doc = $this->doc->implementation->createDocument(null, null, null);
            $this->wptAssertEquals($doc->adoptNode($y), $y);
            $this->wptAssertEquals($y->parentNode, null);
            $this->wptAssertEquals($y->firstChild, $child);
            $this->wptAssertEquals($y->ownerDocument, $doc);
            $this->wptAssertEquals($child->ownerDocument, $doc);
        }, "Adopting an Element called 'x<' should work.");
        $this->assertTest(function () {
            $x = $this->doc->createElement(':good:times:');
            $this->wptAssertEquals($this->doc->adoptNode($x), $x);
            $doc = $this->doc->implementation->createDocument(null, null, null);
            $this->wptAssertEquals($doc->adoptNode($x), $x);
            $this->wptAssertEquals($x->parentNode, null);
            $this->wptAssertEquals($x->ownerDocument, $doc);
        }, "Adopting an Element called ':good:times:' should work.");
        $this->assertTest(function () {
            $doctype = $this->doc->doctype;
            $this->wptAssertEquals($doctype->parentNode, $this->doc);
            $this->wptAssertEquals($doctype->ownerDocument, $this->doc);
            $this->wptAssertEquals($this->doc->adoptNode($doctype), $doctype);
            $this->wptAssertEquals($doctype->parentNode, null);
            $this->wptAssertEquals($doctype->ownerDocument, $this->doc);
        }, 'Explicitly adopting a DocumentType should work.');
        $this->assertTest(function () {
            $doc = $this->doc->implementation->createDocument(null, null, null);
            $this->wptAssertThrowsDom('NOT_SUPPORTED_ERR', function () use(&$doc) {
                $this->doc->adoptNode($doc);
            });
        }, 'Adopting a Document should throw.');
    }
}
