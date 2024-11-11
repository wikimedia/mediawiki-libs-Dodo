<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom\Nodes;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Element-closest.html.
class ElementClosestTest extends WPTTestHarness
{
    public function doTestHelper($aSelector, $aElementId, $aTargetId)
    {
        $this->assertTest(function () use (&$aElementId, &$aSelector, &$aTargetId) {
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
        $this->doTestHelper('select', 'test12', 'test3');
        $this->doTestHelper('fieldset', 'test13', 'test2');
        $this->doTestHelper('div', 'test13', 'test6');
        $this->doTestHelper('body', 'test3', 'body');
        $this->doTestHelper('[default]', 'test4', 'test4');
        $this->doTestHelper('[selected]', 'test4', '');
        $this->doTestHelper('[selected]', 'test11', 'test11');
        $this->doTestHelper('[name="form-a"]', 'test12', 'test5');
        $this->doTestHelper('form[name="form-a"]', 'test13', 'test5');
        $this->doTestHelper('input[required]', 'test9', 'test9');
        $this->doTestHelper('select[required]', 'test9', '');
        $this->doTestHelper('div:not(.div1)', 'test13', 'test7');
        $this->doTestHelper('div.div3', 'test6', 'test8');
        $this->doTestHelper('div#test7', 'test1', 'test7');
        $this->doTestHelper('.div3 > .div2', 'test12', 'test7');
        $this->doTestHelper('.div3 > .div1', 'test12', '');
        $this->doTestHelper('form > input[required]', 'test9', '');
        $this->doTestHelper('fieldset > select[required]', 'test12', 'test3');
        $this->doTestHelper('input + fieldset', 'test6', '');
        $this->doTestHelper('form + form', 'test3', 'test5');
        $this->doTestHelper('form + form', 'test5', 'test5');
        $this->doTestHelper(':empty', 'test10', 'test10');
        $this->doTestHelper(':last-child', 'test11', 'test2');
        $this->doTestHelper(':first-child', 'test12', 'test3');
        $this->doTestHelper(':invalid', 'test11', 'test2');
        $this->doTestHelper(':scope', 'test4', 'test4');
        $this->doTestHelper('select > :scope', 'test4', 'test4');
        $this->doTestHelper('div > :scope', 'test4', '');
        $this->doTestHelper(':has(> :scope)', 'test4', 'test3');
    }
}
