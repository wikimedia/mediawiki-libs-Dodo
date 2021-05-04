<?php 
namespace Wikimedia\Dodo\Tests\Wpt\Dom;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Tests\Wpt\Harness\WptTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Node-contains.html.
class NodeContainsTest extends WptTestHarness
{
    public function testNodeContains()
    {
        $this->doc = $this->loadWptHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/Node-contains.html');
        foreach ($this->testNodes as $referenceName) {
            $reference = eval($referenceName);
            $this->assertTest(function () use(&$reference) {
                $this->assertFalseData($reference->contains(null));
            }, $referenceName . '.contains(null)');
            foreach ($this->testNodes as $otherName) {
                $other = eval($otherName);
                $this->assertTest(function () use(&$other, &$reference) {
                    $ancestor = $other;
                    while ($ancestor && $ancestor !== $reference) {
                        $ancestor = $ancestor->parentNode;
                    }
                    if ($ancestor === $reference) {
                        $this->assertTrueData($reference->contains($other));
                    } else {
                        $this->assertFalseData($reference->contains($other));
                    }
                }, $referenceName . '.contains(' . $otherName . ')');
            }
        }
        $testDiv->parentNode->removeChild($testDiv);
    }
}
