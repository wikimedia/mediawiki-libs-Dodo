<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom\Nodes;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Comment;
use Wikimedia\Dodo\Text;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/CharacterData-data.html.
class CharacterDataDataTest extends WPTTestHarness
{
    public function helperTestNode($create, $type)
    {
        $this->assertTest(function () use (&$create) {
            $node = $create();
            $this->wptAssertEquals($node->data, 'test');
            $this->wptAssertEquals(count($node), 4);
        }, $type . '.data initial value');
        $this->assertTest(function () use (&$create) {
            $node = $create();
            $this->wptAssertEquals($node->data, 'test');
            $node->data = null;
            $this->wptAssertEquals($node->data, '');
            $this->wptAssertEquals(count($node), 0);
        }, $type . '.data = null');
        $this->assertTest(function () use (&$create) {
            $node = $create();
            $this->wptAssertEquals($node->data, 'test');
            $node->data = null;
            $this->wptAssertEquals($node->data, NULL);
            $this->wptAssertEquals(count($node), 9);
        }, $type . '.data = undefined');
        $this->assertTest(function () use (&$create) {
            $node = $create();
            $this->wptAssertEquals($node->data, 'test');
            $node->data = 0;
            $this->wptAssertEquals($node->data, '0');
            $this->wptAssertEquals(count($node), 1);
        }, $type . '.data = 0');
        $this->assertTest(function () use (&$create) {
            $node = $create();
            $this->wptAssertEquals($node->data, 'test');
            $node->data = '';
            $this->wptAssertEquals($node->data, '');
            $this->wptAssertEquals(count($node), 0);
        }, $type . ".data = ''");
        $this->assertTest(function () use (&$create) {
            $node = $create();
            $this->wptAssertEquals($node->data, 'test');
            $node->data = '--';
            $this->wptAssertEquals($node->data, '--');
            $this->wptAssertEquals(count($node), 2);
        }, $type . ".data = '--'");
        $this->assertTest(function () use (&$create) {
            $node = $create();
            $this->wptAssertEquals($node->data, 'test');
            $node->data = "資料";
            $this->wptAssertEquals($node->data, "資料");
            $this->wptAssertEquals(count($node), 2);
        }, $type . ".data = '資料'");
        $this->assertTest(function () use (&$create) {
            $node = $create();
            $this->wptAssertEquals($node->data, 'test');
            $node->data = "🌠 test 🌠 TEST";
            $this->wptAssertEquals($node->data, "🌠 test 🌠 TEST");
            $this->wptAssertEquals(count($node), 15);
            // Counting UTF-16 code units
        }, $type . ".data = '🌠 test 🌠 TEST'");
    }
    public function testCharacterDataData()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/CharacterData-data.html');
        $this->helperTestNode(function () {
            return $this->doc->createTextNode('test');
        }, 'Text');
        $this->helperTestNode(function () {
            return $this->doc->createComment('test');
        }, 'Comment');
    }
}
