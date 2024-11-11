<?php 
namespace Wikimedia\Dodo\Tests\WPT\Domparsing;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\HTMLHtmlElement;
use Wikimedia\Dodo\HTMLParagraphElement;
use Wikimedia\Dodo\URL;
use Wikimedia\Dodo\DOMParser;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/domparsing/DOMParser-parseFromString-html.html.
class DOMParserParseFromStringHtmlTest extends WPTTestHarness
{
    public function assertNode($actual, $expected)
    {
        $this->wptAssertTrue($actual instanceof $expected->type, 'Node type mismatch: actual = ' . $actual->constructor->name . ', expected = ' . $expected->type->name);
        if (gettype($expected->id) !== NULL) {
            $this->wptAssertEquals($actual->id, $expected->id, $expected->idMessage);
        }
    }
    public function testDOMParserParseFromStringHtml()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/domparsing/DOMParser-parseFromString-html.html');
        $doc = null;
        // setup()
        $parser = new DOMParser();
        $input = '<html id="root"><head></head><body></body></html>';
        $doc = $parser->parseFromString($input, 'text/html');
        $this->assertTest(function () use (&$doc) {
            $root = $doc->documentElement;
            $this->wptAssertNode($root, ['type' => HTMLHtmlElement::class, 'id' => 'root', 'idMessage' => 'documentElement id attribute should be root.']);
        }, 'Parsing of id attribute');
        $this->assertTest(function () use (&$doc) {
            $this->wptAssertEquals($doc->contentType, 'text/html');
        }, 'contentType');
        $this->assertTest(function () use (&$doc) {
            $this->wptAssertEquals($doc->compatMode, 'BackCompat');
        }, 'compatMode');
        $this->assertTest(function () {
            $parser = new DOMParser();
            $input = '<!DOCTYPE html><html id="root"><head></head><body></body></html>';
            $doc = $parser->parseFromString($input, 'text/html');
            $this->wptAssertEquals($doc->compatMode, 'CSS1Compat');
        }, 'compatMode for a proper DOCTYPE');
        // URL- and encoding-related stuff tested separately.
        $this->assertTest(function () use (&$doc) {
            $this->wptAssertEquals($doc->location, null, 'The document must have a location value of null.');
        }, 'Location value');
        $this->assertTest(function () {
            $soup = '<!DOCTYPE foo></><foo></multiple></>';
            $htmldoc = (new DOMParser())->parseFromString($soup, 'text/html');
            $this->wptAssertEquals($htmldoc->documentElement->localName, 'html');
            $this->wptAssertEquals($htmldoc->documentElement->namespaceURI, 'http://www.w3.org/1999/xhtml');
        }, 'DOMParser parses HTML tag soup with no problems');
        $this->assertTest(function () {
            $doc = (new DOMParser())->parseFromString('<noembed>&lt;a&gt;</noembed>', 'text/html');
            $this->wptAssertEquals($doc->querySelector('noembed')->textContent, '&lt;a&gt;');
        }, 'DOMParser should handle the content of <noembed> as raw text');
        $this->assertTest(function () {
            $this->wptAssertThrowsJs($this->type_error, function () {
                (new DOMParser())->parseFromString('', 'text/foo-this-is-invalid');
            });
        }, 'DOMParser throws on an invalid enum value');
        $this->assertTest(function () {
            $doc = (new DOMParser())->parseFromString("\n<html><body>\n<style>\n  @import url(/dummy.css)\n</style>\n<script>document.x = 8</script>\n</body></html>", 'text/html');
            $this->wptAssertNotEquals($doc->querySelector('script'), null, 'script must be found');
            $this->wptAssertEquals($doc->x, null, 'script must not be executed on the inner document');
            $this->wptAssertEquals($this->doc->x, null, 'script must not be executed on the outer document');
        }, 'script is found synchronously even when there is a css import');
        $this->assertTest(function () {
            $doc = (new DOMParser())->parseFromString("<body><noscript><p id=\"test1\">test1<p id=\"test2\">test2</noscript>", 'text/html');
            $this->wptAssertNode($doc->body->firstChild->childNodes[0], ['type' => HTMLParagraphElement::class, 'id' => 'test1']);
            $this->wptAssertNode($doc->body->firstChild->childNodes[1], ['type' => HTMLParagraphElement::class, 'id' => 'test2']);
        }, 'must be parsed with scripting disabled, so noscript works');
    }
}
