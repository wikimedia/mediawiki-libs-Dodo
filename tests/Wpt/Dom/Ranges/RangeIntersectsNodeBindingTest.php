<?php 
namespace Wikimedia\Dodo\Tests\Wpt\Dom;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Tests\Wpt\Harness\WptTestHarness;
// @see vendor/web-platform-tests/wpt/dom/ranges/Range-intersectsNode-binding.html.
class RangeIntersectsNodeBindingTest extends WptTestHarness
{
    public function testRangeIntersectsNodeBinding()
    {
        $this->doc = $this->loadWptHtmlFile('vendor/web-platform-tests/wpt/dom/ranges/Range-intersectsNode-binding.html');
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
