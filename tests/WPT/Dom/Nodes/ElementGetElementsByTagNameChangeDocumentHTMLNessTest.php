<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\WPT\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Element-getElementsByTagName-change-document-HTMLNess.html.
class ElementGetElementsByTagNameChangeDocumentHTMLNessTest extends WPTTestHarness
{
    public function testElementGetElementsByTagNameChangeDocumentHTMLNess()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/Element-getElementsByTagName-change-document-HTMLNess.html');
        $onload = function () {
            $parent = $this->doc->createElement('div');
            $child1 = $this->doc->createElementNS('http://www.w3.org/1999/xhtml', 'a');
            $child1->textContent = 'xhtml:a';
            $child2 = $this->doc->createElementNS('http://www.w3.org/1999/xhtml', 'A');
            $child2->textContent = 'xhtml:A';
            $child3 = $this->doc->createElementNS('', 'a');
            $child3->textContent = 'a';
            $child4 = $this->doc->createElementNS('', 'A');
            $child4->textContent = 'A';
            $parent->appendChild($child1);
            $parent->appendChild($child2);
            $parent->appendChild($child3);
            $parent->appendChild($child4);
            $list = $parent->getElementsByTagName('A');
            $this->assertArrayEqualsData($list, [$child1, $child4], 'In an HTML document, should lowercase the tagname passed in for HTML ' . 'elements only');
            $frames[0]->document->documentElement->appendChild($parent);
            $this->assertArrayEqualsData($list, [$child1, $child4], 'After changing document, should still be lowercasing for HTML');
            $this->assertArrayEqualsData($parent->getElementsByTagName('A'), [$child2, $child4], 'New list with same root and argument should not be lowercasing now');
            // Now reinsert all those nodes into the parent, to blow away caches.
            $parent->appendChild($child1);
            $parent->appendChild($child2);
            $parent->appendChild($child3);
            $parent->appendChild($child4);
            $this->assertArrayEqualsData($list, [$child1, $child4], 'After blowing away caches, should still have the same list');
            $this->assertArrayEqualsData($parent->getElementsByTagName('A'), [$child2, $child4], 'New list with same root and argument should still not be lowercasing');
            $this->done();
        };
    }
}
