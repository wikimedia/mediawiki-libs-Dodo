<?php 
namespace Wikimedia\Dodo\Tests\Wpt\Dom;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\DocumentFragment;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Comment;
use Wikimedia\Dodo\Text;
use Wikimedia\Dodo\Tests\Wpt\Harness\WptTestHarness;
// @see vendor/web-platform-tests/wpt/dom/ranges/Range-commonAncestorContainer-2.html.
class RangeCommonAncestorContainer2Test extends WptTestHarness
{
    public function testRangeCommonAncestorContainer2()
    {
        $this->source_file = 'vendor/web-platform-tests/wpt/dom/ranges/Range-commonAncestorContainer-2.html';
        $this->assertTest(function () {
            $range = $this->doc->createRange();
            $range->detach();
            $this->assertEqualsData($range->commonAncestorContainer, $this->doc);
        }, 'Detached Range');
        $this->assertTest(function () {
            $df = $this->doc->createDocumentFragment();
            $foo = $df->appendChild($this->doc->createElement('foo'));
            $foo->appendChild($this->doc->createTextNode('Foo'));
            $bar = $df->appendChild($this->doc->createElement('bar'));
            $bar->appendChild($this->doc->createComment('Bar'));
            foreach ([
                // start node, start offset, end node, end offset, expected cAC
                [$foo, 0, $bar, 0, $df],
                [$foo, 0, $foo->firstChild, 3, $foo],
                [$foo->firstChild, 0, $bar, 0, $df],
                [$foo->firstChild, 3, $bar->firstChild, 2, $df],
            ] as $t) {
                $this->assertTest(function () use(&$t) {
                    $range = $this->doc->createRange();
                    $range->setStart($t[0], $t[1]);
                    $range->setEnd($t[2], $t[3]);
                    $this->assertEqualsData($range->commonAncestorContainer, $t[4]);
                });
            }
        }, 'Normal Ranges');
    }
}
