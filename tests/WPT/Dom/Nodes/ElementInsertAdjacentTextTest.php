<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom\Nodes;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Text;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Element-insertAdjacentText.html.
class ElementInsertAdjacentTextTest extends WPTTestHarness
{
    public function testElementInsertAdjacentText()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/Element-insertAdjacentText.html');
        $target = $this->doc->getElementById('target');
        $target2 = $this->doc->getElementById('target2');
        $this->assertTest(function () use (&$target, &$target2) {
            $this->wptAssertThrowsDom('SyntaxError', function () use (&$target) {
                $target->insertAdjacentText('test', 'text');
            });
            $this->wptAssertThrowsDom('SyntaxError', function () use (&$target2) {
                $target2->insertAdjacentText('test', 'test');
            });
        }, 'Inserting to an invalid location should cause a Syntax Error exception');
        $this->assertTest(function () use (&$target, &$target2) {
            $target->insertAdjacentText('beforebegin', 'test1');
            $this->wptAssertEquals($target->getPreviousSibling()->nodeValue, 'test1');
            $target2->insertAdjacentText('beforebegin', 'test1');
            $this->wptAssertEquals($target2->getPreviousSibling()->nodeValue, 'test1');
        }, "Inserted text node should be target element's previous sibling for 'beforebegin' case");
        $this->assertTest(function () use (&$target, &$target2) {
            $target->insertAdjacentText('afterbegin', 'test2');
            $this->wptAssertEquals($target->firstChild->nodeValue, 'test2');
            $target2->insertAdjacentText('afterbegin', 'test2');
            $this->wptAssertEquals($target2->firstChild->nodeValue, 'test2');
        }, "Inserted text node should be target element's first child for 'afterbegin' case");
        $this->assertTest(function () use (&$target, &$target2) {
            $target->insertAdjacentText('beforeend', 'test3');
            $this->wptAssertEquals($target->lastChild->nodeValue, 'test3');
            $target2->insertAdjacentText('beforeend', 'test3');
            $this->wptAssertEquals($target2->lastChild->nodeValue, 'test3');
        }, "Inserted text node should be target element's last child for 'beforeend' case");
        $this->assertTest(function () use (&$target, &$target2) {
            $target->insertAdjacentText('afterend', 'test4');
            $this->wptAssertEquals($target->nextSibling->nodeValue, 'test4');
            $target2->insertAdjacentText('afterend', 'test4');
            $this->wptAssertEquals($target->nextSibling->nodeValue, 'test4');
        }, "Inserted text node should be target element's next sibling for 'afterend' case");
        $this->assertTest(function () {
            $docElement = $this->doc->documentElement;
            $docElement->style->visibility = 'hidden';
            $this->wptAssertThrowsDom('HierarchyRequestError', function () use (&$docElement) {
                $docElement->insertAdjacentText('beforebegin', 'text1');
            });
            $docElement->insertAdjacentText('afterbegin', 'test2');
            $this->wptAssertEquals($docElement->firstChild->nodeValue, 'test2');
            $docElement->insertAdjacentText('beforeend', 'test3');
            $this->wptAssertEquals($docElement->lastChild->nodeValue, 'test3');
            $this->wptAssertThrowsDom('HierarchyRequestError', function () use (&$docElement) {
                $docElement->insertAdjacentText('afterend', 'test4');
            });
        }, 'Adding more than one child to document should cause a HierarchyRequestError exception');
    }
}
