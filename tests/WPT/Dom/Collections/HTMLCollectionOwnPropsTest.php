<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom\Collections;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/collections/HTMLCollection-own-props.html.
class HTMLCollectionOwnPropsTest extends WPTTestHarness
{
    public function append($t, $tag, $name = '')
    {
        $element = $this->doc->createElement($tag);
        if ($name) {
            $element->id = $name;
        }
        $this->doc->body->appendChild($element);
        $t->add_cleanup(function () use(&$element) {
            $element->remove();
        });
        return $element;
    }
    public function testHTMLCollectionOwnProps()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/collections/HTMLCollection-own-props.html');
        $this->assertTest(function () {
            $name = 'named';
            $tag = 'a';
            $c = $this->doc->getElementsByTagName($tag);
            $element = $this->append($this, $tag, $name);
            $this->wptAssertEquals($c[$name], $element);
            $c[$name] = 'foo';
            $this->wptAssertEquals($c[$name], $element);
        }, 'Setting non-array index while named property exists (loose)');
        $this->assertTest(function () {
            $name = 'named';
            $tag = 'b';
            $c = $this->doc->getElementsByTagName($tag);
            $element = $this->append($this, $tag, $name);
            $this->wptAssertEquals($c[$name], $element);
            $this->wptAssertThrowsJs($this->type_error, function () use(&$c, &$name) {
                $c[$name] = 'foo';
            });
            $this->wptAssertEquals($c[$name], $element);
        }, 'Setting non-array index while named property exists (strict)');
        $this->assertTest(function () {
            $name = 'named';
            $tag = 'i';
            $c = $this->doc->getElementsByTagName($tag);
            $this->wptAssertEquals($c[$name], null);
            $c[$name] = 'foo';
            $this->wptAssertEquals($c[$name], 'foo');
            $element = $this->append($this, $tag, $name);
            $this->wptAssertEquals($c[$name], 'foo');
            $this->wptAssertEquals($c->namedItem($name), $element);
        }, "Setting non-array index while named property doesn't exist (loose)");
        $this->assertTest(function () {
            $name = 'named';
            $tag = 'p';
            $c = $this->doc->getElementsByTagName($tag);
            $this->wptAssertEquals($c[$name], null);
            $c[$name] = 'foo';
            $this->wptAssertEquals($c[$name], 'foo');
            $element = $this->append($this, $tag, $name);
            $this->wptAssertEquals($c[$name], 'foo');
            $this->wptAssertEquals($c->namedItem($name), $element);
        }, "Setting non-array index while named property doesn't exist (strict)");
        $this->assertTest(function () {
            $tag = 'q';
            $c = $this->doc->getElementsByTagName($tag);
            $element = $this->append($this, $tag);
            $this->wptAssertEquals($c[0], $element);
            $c[0] = 'foo';
            $this->wptAssertEquals($c[0], $element);
        }, 'Setting array index while indexed property exists (loose)');
        $this->assertTest(function () {
            $tag = 's';
            $c = $this->doc->getElementsByTagName($tag);
            $element = $this->append($this, $tag);
            $this->wptAssertEquals($c[0], $element);
            $this->wptAssertThrowsJs($this->type_error, function () use(&$c) {
                $c[0] = 'foo';
            });
            $this->wptAssertEquals($c[0], $element);
        }, 'Setting array index while indexed property exists (strict)');
        $this->assertTest(function () {
            $tag = 'u';
            $c = $this->doc->getElementsByTagName($tag);
            $this->wptAssertEquals($c[0], null);
            $c[0] = 'foo';
            $this->wptAssertEquals($c[0], null);
            $element = $this->append($this, $tag);
            $this->wptAssertEquals($c[0], $element);
        }, "Setting array index while indexed property doesn't exist (loose)");
        $this->assertTest(function () {
            $tag = 'u';
            $c = $this->doc->getElementsByTagName($tag);
            $this->wptAssertEquals($c[0], null);
            $this->wptAssertThrowsJs($this->type_error, function () use(&$c) {
                $c[0] = 'foo';
            });
            $this->wptAssertEquals($c[0], null);
            $element = $this->append($this, $tag);
            $this->wptAssertEquals($c[0], $element);
        }, "Setting array index while indexed property doesn't exist (strict)");
    }
}
