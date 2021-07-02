<?php 
namespace Wikimedia\Dodo\Tests\WPT\Domparsing;
use Wikimedia\Dodo\DOMParser;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/domparsing/DOMParser-parseFromString-encoding.html.
class DOMParserParseFromStringEncodingTest extends WPTTestHarness
{
    public function assertEncoding($doc)
    {
        $this->wptAssertEquals($doc->charset, 'UTF-8', 'document.charset');
        $this->wptAssertEquals($doc->characterSet, 'UTF-8', 'document.characterSet');
        $this->wptAssertEquals($doc->inputEncoding, 'UTF-8', 'document.characterSet');
    }
    public function testDOMParserParseFromStringEncoding()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/domparsing/DOMParser-parseFromString-encoding.html');
        // setup()
        $this->wptAssertEquals($this->doc->characterSet, 'windows-1252', 'the meta charset must be in effect, making the main document windows-1252');
        $this->assertTest(function () {
            $parser = new DOMParser();
            $doc = $parser->parseFromString('', 'text/html');
            assertEncoding($doc);
        }, 'HTML: empty');
        $this->assertTest(function () {
            $parser = new DOMParser();
            $doc = $parser->parseFromString('', 'text/xml');
            assertEncoding($doc);
        }, 'XML: empty');
        $this->assertTest(function () {
            $parser = new DOMParser();
            $doc = $parser->parseFromString("<meta charset=\"latin2\">", 'text/html');
            assertEncoding($doc);
        }, 'HTML: meta charset');
        $this->assertTest(function () {
            $parser = new DOMParser();
            $doc = $parser->parseFromString("<?xml version=\"1.0\" encoding=\"latin2\"?><x/>", 'text/xml');
            assertEncoding($doc);
        }, 'XML: XML declaration');
    }
}
