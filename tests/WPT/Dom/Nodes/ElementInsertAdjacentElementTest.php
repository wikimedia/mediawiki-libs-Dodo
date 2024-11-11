<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom\Nodes;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Element-insertAdjacentElement.html.
class ElementInsertAdjacentElementTest extends WPTTestHarness
{
    public function testElementInsertAdjacentElement()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/Element-insertAdjacentElement.html');
        $target = $this->doc->getElementById('target');
        $target2 = $this->doc->getElementById('target2');
        $this->assertTest(function () use (&$target, &$target2) {
            $this->wptAssertThrowsDom('SyntaxError', function () use (&$target) {
                $target->insertAdjacentElement('test', $this->doc->getElementById('test1'));
            });
            $this->wptAssertThrowsDom('SyntaxError', function () use (&$target2) {
                $target2->insertAdjacentElement('test', $this->doc->getElementById('test1'));
            });
        }, 'Inserting to an invalid location should cause a Syntax Error exception');
        $this->assertTest(function () use (&$target, &$target2) {
            $el = $target->insertAdjacentElement('beforebegin', $this->doc->getElementById('test1'));
            $this->wptAssertEquals($target->getPreviousSibling()->id, 'test1');
            $this->wptAssertEquals($el->id, 'test1');
            $el = $target2->insertAdjacentElement('beforebegin', $this->doc->getElementById('test1'));
            $this->wptAssertEquals($target2->getPreviousSibling()->id, 'test1');
            $this->wptAssertEquals($el->id, 'test1');
        }, "Inserted element should be target element's previous sibling for 'beforebegin' case");
        $this->assertTest(function () use (&$target, &$target2) {
            $el = $target->insertAdjacentElement('afterbegin', $this->doc->getElementById('test2'));
            $this->wptAssertEquals($target->firstChild->id, 'test2');
            $this->wptAssertEquals($el->id, 'test2');
            $el = $target2->insertAdjacentElement('afterbegin', $this->doc->getElementById('test2'));
            $this->wptAssertEquals($target2->firstChild->id, 'test2');
            $this->wptAssertEquals($el->id, 'test2');
        }, "Inserted element should be target element's first child for 'afterbegin' case");
        $this->assertTest(function () use (&$target, &$target2) {
            $el = $target->insertAdjacentElement('beforeend', $this->doc->getElementById('test3'));
            $this->wptAssertEquals($target->lastChild->id, 'test3');
            $this->wptAssertEquals($el->id, 'test3');
            $el = $target2->insertAdjacentElement('beforeend', $this->doc->getElementById('test3'));
            $this->wptAssertEquals($target2->lastChild->id, 'test3');
            $this->wptAssertEquals($el->id, 'test3');
        }, "Inserted element should be target element's last child for 'beforeend' case");
        $this->assertTest(function () use (&$target, &$target2) {
            $el = $target->insertAdjacentElement('afterend', $this->doc->getElementById('test4'));
            $this->wptAssertEquals($target->nextSibling->id, 'test4');
            $this->wptAssertEquals($el->id, 'test4');
            $el = $target2->insertAdjacentElement('afterend', $this->doc->getElementById('test4'));
            $this->wptAssertEquals($target2->nextSibling->id, 'test4');
            $this->wptAssertEquals($el->id, 'test4');
        }, "Inserted element should be target element's next sibling for 'afterend' case");
        $this->assertTest(function () {
            $docElement = $this->doc->documentElement;
            $docElement->style->visibility = 'hidden';
            $this->wptAssertThrowsDom('HierarchyRequestError', function () use (&$docElement) {
                $el = $docElement->insertAdjacentElement('beforebegin', $this->doc->getElementById('test1'));
                $this->wptAssertEquals($el, null);
            });
            $el = $docElement->insertAdjacentElement('afterbegin', $this->doc->getElementById('test2'));
            $this->wptAssertEquals($docElement->firstChild->id, 'test2');
            $this->wptAssertEquals($el->id, 'test2');
            $el = $docElement->insertAdjacentElement('beforeend', $this->doc->getElementById('test3'));
            $this->wptAssertEquals($docElement->lastChild->id, 'test3');
            $this->wptAssertEquals($el->id, 'test3');
            $this->wptAssertThrowsDom('HierarchyRequestError', function () use (&$docElement) {
                $el = $docElement->insertAdjacentElement('afterend', $this->doc->getElementById('test4'));
                $this->wptAssertEquals($el, null);
            });
        }, 'Adding more than one child to document should cause a HierarchyRequestError exception');
    }
}
