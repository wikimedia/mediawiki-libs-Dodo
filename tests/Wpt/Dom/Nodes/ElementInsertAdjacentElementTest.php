<?php 
namespace Wikimedia\Dodo\Tests\Wpt\Dom;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\Wpt\Harness\WptTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Element-insertAdjacentElement.html.
class ElementInsertAdjacentElementTest extends WptTestHarness
{
    public function testElementInsertAdjacentElement()
    {
        $this->source_file = 'vendor/web-platform-tests/wpt/dom/nodes/Element-insertAdjacentElement.html';
        $target = $this->doc->getElementById('target');
        $target2 = $this->doc->getElementById('target2');
        $this->assertTest(function () use(&$target, &$target2) {
            $this->assertThrowsDomData('SyntaxError', function () use(&$target) {
                $target->insertAdjacentElement('test', $this->doc->getElementById('test1'));
            });
            $this->assertThrowsDomData('SyntaxError', function () use(&$target2) {
                $target2->insertAdjacentElement('test', $this->doc->getElementById('test1'));
            });
        }, 'Inserting to an invalid location should cause a Syntax Error exception');
        $this->assertTest(function () use(&$target, &$target2) {
            $el = $target->insertAdjacentElement('beforebegin', $this->doc->getElementById('test1'));
            $this->assertEqualsData($target->previousSibling->id, 'test1');
            $this->assertEqualsData($el->id, 'test1');
            $el = $target2->insertAdjacentElement('beforebegin', $this->doc->getElementById('test1'));
            $this->assertEqualsData($target2->previousSibling->id, 'test1');
            $this->assertEqualsData($el->id, 'test1');
        }, "Inserted element should be target element's previous sibling for 'beforebegin' case");
        $this->assertTest(function () use(&$target, &$target2) {
            $el = $target->insertAdjacentElement('afterbegin', $this->doc->getElementById('test2'));
            $this->assertEqualsData($target->firstChild->id, 'test2');
            $this->assertEqualsData($el->id, 'test2');
            $el = $target2->insertAdjacentElement('afterbegin', $this->doc->getElementById('test2'));
            $this->assertEqualsData($target2->firstChild->id, 'test2');
            $this->assertEqualsData($el->id, 'test2');
        }, "Inserted element should be target element's first child for 'afterbegin' case");
        $this->assertTest(function () use(&$target, &$target2) {
            $el = $target->insertAdjacentElement('beforeend', $this->doc->getElementById('test3'));
            $this->assertEqualsData($target->lastChild->id, 'test3');
            $this->assertEqualsData($el->id, 'test3');
            $el = $target2->insertAdjacentElement('beforeend', $this->doc->getElementById('test3'));
            $this->assertEqualsData($target2->lastChild->id, 'test3');
            $this->assertEqualsData($el->id, 'test3');
        }, "Inserted element should be target element's last child for 'beforeend' case");
        $this->assertTest(function () use(&$target, &$target2) {
            $el = $target->insertAdjacentElement('afterend', $this->doc->getElementById('test4'));
            $this->assertEqualsData($target->nextSibling->id, 'test4');
            $this->assertEqualsData($el->id, 'test4');
            $el = $target2->insertAdjacentElement('afterend', $this->doc->getElementById('test4'));
            $this->assertEqualsData($target2->nextSibling->id, 'test4');
            $this->assertEqualsData($el->id, 'test4');
        }, "Inserted element should be target element's next sibling for 'afterend' case");
        $this->assertTest(function () {
            $docElement = $this->doc->documentElement;
            $docElement->style->visibility = 'hidden';
            $this->assertThrowsDomData('HierarchyRequestError', function () use(&$docElement) {
                $el = $docElement->insertAdjacentElement('beforebegin', $this->doc->getElementById('test1'));
                $this->assertEqualsData($el, null);
            });
            $el = $docElement->insertAdjacentElement('afterbegin', $this->doc->getElementById('test2'));
            $this->assertEqualsData($docElement->firstChild->id, 'test2');
            $this->assertEqualsData($el->id, 'test2');
            $el = $docElement->insertAdjacentElement('beforeend', $this->doc->getElementById('test3'));
            $this->assertEqualsData($docElement->lastChild->id, 'test3');
            $this->assertEqualsData($el->id, 'test3');
            $this->assertThrowsDomData('HierarchyRequestError', function () use(&$docElement) {
                $el = $docElement->insertAdjacentElement('afterend', $this->doc->getElementById('test4'));
                $this->assertEqualsData($el, null);
            });
        }, 'Adding more than one child to document should cause a HierarchyRequestError exception');
    }
}
