<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Attr;
use Wikimedia\Dodo\HTMLDivElement;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Document-getElementById.html.
class DocumentGetElementByIdTest extends WPTTestHarness
{
    public function testDocumentGetElementById()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/Document-getElementById.html');
        $gBody = $this->doc->getElementsByTagName('body')[0];
        $this->assertTest(function () {
            $this->assertEqualsData($this->doc->getElementById(''), null);
        }, 'Calling document.getElementById with an empty string argument.');
        $this->assertTest(function () {
            $element = $this->doc->createElement('div');
            $element->setAttribute('id', 'null');
            $this->getDocBody( $this->doc )->appendChild($element);
            $this->{$this}->addCleanup(function () use(&$element) {
                $this->getDocBody( $this->doc )->removeChild($element);
            });
            $this->assertEqualsData($this->doc->getElementById(null), $element);
        }, 'Calling document.getElementById with a null argument.');
        $this->assertTest(function () {
            $element = $this->doc->createElement('div');
            $element->setAttribute('id', NULL);
            $this->getDocBody( $this->doc )->appendChild($element);
            $this->{$this}->addCleanup(function () use(&$element) {
                $this->getDocBody( $this->doc )->removeChild($element);
            });
            $this->assertEqualsData($this->doc->getElementById(null), $element);
        }, 'Calling document.getElementById with an undefined argument.');
        $this->assertTest(function () {
            $bar = $this->doc->getElementById('test1');
            $this->assertNotEqualsData($bar, null, 'should not be null');
            $this->assertEqualsData($bar->tagName, 'DIV', 'should have expected tag name.');
            $this->assertTrueData($bar instanceof HTMLDivElement, 'should be a valid Element instance');
        }, 'on static page');
        $this->assertTest(function () use(&$gBody) {
            $TEST_ID = 'test2';
            $test = $this->doc->createElement('div');
            $test->setAttribute('id', $TEST_ID);
            $gBody->appendChild($test);
            // test: appended element
            $result = $this->doc->getElementById($TEST_ID);
            $this->assertNotEqualsData($result, null, 'should not be null.');
            $this->assertEqualsData($result->tagName, 'DIV', "should have appended element's tag name");
            $this->assertTrueData($result instanceof HTMLDivElement, 'should be a valid Element instance');
            // test: removed element
            $gBody->removeChild($test);
            $removed = $this->doc->getElementById($TEST_ID);
            // `document.getElementById()` returns `null` if there is none.
            // https://dom.spec.whatwg.org/#dom-nonelementparentnode-getelementbyid
            $this->assertEqualsData($removed, null, 'should not get removed element.');
        }, 'Document.getElementById with a script-inserted element');
        $this->assertTest(function () use(&$gBody) {
            // setup fixtures.
            $TEST_ID = 'test3';
            $test = $this->doc->createElement('div');
            $test->setAttribute('id', $TEST_ID);
            $gBody->appendChild($test);
            // update id
            $UPDATED_ID = 'test3-updated';
            $test->setAttribute('id', $UPDATED_ID);
            $e = $this->doc->getElementById($UPDATED_ID);
            $this->assertEqualsData($e, $test, 'should get the element with id.');
            $old = $this->doc->getElementById($TEST_ID);
            $this->assertEqualsData($old, null, "shouldn't get the element by the old id.");
            // remove id.
            $test->removeAttribute('id');
            $e2 = $this->doc->getElementById($UPDATED_ID);
            $this->assertEqualsData($e2, null, 'should return null when the passed id is none in document.');
        }, 'update `id` attribute via setAttribute/removeAttribute');
        $this->assertTest(function () {
            $TEST_ID = 'test4-should-not-exist';
            $e = $this->doc->createElement('div');
            $e->setAttribute('id', $TEST_ID);
            $this->assertEqualsData($this->doc->getElementById($TEST_ID), null, 'should be null');
            $this->getDocBody( $this->doc )->appendChild($e);
            $this->assertEqualsData($this->doc->getElementById($TEST_ID), $e, 'should be the appended element');
        }, 'Ensure that the id attribute only affects elements present in a document');
        $this->assertTest(function () use(&$gBody) {
            // the method should return the 1st element.
            $TEST_ID = 'test5';
            $target = $this->doc->getElementById($TEST_ID);
            $this->assertNotEqualsData($target, null, 'should not be null');
            $this->assertEqualsData($target->getAttribute('data-name'), '1st', 'should return the 1st');
            // even if after the new element was appended.
            $element4 = $this->doc->createElement('div');
            $element4->setAttribute('id', $TEST_ID);
            $element4->setAttribute('data-name', '4th');
            $gBody->appendChild($element4);
            $target2 = $this->doc->getElementById($TEST_ID);
            $this->assertNotEqualsData($target2, null, 'should not be null');
            $this->assertEqualsData($target2->getAttribute('data-name'), '1st', 'should be the 1st');
            // should return the next element after removed the subtree including the 1st element.
            $target2->parentNode->removeChild($target2);
            $target3 = $this->doc->getElementById($TEST_ID);
            $this->assertNotEqualsData($target3, null, 'should not be null');
            $this->assertEqualsData($target3->getAttribute('data-name'), '4th', 'should be the 4th');
        }, "in tree order, within the context object's tree");
        $this->assertTest(function () {
            $TEST_ID = 'test6';
            $s = $this->doc->createElement('div');
            $s->setAttribute('id', $TEST_ID);
            // append to Element, not Document.
            $this->doc->createElement('div')->appendChild($s);
            $this->assertEqualsData($this->doc->getElementById($TEST_ID), null, 'should be null');
        }, 'Modern browsers optimize this method with using internal id cache. ' . 'This test checks that their optimization should effect only append to `Document`, not append to `Node`.');
        $this->assertTest(function () use(&$gBody) {
            $TEST_ID = 'test7';
            $element = $this->doc->createElement('div');
            $element->setAttribute('id', $TEST_ID);
            $gBody->appendChild($element);
            $target = $this->doc->getElementById($TEST_ID);
            $this->assertEqualsData($target, $element, 'should return the element before changing the value');
            $element->attributes[0]->value = $TEST_ID . '-updated';
            $target2 = $this->doc->getElementById($TEST_ID);
            $this->assertEqualsData($target2, null, 'should return null after updated id via Attr.value');
            $target3 = $this->doc->getElementById($TEST_ID . '-updated');
            $this->assertEqualsData($target3, $element, 'should be equal to the updated element.');
        }, "changing attribute's value via `Attr` gotten from `Element.attribute`.");
        $this->assertTest(function () use(&$gBody) {
            $TEST_ID = 'test8';
            // setup fixture
            $element = $this->doc->createElement('div');
            $element->setAttribute('id', $TEST_ID . '-fixture');
            $gBody->appendChild($element);
            // add id-ed element with using innerHTML
            $element->innerHTML = "<div id='" . $TEST_ID . "'></div>";
            $test = $this->doc->getElementById($TEST_ID);
            $this->assertEqualsData($test, $element->firstChild, 'should not be null');
            $this->assertEqualsData($test->tagName, 'DIV', 'should have expected tag name.');
            $this->assertTrueData($test instanceof HTMLDivElement, 'should be a valid Element instance');
        }, 'add id attribute via innerHTML');
        $this->assertTest(function () use(&$gBody) {
            $TEST_ID = 'test9';
            // add fixture
            $fixture = $this->doc->createElement('div');
            $fixture->setAttribute('id', $TEST_ID . '-fixture');
            $gBody->appendChild($fixture);
            $element = $this->doc->createElement('div');
            $element->setAttribute('id', $TEST_ID);
            $fixture->appendChild($element);
            // check 'getElementById' should get the 'element'
            $this->assertEqualsData($this->doc->getElementById($TEST_ID), $element, 'should not be null');
            // remove id-ed element with using innerHTML (clear 'element')
            $fixture->innerHTML = '';
            $test = $this->doc->getElementById($TEST_ID);
            $this->assertEqualsData($test, null, 'should be null.');
        }, 'remove id attribute via innerHTML');
        $this->assertTest(function () use(&$gBody) {
            $TEST_ID = 'test10';
            // setup fixture
            $element = $this->doc->createElement('div');
            $element->setAttribute('id', $TEST_ID . '-fixture');
            $gBody->appendChild($element);
            // add id-ed element with using outerHTML
            $element->outerHTML = "<div id='" . $TEST_ID . "'></div>";
            $test = $this->doc->getElementById($TEST_ID);
            $this->assertNotEqualsData($test, null, 'should not be null');
            $this->assertEqualsData($test->tagName, 'DIV', 'should have expected tag name.');
            $this->assertTrueData($test instanceof HTMLDivElement, 'should be a valid Element instance');
        }, 'add id attribute via outerHTML');
        $this->assertTest(function () use(&$gBody) {
            $TEST_ID = 'test11';
            $element = $this->doc->createElement('div');
            $element->setAttribute('id', $TEST_ID);
            $gBody->appendChild($element);
            $test = $this->doc->getElementById($TEST_ID);
            $this->assertEqualsData($test, $element, 'should be equal to the appended element.');
            // remove id-ed element with using outerHTML
            $element->outerHTML = '<div></div>';
            $test = $this->doc->getElementById($TEST_ID);
            $this->assertEqualsData($test, null, 'should be null.');
        }, 'remove id attribute via outerHTML');
        $this->assertTest(function () use(&$gBody) {
            // setup fixtures.
            $TEST_ID = 'test12';
            $test = $this->doc->createElement('div');
            $test->id = $TEST_ID;
            $gBody->appendChild($test);
            // update id
            $UPDATED_ID = $TEST_ID . '-updated';
            $test->id = $UPDATED_ID;
            $e = $this->doc->getElementById($UPDATED_ID);
            $this->assertEqualsData($e, $test, 'should get the element with id.');
            $old = $this->doc->getElementById($TEST_ID);
            $this->assertEqualsData($old, null, "shouldn't get the element by the old id.");
            // remove id.
            $test->id = '';
            $e2 = $this->doc->getElementById($UPDATED_ID);
            $this->assertEqualsData($e2, null, 'should return null when the passed id is none in document.');
        }, 'update `id` attribute via element.id');
        $this->assertTest(function () use(&$gBody) {
            $TEST_ID = 'test13';
            $create_same_id_element = function ($order) use(&$TEST_ID) {
                $element = $this->doc->createElement('div');
                $element->setAttribute('id', $TEST_ID);
                $element->setAttribute('data-order', $order);
                // for debug
                return $element;
            };
            // create fixture
            $container = $this->doc->createElement('div');
            $container->setAttribute('id', $TEST_ID . '-fixture');
            $gBody->appendChild($container);
            $element1 = $create_same_id_element('1');
            $element2 = $create_same_id_element('2');
            $element3 = $create_same_id_element('3');
            $element4 = $create_same_id_element('4');
            // append element: 2 -> 4 -> 3 -> 1
            $container->appendChild($element2);
            $container->appendChild($element4);
            $container->insertBefore($element3, $element4);
            $container->insertBefore($element1, $element2);
            $test = $this->doc->getElementById($TEST_ID);
            $this->assertEqualsData($test, $element1, 'should return 1st element');
            $container->removeChild($element1);
            $test = $this->doc->getElementById($TEST_ID);
            $this->assertEqualsData($test, $element2, 'should return 2nd element');
            $container->removeChild($element2);
            $test = $this->doc->getElementById($TEST_ID);
            $this->assertEqualsData($test, $element3, 'should return 3rd element');
            $container->removeChild($element3);
            $test = $this->doc->getElementById($TEST_ID);
            $this->assertEqualsData($test, $element4, 'should return 4th element');
            $container->removeChild($element4);
        }, "where insertion order and tree order don't match");
        $this->assertTest(function () use(&$gBody) {
            $TEST_ID = 'test14';
            $a = $this->doc->createElement('a');
            $b = $this->doc->createElement('b');
            $a->appendChild($b);
            $b->id = $TEST_ID;
            $this->assertEqualsData($this->doc->getElementById($TEST_ID), null);
            $gBody->appendChild($a);
            $this->assertEqualsData($this->doc->getElementById($TEST_ID), $b);
        }, 'Inserting an id by inserting its parent node');
        $this->assertTest(function () {
            $TEST_ID = 'test15';
            $outer = $this->doc->getElementById('outer');
            $middle = $this->doc->getElementById('middle');
            $inner = $this->doc->getElementById('inner');
            $outer->removeChild($middle);
            $new_el = $this->doc->createElement('h1');
            $new_el->id = 'heading';
            $inner->appendChild($new_el);
            // the new element is not part of the document since
            // "middle" element was removed previously
            $this->assertEqualsData($this->doc->getElementById('heading'), null);
        }, 'Document.getElementById must not return nodes not present in document');
        // TODO:
        // id attribute in a namespace
        // TODO:
        // SVG + MathML elements with id attributes
    }
}
