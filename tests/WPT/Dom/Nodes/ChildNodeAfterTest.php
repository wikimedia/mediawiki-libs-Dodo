<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom\Nodes;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Comment;
use Wikimedia\Dodo\Text;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/ChildNode-after.html.
class ChildNodeAfterTest extends WPTTestHarness
{
    public function helperTestAfter($child, $nodeName, $innerHTML)
    {
        $this->assertTest(function () use (&$child, &$innerHTML) {
            $parent = $this->doc->createElement('div');
            $parent->appendChild($child);
            $child->after();
            $this->wptAssertEquals($parent->innerHTML, $innerHTML);
        }, $nodeName . '.after() without any argument.');
        $this->assertTest(function () use (&$child, &$innerHTML) {
            $parent = $this->doc->createElement('div');
            $parent->appendChild($child);
            $child->after(null);
            $expected = $innerHTML . 'null';
            $this->wptAssertEquals($parent->innerHTML, $expected);
        }, $nodeName . '.after() with null as an argument.');
        $this->assertTest(function () use (&$child, &$innerHTML) {
            $parent = $this->doc->createElement('div');
            $parent->appendChild($child);
            $child->after(null);
            $expected = $innerHTML . NULL;
            $this->wptAssertEquals($parent->innerHTML, $expected);
        }, $nodeName . '.after() with undefined as an argument.');
        $this->assertTest(function () use (&$child) {
            $parent = $this->doc->createElement('div');
            $parent->appendChild($child);
            $child->after('');
            $this->wptAssertEquals($parent->lastChild->data, '');
        }, $nodeName . '.after() with the empty string as an argument.');
        $this->assertTest(function () use (&$child, &$innerHTML) {
            $parent = $this->doc->createElement('div');
            $parent->appendChild($child);
            $child->after('text');
            $expected = $innerHTML . 'text';
            $this->wptAssertEquals($parent->innerHTML, $expected);
        }, $nodeName . '.after() with only text as an argument.');
        $this->assertTest(function () use (&$child, &$innerHTML) {
            $parent = $this->doc->createElement('div');
            $x = $this->doc->createElement('x');
            $parent->appendChild($child);
            $child->after($x);
            $expected = $innerHTML . '<x></x>';
            $this->wptAssertEquals($parent->innerHTML, $expected);
        }, $nodeName . '.after() with only one element as an argument.');
        $this->assertTest(function () use (&$child, &$innerHTML) {
            $parent = $this->doc->createElement('div');
            $x = $this->doc->createElement('x');
            $parent->appendChild($child);
            $child->after($x, 'text');
            $expected = $innerHTML . '<x></x>text';
            $this->wptAssertEquals($parent->innerHTML, $expected);
        }, $nodeName . '.after() with one element and text as arguments.');
        $this->assertTest(function () use (&$child, &$innerHTML) {
            $parent = $this->doc->createElement('div');
            $parent->appendChild($child);
            $child->after('text', $child);
            $expected = 'text' . $innerHTML;
            $this->wptAssertEquals($parent->innerHTML, $expected);
        }, $nodeName . '.after() with context object itself as the argument.');
        $this->assertTest(function () use (&$child, &$innerHTML) {
            $parent = $this->doc->createElement('div');
            $x = $this->doc->createElement('x');
            $parent->appendChild($x);
            $parent->appendChild($child);
            $child->after($child, $x);
            $expected = $innerHTML . '<x></x>';
            $this->wptAssertEquals($parent->innerHTML, $expected);
        }, $nodeName . '.after() with context object itself and node as the arguments, switching positions.');
        $this->assertTest(function () use (&$child, &$innerHTML) {
            $parent = $this->doc->createElement('div');
            $x = $this->doc->createElement('x');
            $y = $this->doc->createElement('y');
            $z = $this->doc->createElement('z');
            $parent->appendChild($y);
            $parent->appendChild($child);
            $parent->appendChild($x);
            $child->after($x, $y, $z);
            $expected = $innerHTML . '<x></x><y></y><z></z>';
            $this->wptAssertEquals($parent->innerHTML, $expected);
        }, $nodeName . '.after() with all siblings of child as arguments.');
        $this->assertTest(function () use (&$child, &$innerHTML) {
            $parent = $this->doc->createElement('div');
            $x = $this->doc->createElement('x');
            $y = $this->doc->createElement('y');
            $z = $this->doc->createElement('z');
            $parent->appendChild($child);
            $parent->appendChild($x);
            $parent->appendChild($y);
            $parent->appendChild($z);
            $child->after($x, $y);
            $expected = $innerHTML . '<x></x><y></y><z></z>';
            $this->wptAssertEquals($parent->innerHTML, $expected);
        }, $nodeName . '.before() with some siblings of child as arguments; no changes in tree; viable sibling is first child.');
        $this->assertTest(function () use (&$child, &$innerHTML) {
            $parent = $this->doc->createElement('div');
            $v = $this->doc->createElement('v');
            $x = $this->doc->createElement('x');
            $y = $this->doc->createElement('y');
            $z = $this->doc->createElement('z');
            $parent->appendChild($child);
            $parent->appendChild($v);
            $parent->appendChild($x);
            $parent->appendChild($y);
            $parent->appendChild($z);
            $child->after($v, $x);
            $expected = $innerHTML . '<v></v><x></x><y></y><z></z>';
            $this->wptAssertEquals($parent->innerHTML, $expected);
        }, $nodeName . '.after() with some siblings of child as arguments; no changes in tree.');
        $this->assertTest(function () use (&$child, &$innerHTML) {
            $parent = $this->doc->createElement('div');
            $x = $this->doc->createElement('x');
            $y = $this->doc->createElement('y');
            $parent->appendChild($child);
            $parent->appendChild($x);
            $parent->appendChild($y);
            $child->after($y, $x);
            $expected = $innerHTML . '<y></y><x></x>';
            $this->wptAssertEquals($parent->innerHTML, $expected);
        }, $nodeName . '.after() when pre-insert behaves like append.');
        $this->assertTest(function () use (&$child, &$innerHTML) {
            $parent = $this->doc->createElement('div');
            $x = $this->doc->createElement('x');
            $y = $this->doc->createElement('y');
            $parent->appendChild($child);
            $parent->appendChild($x);
            $parent->appendChild($this->doc->createTextNode('1'));
            $parent->appendChild($y);
            $child->after($x, '2');
            $expected = $innerHTML . '<x></x>21<y></y>';
            $this->wptAssertEquals($parent->innerHTML, $expected);
        }, $nodeName . '.after() with one sibling of child and text as arguments.');
        $this->assertTest(function () {
            $x = $this->doc->createElement('x');
            $y = $this->doc->createElement('y');
            $x->after($y);
            $this->wptAssertEquals($x->nextSibling, null);
        }, $nodeName . '.after() on a child without any parent.');
    }
    public function testChildNodeAfter()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/ChildNode-after.html');
        $this->helperTestAfter($this->doc->createComment('test'), 'Comment', '<!--test-->');
        $this->helperTestAfter($this->doc->createElement('test'), 'Element', '<test></test>');
        $this->helperTestAfter($this->doc->createTextNode('test'), 'Text', 'test');
    }
}
