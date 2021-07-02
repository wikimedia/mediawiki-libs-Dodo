<?php 
namespace Wikimedia\Dodo\Tests\WPT\Domparsing;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/domparsing/innerhtml-06.html.
class Innerhtml06Test extends WPTTestHarness
{
    public function testInnerhtml06()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/domparsing/innerhtml-06.html');
        $this->assertTest(function () {
            $math = $this->doc->getElementById('d1')->firstChild;
            $this->wptAssertEquals($math->innerHTML, '<mi>x</mi>');
        }, 'innerHTML defined on math.');
    }
}
