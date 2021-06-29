<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\HTMLHtmlElement;
use Wikimedia\Dodo\HTMLBodyElement;
use Wikimedia\Dodo\HTMLHeadElement;
use Wikimedia\Dodo\DocumentType;
use Wikimedia\Dodo\URL;
use Wikimedia\Dodo\Tests\WPT\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/DOMImplementation-createHTMLDocument.html.
class DOMImplementationCreateHTMLDocumentTest extends WPTTestHarness
{
    public function createHTMLDocuments($checkDoc)
    {
        $tests = [['', '', ''], [null, 'null', 'null'], [null, null, ''], ['foo  bar baz', 'foo  bar baz', 'foo bar baz'], ["foo\t\tbar baz", "foo\t\tbar baz", 'foo bar baz'], ["foo\n\nbar baz", "foo\n\nbar baz", 'foo bar baz'], ["foo\f\fbar baz", "foo\f\fbar baz", 'foo bar baz'], ["foo\r\rbar baz", "foo\r\rbar baz", 'foo bar baz']];
        foreach ($tests as $t) {
            $title = $t[0];
            $expectedtitle = $t[1];
            $normalizedtitle = $t[2];
            $this->assertTest(function () use(&$title, &$checkDoc, &$expectedtitle, &$normalizedtitle) {
                $doc = $this->doc->implementation->createHTMLDocument($title);
                $checkDoc($doc, $expectedtitle, $normalizedtitle);
            }, 'createHTMLDocument test ' . $i . ': ' . $this->arrayMap($t, function ($el) {
                return $this->formatValue($el);
            }));
        }
        $this->assertTest(function () use(&$checkDoc) {
            $doc = $this->doc->implementation->createHTMLDocument();
            $checkDoc($doc, null, '');
        }, 'Missing title argument');
    }
    public function testDOMImplementationCreateHTMLDocument()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/DOMImplementation-createHTMLDocument.html');
        $this->createHTMLDocuments(function ($doc, $expectedtitle, $normalizedtitle) {
            $this->assertTrueData($doc instanceof Document, 'Should be a Document');
            $this->assertTrueData($doc instanceof Node, 'Should be a Node');
            $this->assertEqualsData(count($doc->childNodes), 2, 'Document should have two child nodes');
            $doctype = $doc->doctype;
            $this->assertTrueData($doctype instanceof DocumentType, 'Doctype should be a DocumentType');
            $this->assertTrueData($doctype instanceof Node, 'Doctype should be a Node');
            $this->assertEqualsData($doctype->name, 'html');
            $this->assertEqualsData($doctype->publicId, '');
            $this->assertEqualsData($doctype->systemId, '');
            $this->docElement = $doc->documentElement;
            $this->assertTrueData($this->docElement instanceof HTMLHtmlElement, 'Document element should be a HTMLHtmlElement');
            $this->assertEqualsData(count($this->docElement->childNodes), 2, 'Document element should have two child nodes');
            $this->assertEqualsData($this->docElement->localName, 'html');
            $this->assertEqualsData($this->docElement->tagName, 'HTML');
            $head = $this->docElement->firstChild;
            $this->assertTrueData($head instanceof HTMLHeadElement, 'Head should be a HTMLHeadElement');
            $this->assertEqualsData($head->localName, 'head');
            $this->assertEqualsData($head->tagName, 'HEAD');
            if ($expectedtitle !== null) {
                $this->assertEqualsData(count($head->childNodes), 1);
                $title = $head->firstChild;
                $this->assertTrueData($title instanceof HTMLTitleElement, 'Title should be a HTMLTitleElement');
                $this->assertEqualsData($title->localName, 'title');
                $this->assertEqualsData($title->tagName, 'TITLE');
                $this->assertEqualsData(count($title->childNodes), 1);
                $this->assertEqualsData($title->firstChild->data, $expectedtitle);
            } else {
                $this->assertEqualsData(count($head->childNodes), 0);
            }
            $body = $this->docElement->lastChild;
            $this->assertTrueData($body instanceof HTMLBodyElement, 'Body should be a HTMLBodyElement');
            $this->assertEqualsData($body->localName, 'body');
            $this->assertEqualsData($body->tagName, 'BODY');
            $this->assertEqualsData(count($body->childNodes), 0);
        });
        $this->assertTest(function () {
            $doc = $this->doc->implementation->createHTMLDocument('test');
            $this->assertEqualsData($doc->URL, 'about:blank');
            $this->assertEqualsData($doc->documentURI, 'about:blank');
            $this->assertEqualsData($doc->compatMode, 'CSS1Compat');
            $this->assertEqualsData($doc->characterSet, 'UTF-8');
            $this->assertEqualsData($doc->contentType, 'text/html');
            $this->assertEqualsData($doc->createElement('DIV')->localName, 'div');
        }, 'createHTMLDocument(): metadata');
        $this->assertTest(function () {
            $doc = $this->doc->implementation->createHTMLDocument('test');
            $this->assertEqualsData($doc->characterSet, 'UTF-8', 'characterSet');
            $this->assertEqualsData($doc->charset, 'UTF-8', 'charset');
            $this->assertEqualsData($doc->inputEncoding, 'UTF-8', 'inputEncoding');
        }, 'createHTMLDocument(): characterSet aliases');
        $this->assertTest(function () {
            $doc = $this->doc->implementation->createHTMLDocument('test');
            $a = $doc->createElement('a');
            // In UTF-8: 0xC3 0xA4
            $a->href = "http://example.org/?ä";
            $this->assertEqualsData($a->href, 'http://example.org/?%C3%A4');
        }, 'createHTMLDocument(): URL parsing');
        // Test the document location getter is null outside of browser context
        $this->assertTest(function () {
            $doc = $this->doc->implementation->createHTMLDocument();
            $this->assertEqualsData($doc->location, null);
        }, 'createHTMLDocument(): document location getter is null');
    }
}
