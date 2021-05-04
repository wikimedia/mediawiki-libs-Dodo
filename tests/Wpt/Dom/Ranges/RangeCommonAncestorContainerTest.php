<?php 
namespace Wikimedia\Dodo\Tests\Wpt\Dom;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Tests\Wpt\Harness\WptTestHarness;
// @see vendor/web-platform-tests/wpt/dom/ranges/Range-commonAncestorContainer.html.
class RangeCommonAncestorContainerTest extends WptTestHarness
{
    public function testRangeCommonAncestorContainer()
    {
        $this->doc = $this->loadWptHtmlFile('vendor/web-platform-tests/wpt/dom/ranges/Range-commonAncestorContainer.html');
        array_unshift($testRanges, '[detached]');
        for ($i = 0; $i < count($testRanges); $i++) {
            $this->assertTest(function () use(&$i) {
                $range = null;
                if ($i == 0) {
                    $range = $this->doc->createRange();
                    $range->detach();
                } else {
                    $range = rangeFromEndpoints(eval($testRanges[$i]));
                }
                // "Let container be start node."
                $container = $range->startContainer;
                // "While container is not an inclusive ancestor of end node, let
                // container be container's parent."
                while ($container != $range->endContainer && !isAncestor($container, $range->endContainer)) {
                    $container = $container->parentNode;
                }
                // "Return container."
                $this->assertEqualsData($range->commonAncestorContainer, $container);
            }, $i . ': range ' . $testRanges[$i]);
        }
        $testDiv->style->display = 'none';
    }
}
