<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Range;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/ranges/Range-intersectsNode-binding.html.
class RangeIntersectsNodeBindingTest extends WPTTestHarness
{
    public function testRangeIntersectsNodeBinding()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/ranges/Range-intersectsNode-binding.html');
        $this->assertTest(function () {
            $r = $this->doc->createRange();
            $this->assertThrowsJsData($this->type_error, function () use(&$r) {
                $r->intersectsNode();
            });
            $this->assertThrowsJsData($this->type_error, function () use(&$r) {
                $r->intersectsNode(null);
            });
            $this->assertThrowsJsData($this->type_error, function () use(&$r) {
                $r->intersectsNode(null);
            });
            $this->assertThrowsJsData($this->type_error, function () use(&$r) {
                $r->intersectsNode(42);
            });
            $this->assertThrowsJsData($this->type_error, function () use(&$r) {
                $r->intersectsNode('foo');
            });
            $this->assertThrowsJsData($this->type_error, function () use(&$r) {
                $r->intersectsNode([]);
            });
            $r->detach();
            $this->assertThrowsJsData($this->type_error, function () use(&$r) {
                $r->intersectsNode();
            });
            $this->assertThrowsJsData($this->type_error, function () use(&$r) {
                $r->intersectsNode(null);
            });
            $this->assertThrowsJsData($this->type_error, function () use(&$r) {
                $r->intersectsNode(null);
            });
            $this->assertThrowsJsData($this->type_error, function () use(&$r) {
                $r->intersectsNode(42);
            });
            $this->assertThrowsJsData($this->type_error, function () use(&$r) {
                $r->intersectsNode('foo');
            });
            $this->assertThrowsJsData($this->type_error, function () use(&$r) {
                $r->intersectsNode([]);
            });
        }, 'Calling intersectsNode without an argument or with an invalid argument should throw a TypeError.');
    }
}
