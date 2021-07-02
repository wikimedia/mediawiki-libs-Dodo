<?php 
namespace Wikimedia\Dodo\Tests\WPT\Domparsing\Resources;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/domparsing/resources/domparser-iframe-pushstate.html.
class DomparserIframePushstateTest extends WPTTestHarness
{
    public function testDomparserIframePushstate()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/domparsing/resources/domparser-iframe-pushstate.html');
        $history->pushState(null, '', '/fake/push-state-from-iframe');
    }
}
