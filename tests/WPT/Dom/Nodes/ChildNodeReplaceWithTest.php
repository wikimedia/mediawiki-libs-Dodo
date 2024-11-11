<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom\Nodes;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Comment;
use Wikimedia\Dodo\Text;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/ChildNode-replaceWith.html.
class ChildNodeReplaceWithTest extends WPTTestHarness
{
    public function helperTestReplaceWith($child, $nodeName, $innerHTML)
    {
        $this->assertTest(function () use (&$child) {
            $parent = $this->doc->createElement('div');
            $parent->appendChild($child);
            $child->replaceWith();
            $this->wptAssertEquals($parent->innerHTML, '');
        }, $nodeName . '.replaceWith() without any argument.');
        $this->assertTest(function () use (&$child) {
            $parent = $this->doc->createElement('div');
            $parent->appendChild($child);
            $child->replaceWith(null);
            $this->wptAssertEquals($parent->innerHTML, 'null');
        }, $nodeName . '.replaceWith() with null as an argument.');
        $this->assertTest(function () use (&$child) {
            $parent = $this->doc->createElement('div');
            $parent->appendChild($child);
            $child->replaceWith(null);
            $this->wptAssertEquals($parent->innerHTML, NULL);
        }, $nodeName . '.replaceWith() with undefined as an argument.');
        $this->assertTest(function () use (&$child) {
            $parent = $this->doc->createElement('div');
            $parent->appendChild($child);
            $child->replaceWith('');
            $this->wptAssertEquals($parent->innerHTML, '');
        }, $nodeName . '.replaceWith() with empty string as an argument.');
        $this->assertTest(function () use (&$child) {
            $parent = $this->doc->createElement('div');
            $parent->appendChild($child);
            $child->replaceWith('text');
            $this->wptAssertEquals($parent->innerHTML, 'text');
        }, $nodeName . '.replaceWith() with only text as an argument.');
        $this->assertTest(function () use (&$child) {
            $parent = $this->doc->createElement('div');
            $x = $this->doc->createElement('x');
            $parent->appendChild($child);
            $child->replaceWith($x);
            $this->wptAssertEquals($parent->innerHTML, '<x></x>');
        }, $nodeName . '.replaceWith() with only one element as an argument.');
        $this->assertTest(function () use (&$child) {
            $parent = $this->doc->createElement('div');
            $x = $this->doc->createElement('x');
            $y = $this->doc->createElement('y');
            $z = $this->doc->createElement('z');
            $parent->appendChild($y);
            $parent->appendChild($child);
            $parent->appendChild($x);
            $child->replaceWith($x, $y, $z);
            $this->wptAssertEquals($parent->innerHTML, '<x></x><y></y><z></z>');
        }, $nodeName . '.replaceWith() with sibling of child as arguments.');
        $this->assertTest(function () use (&$child) {
            $parent = $this->doc->createElement('div');
            $x = $this->doc->createElement('x');
            $parent->appendChild($child);
            $parent->appendChild($x);
            $parent->appendChild($this->doc->createTextNode('1'));
            $child->replaceWith($x, '2');
            $this->wptAssertEquals($parent->innerHTML, '<x></x>21');
        }, $nodeName . '.replaceWith() with one sibling of child and text as arguments.');
        $this->assertTest(function () use (&$child, &$innerHTML) {
            $parent = $this->doc->createElement('div');
            $x = $this->doc->createElement('x');
            $parent->appendChild($child);
            $parent->appendChild($x);
            $parent->appendChild($this->doc->createTextNode('text'));
            $child->replaceWith($x, $child);
            $this->wptAssertEquals($parent->innerHTML, '<x></x>' . $innerHTML . 'text');
        }, $nodeName . '.replaceWith() with one sibling of child and child itself as arguments.');
        $this->assertTest(function () use (&$child) {
            $parent = $this->doc->createElement('div');
            $x = $this->doc->createElement('x');
            $parent->appendChild($child);
            $child->replaceWith($x, 'text');
            $this->wptAssertEquals($parent->innerHTML, '<x></x>text');
        }, $nodeName . '.replaceWith() with one element and text as arguments.');
        $this->assertTest(function () use (&$child) {
            $parent = $this->doc->createElement('div');
            $x = $this->doc->createElement('x');
            $y = $this->doc->createElement('y');
            $parent->appendChild($x);
            $parent->appendChild($y);
            $child->replaceWith($x, $y);
            $this->wptAssertEquals($parent->innerHTML, '<x></x><y></y>');
        }, $nodeName . '.replaceWith() on a parentless child with two elements as arguments.');
    }
    public function testChildNodeReplaceWith()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/ChildNode-replaceWith.html');
        $this->helperTestReplaceWith($this->doc->createComment('test'), 'Comment', '<!--test-->');
        $this->helperTestReplaceWith($this->doc->createElement('test'), 'Element', '<test></test>');
        $this->helperTestReplaceWith($this->doc->createTextNode('test'), 'Text', 'test');
    }
}
