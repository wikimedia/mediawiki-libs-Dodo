<?php 
namespace Wikimedia\Dodo\Tests\WPT\Domparsing;
use Wikimedia\Dodo\Document;
use Wikimedia\IDLeDOM\XMLDocument;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\URL;
use Wikimedia\Dodo\DOMParser;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/domparsing/DOMParser-parseFromString-xml.html.
class DOMParserParseFromStringXmlTest extends WPTTestHarness
{
    public function checkMetadata($doc, $contentType)
    {
        $this->wptAssertTrue($doc instanceof Document, 'Should be Document');
        $this->wptAssertEquals($doc->URL, $this->doc->URL, 'URL');
        $this->wptAssertEquals($doc->documentURI, $this->doc->URL, 'documentURI');
        $this->wptAssertEquals($doc->baseURI, $this->doc->URL, 'baseURI');
        $this->wptAssertEquals($doc->characterSet, 'UTF-8', 'characterSet');
        $this->wptAssertEquals($doc->charset, 'UTF-8', 'charset');
        $this->wptAssertEquals($doc->inputEncoding, 'UTF-8', 'inputEncoding');
        $this->wptAssertEquals($doc->contentType, $contentType, 'contentType');
        $this->wptAssertEquals($doc->location, null, 'location');
    }
    public function testDOMParserParseFromStringXml()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/domparsing/DOMParser-parseFromString-xml.html');
        $allowedTypes = ['text/xml', 'application/xml', 'application/xhtml+xml', 'image/svg+xml'];
        foreach ($allowedTypes as $type) {
            $this->assertTest(function () use(&$type) {
                $p = new DOMParser();
                $doc = $p->parseFromString('<foo/>', $type);
                $this->wptAssertTrue($doc instanceof Document, 'Should be Document');
                $this->checkMetadata($doc, $type);
                $this->wptAssertEquals($doc->documentElement->namespaceURI, null);
                $this->wptAssertEquals($doc->documentElement->localName, 'foo');
                $this->wptAssertEquals($doc->documentElement->tagName, 'foo');
            }, 'Should parse correctly in type ' . $type);
            $this->assertTest(function () use(&$type) {
                $p = new DOMParser();
                $doc = $p->parseFromString('<foo/>', $type);
                $this->wptAssertFalse($doc instanceof XMLDocument, 'Should not be XMLDocument');
            }, 'XMLDocument interface for correctly parsed document with type ' . $type);
            $this->assertTest(function () use(&$type) {
                $p = new DOMParser();
                $doc = $p->parseFromString('<foo>', $type);
                $this->checkMetadata($doc, $type);
                $this->wptAssertEquals($doc->documentElement->namespaceURI, 'http://www.mozilla.org/newlayout/xml/parsererror.xml');
                $this->wptAssertEquals($doc->documentElement->localName, 'parsererror');
                $this->wptAssertEquals($doc->documentElement->tagName, 'parsererror');
            }, 'Should return an error document for XML wellformedness errors in type ' . $type);
            $this->assertTest(function () use(&$type) {
                $p = new DOMParser();
                $doc = $p->parseFromString('<foo>', $type);
                $this->wptAssertFalse($doc instanceof XMLDocument, 'Should not be XMLDocument');
            }, 'XMLDocument interface for incorrectly parsed document with type ' . $type);
            $this->assertTest(function () use(&$type) {
                $p = new DOMParser();
                $doc = $p->parseFromString("\n      <html>\n        <head></head>\n        <body>\n          <script>document.x = 5;</script>\n          <noscript><p>test1</p><p>test2</p></noscript>\n        </body>\n      </html>", $type);
                $this->wptAssertEquals($doc->x, null, 'script must not be executed on the inner document');
                $this->wptAssertEquals($this->doc->x, null, 'script must not be executed on the outer document');
                $body = $doc->documentElement->children[1];
                $this->wptAssertEquals($body->localName, 'body');
                $this->wptAssertEquals($body->children[1]->localName, 'noscript');
                $this->wptAssertEquals(count($body->children[1]->children), 2);
                $this->wptAssertEquals($body->children[1]->children[0]->localName, 'p');
                $this->wptAssertEquals($body->children[1]->children[1]->localName, 'p');
            }, 'scripting must be disabled with type ' . $type);
        }
    }
}
