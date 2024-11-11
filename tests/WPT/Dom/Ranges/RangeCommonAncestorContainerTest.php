<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom\Ranges;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Range;
use Wikimedia\Dodo\Tests\Harness\Utils\Common;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/ranges/Range-commonAncestorContainer.html.
class RangeCommonAncestorContainerTest extends WPTTestHarness
{
    public function testRangeCommonAncestorContainer()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/ranges/Range-commonAncestorContainer.html');
        array_unshift($this->getCommon()->testRanges, '[detached]');
        for ($i = 0; $i < count($this->getCommon()->testRanges); $i++) {
            $this->assertTest(function () use (&$i) {
                $range = null;
                if ($i == 0) {
                    $range = $this->doc->createRange();
                    $range->detach();
                } else {
                    $range = Common::rangeFromEndpoints($this->wptEvalNode($this->getCommon()->testRanges[$i]));
                }
                // "Let container be start node."
                $container = $range->startContainer;
                // "While container is not an inclusive ancestor of end node, let
                // container be container's parent."
                while ($container != $range->endContainer && !Common::isAncestor($container, $range->endContainer)) {
                    $container = $container->parentNode;
                }
                // "Return container."
                $this->wptAssertEquals($range->commonAncestorContainer, $container);
            }, $i . ': range ' . $this->getCommon()->testRanges[$i]);
        }
        $this->getCommon()->testDiv->style->display = 'none';
    }
}
