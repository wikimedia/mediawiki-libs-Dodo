<?php 
namespace Wikimedia\Dodo\Tests\Wpt\Dom;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\Wpt\Harness\WptTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Document-createElement-namespace.html.
class DocumentCreateElementNamespaceTest extends WptTestHarness
{
    public function testDoc($doc, $contentType)
    {
        if ($doc->contentType !== null) {
            // Sanity check
            $this->assertEqualsData($doc->contentType, $contentType, 'Wrong MIME type returned from doc.contentType');
        }
        $expectedNamespace = $contentType == 'text/html' || $contentType == 'application/xhtml+xml' ? 'http://www.w3.org/1999/xhtml' : null;
        $this->assertEqualsData($doc->createElement('x')->namespaceURI, $expectedNamespace);
    }
    public function testDocumentCreateElementNamespace()
    {
        $this->source_file = 'vendor/web-platform-tests/wpt/dom/nodes/Document-createElement-namespace.html';
        // First test various objects we create in JS
        $this->assertTest(function () {
            $this->testDoc($this->doc, 'text/html');
        }, "Created element's namespace in current document");
        $this->assertTest(function () {
            $this->testDoc($this->doc->implementation->createHTMLDocument(''), 'text/html');
        }, "Created element's namespace in created HTML document");
        $this->assertTest(function () {
            $this->testDoc($this->doc->implementation->createDocument(null, '', null), 'application/xml');
        }, "Created element's namespace in created XML document");
        $this->assertTest(function () {
            $this->testDoc($this->doc->implementation->createDocument('http://www.w3.org/1999/xhtml', 'html', null), 'application/xhtml+xml');
        }, "Created element's namespace in created XHTML document");
        $this->assertTest(function () {
            $this->testDoc($this->doc->implementation->createDocument('http://www.w3.org/2000/svg', 'svg', null), 'image/svg+xml');
        }, "Created element's namespace in created SVG document");
        $this->assertTest(function () {
            $this->testDoc($this->doc->implementation->createDocument('http://www.w3.org/1998/Math/MathML', 'math', null), 'application/xml');
        }, "Created element's namespace in created MathML document");
        // Second also test document created by DOMParser
        $this->assertTest(function () {
            $this->testDoc($this->parseFromString('', 'text/html'), 'text/html');
        }, "Created element's namespace in created HTML document by DOMParser ('text/html')");
        $this->assertTest(function () {
            $this->testDoc($this->parseFromString('<root/>', 'text/xml'), 'text/xml');
        }, "Created element's namespace in created XML document by DOMParser ('text/xml')");
        $this->assertTest(function () {
            $this->testDoc($this->parseFromString('<root/>', 'application/xml'), 'application/xml');
        }, "Created element's namespace in created XML document by DOMParser ('application/xml')");
        $this->assertTest(function () {
            $this->testDoc($this->parseFromString('<html/>', 'application/xhtml+xml'), 'application/xhtml+xml');
        }, "Created element's namespace in created XHTML document by DOMParser ('application/xhtml+xml')");
        $this->assertTest(function () {
            $this->testDoc($this->parseFromString('<math/>', 'image/svg+xml'), 'image/svg+xml');
        }, "Created element's namespace in created SVG document by DOMParser ('image/svg+xml')");
        // Now for various externally-loaded files.  Note: these lists must be kept
        // synced with the lists in generate.py in the subdirectory, and that script
        // must be run whenever the lists are updated.  (We could keep the lists in a
        // shared JSON file, but it seems like too much effort.)
        $testExtensions = ['html' => 'text/html', 'xhtml' => 'application/xhtml+xml', 'xml' => 'application/xml', 'svg' => 'image/svg+xml'];
        // Was not able to get server MIME type working properly :(
        //mml: "application/mathml+xml",
        $tests = ['empty', 'minimal_html', 'xhtml', 'svg', 'mathml', 'bare_xhtml', 'bare_svg', 'bare_mathml', 'xhtml_ns_removed', 'xhtml_ns_changed'];
        foreach ($tests as $testName) {
            foreach ($testExtensions as $ext) {
                $this->asyncTest(function ($t) use(&$ext, &$testName, &$testExtensions) {
                    $iframe = $this->doc->createElement('iframe');
                    $iframe->src = 'Document-createElement-namespace-tests/' . $testName . '.' . $ext;
                    $iframe->onload = $t->step_func_done(function () use(&$iframe, &$testExtensions, &$ext) {
                        $this->testDoc($iframe->getOwnerDocument(), $testExtensions[$ext]);
                        $this->doc->body->removeChild($iframe);
                    });
                    $this->doc->body->appendChild($iframe);
                }, "Created element's namespace in " . $testName . '.' . $ext);
            }
        }
    }
}
