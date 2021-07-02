<?php 
namespace Wikimedia\Dodo\Tests\WPT\Domparsing;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/domparsing/DOMParser-parseFromString-url-pushstate.html.
class DOMParserParseFromStringUrlPushstateTest extends WPTTestHarness
{
    public function testDOMParserParseFromStringUrlPushstate()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/domparsing/DOMParser-parseFromString-url-pushstate.html');
        $history->pushState(null, '', '/fake/push-state-from-outer-frame');
    }
}
