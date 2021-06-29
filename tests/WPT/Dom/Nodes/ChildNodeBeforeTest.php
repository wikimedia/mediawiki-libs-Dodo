<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Comment;
use Wikimedia\Dodo\Text;
use Wikimedia\Dodo\Tests\WPT\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/ChildNode-before.html.
class ChildNodeBeforeTest extends WPTTestHarness
{
    public function testBefore($child, $nodeName, $innerHTML)
    {
        $this->assertTest(function () use(&$child, &$innerHTML) {
            $parent = $this->doc->createElement('div');
            $parent->appendChild($child);
            $child->before();
            $this->assertEqualsData($parent->innerHTML, $innerHTML);
        }, $nodeName . '.before() without any argument.');
        $this->assertTest(function () use(&$child, &$innerHTML) {
            $parent = $this->doc->createElement('div');
            $parent->appendChild($child);
            $child->before(null);
            $expected = 'null' . $innerHTML;
            $this->assertEqualsData($parent->innerHTML, $expected);
        }, $nodeName . '.before() with null as an argument.');
        $this->assertTest(function () use(&$child, &$innerHTML) {
            $parent = $this->doc->createElement('div');
            $parent->appendChild($child);
            $child->before(null);
            $expected = NULL . $innerHTML;
            $this->assertEqualsData($parent->innerHTML, $expected);
        }, $nodeName . '.before() with undefined as an argument.');
        $this->assertTest(function () use(&$child) {
            $parent = $this->doc->createElement('div');
            $parent->appendChild($child);
            $child->before('');
            $this->assertEqualsData($parent->firstChild->data, '');
        }, $nodeName . '.before() with the empty string as an argument.');
        $this->assertTest(function () use(&$child, &$innerHTML) {
            $parent = $this->doc->createElement('div');
            $parent->appendChild($child);
            $child->before('text');
            $expected = 'text' . $innerHTML;
            $this->assertEqualsData($parent->innerHTML, $expected);
        }, $nodeName . '.before() with only text as an argument.');
        $this->assertTest(function () use(&$child, &$innerHTML) {
            $parent = $this->doc->createElement('div');
            $x = $this->doc->createElement('x');
            $parent->appendChild($child);
            $child->before($x);
            $expected = '<x></x>' . $innerHTML;
            $this->assertEqualsData($parent->innerHTML, $expected);
        }, $nodeName . '.before() with only one element as an argument.');
        $this->assertTest(function () use(&$child, &$innerHTML) {
            $parent = $this->doc->createElement('div');
            $x = $this->doc->createElement('x');
            $parent->appendChild($child);
            $child->before($x, 'text');
            $expected = '<x></x>text' . $innerHTML;
            $this->assertEqualsData($parent->innerHTML, $expected);
        }, $nodeName . '.before() with one element and text as arguments.');
        $this->assertTest(function () use(&$child, &$innerHTML) {
            $parent = $this->doc->createElement('div');
            $parent->appendChild($child);
            $child->before('text', $child);
            $expected = 'text' . $innerHTML;
            $this->assertEqualsData($parent->innerHTML, $expected);
        }, $nodeName . '.before() with context object itself as the argument.');
        $this->assertTest(function () use(&$child, &$innerHTML) {
            $parent = $this->doc->createElement('div');
            $x = $this->doc->createElement('x');
            $parent->appendChild($child);
            $parent->appendChild($x);
            $child->before($x, $child);
            $expected = '<x></x>' . $innerHTML;
            $this->assertEqualsData($parent->innerHTML, $expected);
        }, $nodeName . '.before() with context object itself and node as the arguments, switching positions.');
        $this->assertTest(function () use(&$child, &$innerHTML) {
            $parent = $this->doc->createElement('div');
            $x = $this->doc->createElement('x');
            $y = $this->doc->createElement('y');
            $z = $this->doc->createElement('z');
            $parent->appendChild($y);
            $parent->appendChild($child);
            $parent->appendChild($x);
            $child->before($x, $y, $z);
            $expected = '<x></x><y></y><z></z>' . $innerHTML;
            $this->assertEqualsData($parent->innerHTML, $expected);
        }, $nodeName . '.before() with all siblings of child as arguments.');
        $this->assertTest(function () use(&$child, &$innerHTML) {
            $parent = $this->doc->createElement('div');
            $x = $this->doc->createElement('x');
            $y = $this->doc->createElement('y');
            $z = $this->doc->createElement('z');
            $parent->appendChild($x);
            $parent->appendChild($y);
            $parent->appendChild($z);
            $parent->appendChild($child);
            $child->before($y, $z);
            $expected = '<x></x><y></y><z></z>' . $innerHTML;
            $this->assertEqualsData($parent->innerHTML, $expected);
        }, $nodeName . '.before() with some siblings of child as arguments; no changes in tree; viable sibling is first child.');
        $this->assertTest(function () use(&$child, &$innerHTML) {
            $parent = $this->doc->createElement('div');
            $v = $this->doc->createElement('v');
            $x = $this->doc->createElement('x');
            $y = $this->doc->createElement('y');
            $z = $this->doc->createElement('z');
            $parent->appendChild($v);
            $parent->appendChild($x);
            $parent->appendChild($y);
            $parent->appendChild($z);
            $parent->appendChild($child);
            $child->before($y, $z);
            $expected = '<v></v><x></x><y></y><z></z>' . $innerHTML;
            $this->assertEqualsData($parent->innerHTML, $expected);
        }, $nodeName . '.before() with some siblings of child as arguments; no changes in tree.');
        $this->assertTest(function () use(&$child, &$innerHTML) {
            $parent = $this->doc->createElement('div');
            $x = $this->doc->createElement('x');
            $y = $this->doc->createElement('y');
            $parent->appendChild($x);
            $parent->appendChild($y);
            $parent->appendChild($child);
            $child->before($y, $x);
            $expected = '<y></y><x></x>' . $innerHTML;
            $this->assertEqualsData($parent->innerHTML, $expected);
        }, $nodeName . '.before() when pre-insert behaves like prepend.');
        $this->assertTest(function () use(&$child, &$innerHTML) {
            $parent = $this->doc->createElement('div');
            $x = $this->doc->createElement('x');
            $parent->appendChild($x);
            $parent->appendChild($this->doc->createTextNode('1'));
            $y = $this->doc->createElement('y');
            $parent->appendChild($y);
            $parent->appendChild($child);
            $child->before($x, '2');
            $expected = '1<y></y><x></x>2' . $innerHTML;
            $this->assertEqualsData($parent->innerHTML, $expected);
        }, $nodeName . '.before() with one sibling of child and text as arguments.');
        $this->assertTest(function () {
            $x = $this->doc->createElement('x');
            $y = $this->doc->createElement('y');
            $x->before($y);
            $this->assertEqualsData($x->getPreviousSibling(), null);
        }, $nodeName . '.before() on a child without any parent.');
    }
    public function testChildNodeBefore()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/ChildNode-before.html');
        $this->testBefore($this->doc->createComment('test'), 'Comment', '<!--test-->');
        $this->testBefore($this->doc->createElement('test'), 'Element', '<test></test>');
        $this->testBefore($this->doc->createTextNode('test'), 'Text', 'test');
    }
}
