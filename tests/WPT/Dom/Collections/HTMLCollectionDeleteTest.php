<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom\Collections;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/collections/HTMLCollection-delete.html.
class HTMLCollectionDeleteTest extends WPTTestHarness
{
    public function testHTMLCollectionDelete()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/collections/HTMLCollection-delete.html');
        $c = null;
        $expected = null;
        // These might be cached anyway, so explicitly use a single object.
        // setup()
        $c = $this->doc->getElementsByTagName('i');
        $expected = $this->doc->getElementById('foo');
        $this->assertTest(function () use(&$c, &$expected) {
            $this->wptAssertEquals($c[0], $expected, 'before');
            unset($c[0]);
            $this->wptAssertEquals($c[0], $expected, 'after');
        }, 'Loose id');
        $this->assertTest(function () use(&$c, &$expected) {
            $this->wptAssertEquals($c[0], $expected, 'before');
            $this->wptAssertThrowsJs($this->type_error, function () use(&$c) {
                unset($c[0]);
            });
            $this->wptAssertEquals($c[0], $expected, 'after');
        }, 'Strict id');
        $this->assertTest(function () use(&$c, &$expected) {
            $this->wptAssertEquals($c->foo, $expected, 'before');
            unset($c->foo);
            $this->wptAssertEquals($c->foo, $expected, 'after');
        }, 'Loose name');
        $this->assertTest(function () use(&$c, &$expected) {
            $this->wptAssertEquals($c->foo, $expected, 'before');
            $this->wptAssertThrowsJs($this->type_error, function () use(&$c) {
                unset($c->foo);
            });
            $this->wptAssertEquals($c->foo, $expected, 'after');
        }, 'Strict name');
    }
}
