<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom\Nodes;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Node-contains.html.
class NodeContainsTest extends WPTTestHarness
{
    public function testNodeContains()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/Node-contains.html');
        foreach ($this->getCommon()->testNodes as $referenceName) {
            $reference = $this->wptEvalNode($referenceName);
            $this->assertTest(function () use(&$reference) {
                $this->wptAssertFalse($reference->contains(null));
            }, $referenceName . '.contains(null)');
            foreach ($this->getCommon()->testNodes as $otherName) {
                $other = $this->wptEvalNode($otherName);
                $this->assertTest(function () use(&$other, &$reference) {
                    $ancestor = $other;
                    while ($ancestor && $ancestor !== $reference) {
                        $ancestor = $ancestor->parentNode;
                    }
                    if ($ancestor === $reference) {
                        $this->wptAssertTrue($reference->contains($other));
                    } else {
                        $this->wptAssertFalse($reference->contains($other));
                    }
                }, $referenceName . '.contains(' . $otherName . ')');
            }
        }
        $this->getCommon()->testDiv->parentNode->removeChild($this->getCommon()->testDiv);
    }
}
