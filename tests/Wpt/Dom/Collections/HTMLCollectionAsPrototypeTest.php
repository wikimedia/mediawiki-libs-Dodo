<?php 
namespace Wikimedia\Dodo\Tests\Wpt\Dom;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\Wpt\Harness\WptTestHarness;
// @see vendor/web-platform-tests/wpt/dom/collections/HTMLCollection-as-prototype.html.
class HTMLCollectionAsPrototypeTest extends WptTestHarness
{
    public function testHTMLCollectionAsPrototype()
    {
        $this->source_file = 'vendor/web-platform-tests/wpt/dom/collections/HTMLCollection-as-prototype.html';
        $this->assertTest(function () {
            $obj = clone $this->doc->getElementsByTagName('script');
            $this->assertThrowsJsData($this->type_error, function () use(&$obj) {
                count($obj);
            });
        }, 'HTMLCollection as a prototype should not allow getting .length on the base object');
        $this->assertTest(function () {
            $element = $this->doc->createElement('p');
            $element->id = 'named';
            $this->doc->body->appendChild($element);
            $this->{$this}->addCleanup(function () use(&$element) {
                $element->remove();
            });
            $collection = $this->doc->getElementsByTagName('p');
            $this->assertEqualsData($collection->named, $element);
            $object = clone $this->doc->getElementsByTagName('script');
            $this->assertEqualsData($object->named, $element);
            $object->named = 'foo';
            $this->assertEqualsData($object->named, 'foo');
            $this->assertEqualsData($collection->named, $element);
        }, 'HTMLCollection as a prototype and setting own properties');
    }
}
