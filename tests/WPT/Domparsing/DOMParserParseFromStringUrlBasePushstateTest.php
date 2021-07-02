<?php 
namespace Wikimedia\Dodo\Tests\WPT\Domparsing;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/domparsing/DOMParser-parseFromString-url-base-pushstate.html.
class DOMParserParseFromStringUrlBasePushstateTest extends WPTTestHarness
{
    public function testDOMParserParseFromStringUrlBasePushstate()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/domparsing/DOMParser-parseFromString-url-base-pushstate.html');
        $history->pushState(null, '', '/fake/push-state-from-outer-frame');
    }
}
