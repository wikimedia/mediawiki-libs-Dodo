<?php 
namespace Wikimedia\Dodo\Tests\WPT\Domparsing;
use Wikimedia\Dodo\DOMParser;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/domparsing/xmldomparser.html.
class XmldomparserTest extends WPTTestHarness
{
    public function testXmldomparser()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/domparsing/xmldomparser.html');
        $this->assertTest(function () {
            $this->wptAssertEquals((new DOMParser())->parseFromString('<html></html>', 'text/xml')->readyState, 'complete');
        });
    }
}
