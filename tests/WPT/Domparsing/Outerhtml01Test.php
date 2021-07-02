<?php 
namespace Wikimedia\Dodo\Tests\WPT\Domparsing;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/domparsing/outerhtml-01.html.
class Outerhtml01Test extends WPTTestHarness
{
    public function testOuterhtml01()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/domparsing/outerhtml-01.html');
        $this->assertTest(function () {
            $this->wptAssertThrowsDom('NO_MODIFICATION_ALLOWED_ERR', function () {
                $this->doc->documentElement->outerHTML = '<html><p>FAIL: Should have thrown an error</p></html>';
            });
        });
    }
}
