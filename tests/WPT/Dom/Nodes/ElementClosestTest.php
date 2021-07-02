<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom\Nodes;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Element-closest.html.
class ElementClosestTest extends WPTTestHarness
{
    public function doTest($aSelector, $aElementId, $aTargetId)
    {
        $this->assertTest(function () use(&$aElementId, &$aSelector, &$aTargetId) {
            $el = $this->doc->getElementById($aElementId)->closest($aSelector);
            if ($el === null) {
                $this->wptAssertEquals('', $aTargetId, $aSelector);
            } else {
                $this->wptAssertEquals($el->id, $aTargetId, $aSelector);
            }
        }, "Element.closest with context node '" . $aElementId . "' and selector '" . $aSelector . "'");
    }
    public function testElementClosest()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/Element-closest.html');
        $this->doTest('select', 'test12', 'test3');
        $this->doTest('fieldset', 'test13', 'test2');
        $this->doTest('div', 'test13', 'test6');
        $this->doTest('body', 'test3', 'body');
        $this->doTest('[default]', 'test4', 'test4');
        $this->doTest('[selected]', 'test4', '');
        $this->doTest('[selected]', 'test11', 'test11');
        $this->doTest('[name="form-a"]', 'test12', 'test5');
        $this->doTest('form[name="form-a"]', 'test13', 'test5');
        $this->doTest('input[required]', 'test9', 'test9');
        $this->doTest('select[required]', 'test9', '');
        $this->doTest('div:not(.div1)', 'test13', 'test7');
        $this->doTest('div.div3', 'test6', 'test8');
        $this->doTest('div#test7', 'test1', 'test7');
        $this->doTest('.div3 > .div2', 'test12', 'test7');
        $this->doTest('.div3 > .div1', 'test12', '');
        $this->doTest('form > input[required]', 'test9', '');
        $this->doTest('fieldset > select[required]', 'test12', 'test3');
        $this->doTest('input + fieldset', 'test6', '');
        $this->doTest('form + form', 'test3', 'test5');
        $this->doTest('form + form', 'test5', 'test5');
        $this->doTest(':empty', 'test10', 'test10');
        $this->doTest(':last-child', 'test11', 'test2');
        $this->doTest(':first-child', 'test12', 'test3');
        $this->doTest(':invalid', 'test11', 'test2');
        $this->doTest(':scope', 'test4', 'test4');
        $this->doTest('select > :scope', 'test4', 'test4');
        $this->doTest('div > :scope', 'test4', '');
        $this->doTest(':has(> :scope)', 'test4', 'test3');
    }
}
