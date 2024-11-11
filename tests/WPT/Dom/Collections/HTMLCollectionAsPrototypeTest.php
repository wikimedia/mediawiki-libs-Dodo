<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom\Collections;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/collections/HTMLCollection-as-prototype.html.
class HTMLCollectionAsPrototypeTest extends WPTTestHarness
{
    public function testHTMLCollectionAsPrototype()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/collections/HTMLCollection-as-prototype.html');
        $this->assertTest(function () {
            $obj = clone $this->doc->getElementsByTagName('script');
            $this->wptAssertThrowsJs($this->type_error, function () use (&$obj) {
                count($obj);
            });
        }, 'HTMLCollection as a prototype should not allow getting .length on the base object');
        $this->assertTest(function () {
            $element = $this->doc->createElement('p');
            $element->id = 'named';
            $this->doc->body->appendChild($element);
            $this->add_cleanup(function () use (&$element) {
                $element->remove();
            });
            $collection = $this->doc->getElementsByTagName('p');
            $this->wptAssertEquals($collection->named, $element);
            $object = clone $this->doc->getElementsByTagName('script');
            $this->wptAssertEquals($object->named, $element);
            $object->named = 'foo';
            $this->wptAssertEquals($object->named, 'foo');
            $this->wptAssertEquals($collection->named, $element);
        }, 'HTMLCollection as a prototype and setting own properties');
    }
}
