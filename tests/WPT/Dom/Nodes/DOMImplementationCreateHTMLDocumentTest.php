<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom\Nodes;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Document;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\HTMLHtmlElement;
use Wikimedia\Dodo\HTMLBodyElement;
use Wikimedia\Dodo\HTMLHeadElement;
use Wikimedia\Dodo\DocumentType;
use Wikimedia\Dodo\URL;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
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
            $this->assertTest(function () use (&$title, &$checkDoc, &$expectedtitle, &$normalizedtitle) {
                $doc = $this->doc->implementation->createHTMLDocument($title);
                $checkDoc($doc, $expectedtitle, $normalizedtitle);
            }, 'createHTMLDocument test ' . $i . ': ' . $this->arrayMap($t, function ($el) {
                return $this->formatValue($el);
            }));
        }
        $this->assertTest(function () use (&$checkDoc) {
            $doc = $this->doc->implementation->createHTMLDocument();
            $checkDoc($doc, null, '');
        }, 'Missing title argument');
    }
    public function testDOMImplementationCreateHTMLDocument()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/DOMImplementation-createHTMLDocument.html');
        $this->createHTMLDocuments(function ($doc, $expectedtitle, $normalizedtitle) {
            $this->wptAssertTrue($doc instanceof Document, 'Should be a Document');
            $this->wptAssertTrue($doc instanceof Node, 'Should be a Node');
            $this->wptAssertEquals(count($doc->childNodes), 2, 'Document should have two child nodes');
            $doctype = $doc->doctype;
            $this->wptAssertTrue($doctype instanceof DocumentType, 'Doctype should be a DocumentType');
            $this->wptAssertTrue($doctype instanceof Node, 'Doctype should be a Node');
            $this->wptAssertEquals($doctype->name, 'html');
            $this->wptAssertEquals($doctype->publicId, '');
            $this->wptAssertEquals($doctype->systemId, '');
            $this->docElement = $doc->documentElement;
            $this->wptAssertTrue($this->docElement instanceof HTMLHtmlElement, 'Document element should be a HTMLHtmlElement');
            $this->wptAssertEquals(count($this->docElement->childNodes), 2, 'Document element should have two child nodes');
            $this->wptAssertEquals($this->docElement->localName, 'html');
            $this->wptAssertEquals($this->docElement->tagName, 'HTML');
            $head = $this->docElement->firstChild;
            $this->wptAssertTrue($head instanceof HTMLHeadElement, 'Head should be a HTMLHeadElement');
            $this->wptAssertEquals($head->localName, 'head');
            $this->wptAssertEquals($head->tagName, 'HEAD');
            if ($expectedtitle !== null) {
                $this->wptAssertEquals(count($head->childNodes), 1);
                $title = $head->firstChild;
                $this->wptAssertTrue($title instanceof HTMLTitleElement, 'Title should be a HTMLTitleElement');
                $this->wptAssertEquals($title->localName, 'title');
                $this->wptAssertEquals($title->tagName, 'TITLE');
                $this->wptAssertEquals(count($title->childNodes), 1);
                $this->wptAssertEquals($title->firstChild->data, $expectedtitle);
            } else {
                $this->wptAssertEquals(count($head->childNodes), 0);
            }
            $body = $this->docElement->lastChild;
            $this->wptAssertTrue($body instanceof HTMLBodyElement, 'Body should be a HTMLBodyElement');
            $this->wptAssertEquals($body->localName, 'body');
            $this->wptAssertEquals($body->tagName, 'BODY');
            $this->wptAssertEquals(count($body->childNodes), 0);
        });
        $this->assertTest(function () {
            $doc = $this->doc->implementation->createHTMLDocument('test');
            $this->wptAssertEquals($this->getURL(), 'about:blank');
            $this->wptAssertEquals($doc->documentURI, 'about:blank');
            $this->wptAssertEquals($doc->compatMode, 'CSS1Compat');
            $this->wptAssertEquals($doc->characterSet, 'UTF-8');
            $this->wptAssertEquals($doc->contentType, 'text/html');
            $this->wptAssertEquals($doc->createElement('DIV')->localName, 'div');
        }, 'createHTMLDocument(): metadata');
        $this->assertTest(function () {
            $doc = $this->doc->implementation->createHTMLDocument('test');
            $this->wptAssertEquals($doc->characterSet, 'UTF-8', 'characterSet');
            $this->wptAssertEquals($doc->charset, 'UTF-8', 'charset');
            $this->wptAssertEquals($doc->inputEncoding, 'UTF-8', 'inputEncoding');
        }, 'createHTMLDocument(): characterSet aliases');
        $this->assertTest(function () {
            $doc = $this->doc->implementation->createHTMLDocument('test');
            $a = $doc->createElement('a');
            // In UTF-8: 0xC3 0xA4
            $a->href = "http://example.org/?ä";
            $this->wptAssertEquals($a->href, 'http://example.org/?%C3%A4');
        }, 'createHTMLDocument(): URL parsing');
        // Test the document location getter is null outside of browser context
        $this->assertTest(function () {
            $doc = $this->doc->implementation->createHTMLDocument();
            $this->wptAssertEquals($doc->location, null);
        }, 'createHTMLDocument(): document location getter is null');
    }
}
