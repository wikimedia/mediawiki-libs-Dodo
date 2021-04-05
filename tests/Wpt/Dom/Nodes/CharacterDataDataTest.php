<?php 
namespace Wikimedia\Dodo\Tests\Wpt\Dom;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Comment;
use Wikimedia\Dodo\Text;
use Wikimedia\Dodo\Tests\Wpt\Harness\WptTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/CharacterData-data.html.
class CharacterDataDataTest extends WptTestHarness
{
    public function testNode($create, $type)
    {
        $this->assertTest(function () use(&$create) {
            $node = $create();
            $this->assertEqualsData($node->data, 'test');
            $this->assertEqualsData(count($node), 4);
        }, $type . '.data initial value');
        $this->assertTest(function () use(&$create) {
            $node = $create();
            $this->assertEqualsData($node->data, 'test');
            $node->data = null;
            $this->assertEqualsData($node->data, '');
            $this->assertEqualsData(count($node), 0);
        }, $type . '.data = null');
        $this->assertTest(function () use(&$create) {
            $node = $create();
            $this->assertEqualsData($node->data, 'test');
            $node->data = null;
            $this->assertEqualsData($node->data, NULL);
            $this->assertEqualsData(count($node), 9);
        }, $type . '.data = undefined');
        $this->assertTest(function () use(&$create) {
            $node = $create();
            $this->assertEqualsData($node->data, 'test');
            $node->data = 0;
            $this->assertEqualsData($node->data, '0');
            $this->assertEqualsData(count($node), 1);
        }, $type . '.data = 0');
        $this->assertTest(function () use(&$create) {
            $node = $create();
            $this->assertEqualsData($node->data, 'test');
            $node->data = '';
            $this->assertEqualsData($node->data, '');
            $this->assertEqualsData(count($node), 0);
        }, $type . ".data = ''");
        $this->assertTest(function () use(&$create) {
            $node = $create();
            $this->assertEqualsData($node->data, 'test');
            $node->data = '--';
            $this->assertEqualsData($node->data, '--');
            $this->assertEqualsData(count($node), 2);
        }, $type . ".data = '--'");
        $this->assertTest(function () use(&$create) {
            $node = $create();
            $this->assertEqualsData($node->data, 'test');
            $node->data = "資料";
            $this->assertEqualsData($node->data, "資料");
            $this->assertEqualsData(count($node), 2);
        }, $type . ".data = '資料'");
        $this->assertTest(function () use(&$create) {
            $node = $create();
            $this->assertEqualsData($node->data, 'test');
            $node->data = "🌠 test 🌠 TEST";
            $this->assertEqualsData($node->data, "🌠 test 🌠 TEST");
            $this->assertEqualsData(count($node), 15);
            // Counting UTF-16 code units
        }, $type . ".data = '🌠 test 🌠 TEST'");
    }
    public function testCharacterDataData()
    {
        $this->source_file = 'vendor/web-platform-tests/wpt/dom/nodes/CharacterData-data.html';
        $this->testNode(function () {
            return $this->doc->createTextNode('test');
        }, 'Text');
        $this->testNode(function () {
            return $this->doc->createComment('test');
        }, 'Comment');
    }
}
