<?php 
namespace Wikimedia\Dodo\Tests\WPT\Domparsing;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DOMParser;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/domparsing/DOMParser-parseFromString-xml-doctype.html.
class DOMParserParseFromStringXmlDoctypeTest extends WPTTestHarness
{
    public function testDOMParserParseFromStringXmlDoctype()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/domparsing/DOMParser-parseFromString-xml-doctype.html');
        $this->assertTest(function () {
            $doc = (new DOMParser())->parseFromString('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"><html><div id="test"/></html>', 'application/xhtml+xml');
            $div = $doc->getElementById('test');
            $this->wptAssertEquals($div, null);
            // If null, then this was a an error document (didn't parse the input successfully)
        }, 'Doctype parsing of System Id must fail on ommitted value');
        $this->assertTest(function () {
            $doc = (new DOMParser())->parseFromString('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" ""><html><div id="test"/></html>', 'application/xhtml+xml');
            $div = $doc->getElementById('test');
            $this->wptAssertNotEquals($div, null);
            // If found, then the DOMParser didn't generate an error document
        }, 'Doctype parsing of System Id can handle empty string');
        $this->assertTest(function () {
            $doc = (new DOMParser())->parseFromString('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "x"><html><div id="test"/></html>', 'application/xhtml+xml');
            $div = $doc->getElementById('test');
            $this->wptAssertNotEquals($div, null);
        }, 'Doctype parsing of System Id can handle a quoted value');
    }
}
