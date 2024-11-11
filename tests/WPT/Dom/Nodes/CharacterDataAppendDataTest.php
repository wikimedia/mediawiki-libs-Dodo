<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom\Nodes;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Comment;
use Wikimedia\Dodo\Text;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/CharacterData-appendData.html.
class CharacterDataAppendDataTest extends WPTTestHarness
{
    public function helperTestNode($create, $type)
    {
        $this->assertTest(function () use (&$create) {
            $node = $create();
            $this->wptAssertEquals($node->data, 'test');
            $node->appendData('bar');
            $this->wptAssertEquals($node->data, 'testbar');
        }, $type . ".appendData('bar')");
        $this->assertTest(function () use (&$create) {
            $node = $create();
            $this->wptAssertEquals($node->data, 'test');
            $node->appendData('');
            $this->wptAssertEquals($node->data, 'test');
        }, $type . ".appendData('')");
        $this->assertTest(function () use (&$create) {
            $node = $create();
            $this->wptAssertEquals($node->data, 'test');
            $node->appendData(", append more 資料，測試資料");
            $this->wptAssertEquals($node->data, "test, append more 資料，測試資料");
            $this->wptAssertEquals(count($node), 25);
        }, $type . '.appendData(non-ASCII)');
        $this->assertTest(function () use (&$create) {
            $node = $create();
            $this->wptAssertEquals($node->data, 'test');
            $node->appendData(null);
            $this->wptAssertEquals($node->data, 'testnull');
        }, $type . '.appendData(null)');
        $this->assertTest(function () use (&$create) {
            $node = $create();
            $this->wptAssertEquals($node->data, 'test');
            $node->appendData(null);
            $this->wptAssertEquals($node->data, 'testundefined');
        }, $type . '.appendData(undefined)');
        $this->assertTest(function () use (&$create) {
            $node = $create();
            $this->wptAssertEquals($node->data, 'test');
            $node->appendData('', 'bar');
            $this->wptAssertEquals($node->data, 'test');
        }, $type . ".appendData('', 'bar')");
        $this->assertTest(function () use (&$create) {
            $node = $create();
            $this->wptAssertEquals($node->data, 'test');
            $this->wptAssertThrowsJs($this->type_error, function () use (&$node) {
                $node->appendData();
            });
            $this->wptAssertEquals($node->data, 'test');
        }, $type . '.appendData()');
    }
    public function testCharacterDataAppendData()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/CharacterData-appendData.html');
        $this->helperTestNode(function () {
            return $this->doc->createTextNode('test');
        }, 'Text');
        $this->helperTestNode(function () {
            return $this->doc->createComment('test');
        }, 'Comment');
    }
}
