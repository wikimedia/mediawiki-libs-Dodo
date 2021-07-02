<?php 
namespace Wikimedia\Dodo\Tests\WPT\Domparsing\Resources;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/domparsing/resources/domparser-iframe-base-pushstate.html.
class DomparserIframeBasePushstateTest extends WPTTestHarness
{
    public function testDomparserIframeBasePushstate()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/domparsing/resources/domparser-iframe-base-pushstate.html');
        $history->pushState(null, '', '/fake/push-state-from-iframe');
    }
}
