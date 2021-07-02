<?php 
namespace Wikimedia\Dodo\Tests\WPT\Domparsing;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/domparsing/insert-adjacent.html.
class InsertAdjacentTest extends WPTTestHarness
{
    public function wrap($text)
    {
        return '<h3>' . $text . '</h3>';
    }
    public function testInsertAdjacent()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/domparsing/insert-adjacent.html');
        $possiblePositions = ['beforebegin' => 'previousSibling', 'afterbegin' => 'firstChild', 'beforeend' => 'lastChild', 'afterend' => 'nextSibling'];
        $el = $this->doc->querySelector('#element');
        foreach ($get_object_vars as $position) {
            $html = wrap($position);
            $this->assertTest(function () use(&$el, &$position, &$html, &$possiblePositions) {
                $el->insertAdjacentHTML($position, $html);
                $heading = $this->doc->createElement('h3');
                $heading->innerHTML = $position;
                $this->wptAssertEquals($el[$possiblePositions[$position]]->nodeName, 'H3');
                $this->wptAssertEquals($el[$possiblePositions[$position]]->firstChild->nodeType, Node::TEXT_NODE);
            }, 'insertAdjacentHTML(' . $position . ', ' . $html . ' )');
        }
    }
}
