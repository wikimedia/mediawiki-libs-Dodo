<?php 
namespace Wikimedia\Dodo\Tests\Wpt\Dom;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\Wpt\Harness\WptTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Node-isConnected-shadow-dom.html.
class NodeIsConnectedShadowDomTest extends WptTestHarness
{
    public function testIsConnected($mode)
    {
        $this->assertTest(function () use(&$mode) {
            $host = $this->doc->createElement('div');
            $this->getDocBody( $this->doc )->appendChild($host);
            $root = $host->attachShadow(['mode' => $mode]);
            $node = $this->doc->createElement('div');
            $root->appendChild($node);
            $this->assertTrueData($node->isConnected);
        }, "Node.isConnected in a  shadow tree{$mode}");
    }
    public function testNodeIsConnectedShadowDom()
    {
        $this->doc = $this->loadWptHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/Node-isConnected-shadow-dom.html');
        foreach (['closed', 'open'] as $mode => $___) {
            $this->testIsConnected($mode);
        }
    }
}
