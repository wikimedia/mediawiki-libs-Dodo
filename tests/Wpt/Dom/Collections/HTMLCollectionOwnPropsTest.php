<?php 
namespace Wikimedia\Dodo\Tests\Wpt\Dom;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\Wpt\Harness\WptTestHarness;
// @see vendor/web-platform-tests/wpt/dom/collections/HTMLCollection-own-props.html.
class HTMLCollectionOwnPropsTest extends WptTestHarness
{
    public function append($t, $tag, $name = '')
    {
        $element = $this->doc->createElement($tag);
        if ($name) {
            $element->id = $name;
        }
        $this->doc->body->appendChild($element);
        $t->{$this}->addCleanup(function () use(&$element) {
            $element->remove();
        });
        return $element;
    }
    public function testHTMLCollectionOwnProps()
    {
        $this->source_file = 'vendor/web-platform-tests/wpt/dom/collections/HTMLCollection-own-props.html';
        $this->assertTest(function () {
            $name = 'named';
            $tag = 'a';
            $c = $this->doc->getElementsByTagName($tag);
            $element = $this->append($this, $tag, $name);
            $this->assertEqualsData($c[$name], $element);
            $c[$name] = 'foo';
            $this->assertEqualsData($c[$name], $element);
        }, 'Setting non-array index while named property exists (loose)');
        $this->assertTest(function () {
            $name = 'named';
            $tag = 'b';
            $c = $this->doc->getElementsByTagName($tag);
            $element = $this->append($this, $tag, $name);
            $this->assertEqualsData($c[$name], $element);
            $this->assertThrowsJsData($this->type_error, function () use(&$c, &$name) {
                $c[$name] = 'foo';
            });
            $this->assertEqualsData($c[$name], $element);
        }, 'Setting non-array index while named property exists (strict)');
        $this->assertTest(function () {
            $name = 'named';
            $tag = 'i';
            $c = $this->doc->getElementsByTagName($tag);
            $this->assertEqualsData($c[$name], null);
            $c[$name] = 'foo';
            $this->assertEqualsData($c[$name], 'foo');
            $element = $this->append($this, $tag, $name);
            $this->assertEqualsData($c[$name], 'foo');
            $this->assertEqualsData($c->namedItem($name), $element);
        }, "Setting non-array index while named property doesn't exist (loose)");
        $this->assertTest(function () {
            $name = 'named';
            $tag = 'p';
            $c = $this->doc->getElementsByTagName($tag);
            $this->assertEqualsData($c[$name], null);
            $c[$name] = 'foo';
            $this->assertEqualsData($c[$name], 'foo');
            $element = $this->append($this, $tag, $name);
            $this->assertEqualsData($c[$name], 'foo');
            $this->assertEqualsData($c->namedItem($name), $element);
        }, "Setting non-array index while named property doesn't exist (strict)");
        $this->assertTest(function () {
            $tag = 'q';
            $c = $this->doc->getElementsByTagName($tag);
            $element = $this->append($this, $tag);
            $this->assertEqualsData($c[0], $element);
            $c[0] = 'foo';
            $this->assertEqualsData($c[0], $element);
        }, 'Setting array index while indexed property exists (loose)');
        $this->assertTest(function () {
            $tag = 's';
            $c = $this->doc->getElementsByTagName($tag);
            $element = $this->append($this, $tag);
            $this->assertEqualsData($c[0], $element);
            $this->assertThrowsJsData($this->type_error, function () use(&$c) {
                $c[0] = 'foo';
            });
            $this->assertEqualsData($c[0], $element);
        }, 'Setting array index while indexed property exists (strict)');
        $this->assertTest(function () {
            $tag = 'u';
            $c = $this->doc->getElementsByTagName($tag);
            $this->assertEqualsData($c[0], null);
            $c[0] = 'foo';
            $this->assertEqualsData($c[0], null);
            $element = $this->append($this, $tag);
            $this->assertEqualsData($c[0], $element);
        }, "Setting array index while indexed property doesn't exist (loose)");
        $this->assertTest(function () {
            $tag = 'u';
            $c = $this->doc->getElementsByTagName($tag);
            $this->assertEqualsData($c[0], null);
            $this->assertThrowsJsData($this->type_error, function () use(&$c) {
                $c[0] = 'foo';
            });
            $this->assertEqualsData($c[0], null);
            $element = $this->append($this, $tag);
            $this->assertEqualsData($c[0], $element);
        }, "Setting array index while indexed property doesn't exist (strict)");
    }
}
