<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Comment;
use Wikimedia\Dodo\Text;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/CharacterData-deleteData.html.
class CharacterDataDeleteDataTest extends WPTTestHarness
{
    public function testNode($create, $type)
    {
        $this->assertTest(function () use(&$create) {
            $node = $create();
            $this->assertEqualsData($node->data, 'test');
            $this->assertThrowsDomData('INDEX_SIZE_ERR', function () use(&$node) {
                $node->deleteData(5, 10);
            });
            $this->assertThrowsDomData('INDEX_SIZE_ERR', function () use(&$node) {
                $node->deleteData(5, 0);
            });
            $this->assertThrowsDomData('INDEX_SIZE_ERR', function () use(&$node) {
                $node->deleteData(-1, 10);
            });
            $this->assertThrowsDomData('INDEX_SIZE_ERR', function () use(&$node) {
                $node->deleteData(-1, 0);
            });
        }, $type . '.deleteData() out of bounds');
        $this->assertTest(function () use(&$create) {
            $node = $create();
            $this->assertEqualsData($node->data, 'test');
            $node->deleteData(0, 2);
            $this->assertEqualsData($node->data, 'st');
        }, $type . '.deleteData() at the start');
        $this->assertTest(function () use(&$create) {
            $node = $create();
            $this->assertEqualsData($node->data, 'test');
            $node->deleteData(2, 10);
            $this->assertEqualsData($node->data, 'te');
        }, $type . '.deleteData() at the end');
        $this->assertTest(function () use(&$create) {
            $node = $create();
            $this->assertEqualsData($node->data, 'test');
            $node->deleteData(1, 1);
            $this->assertEqualsData($node->data, 'tst');
        }, $type . '.deleteData() in the middle');
        $this->assertTest(function () use(&$create) {
            $node = $create();
            $this->assertEqualsData($node->data, 'test');
            $node->deleteData(2, 0);
            $this->assertEqualsData($node->data, 'test');
            $node->deleteData(0, 0);
            $this->assertEqualsData($node->data, 'test');
        }, $type . '.deleteData() with zero count');
        $this->assertTest(function () use(&$create) {
            $node = $create();
            $this->assertEqualsData($node->data, 'test');
            $node->deleteData(2, -1);
            $this->assertEqualsData($node->data, 'te');
        }, $type . '.deleteData() with small negative count');
        $this->assertTest(function () use(&$create) {
            $node = $create();
            $this->assertEqualsData($node->data, 'test');
            $node->deleteData(1, -0x100000000 + 2);
            $this->assertEqualsData($node->data, 'tt');
        }, $type . '.deleteData() with large negative count');
        $this->assertTest(function () use(&$create) {
            $node = $create();
            $node->data = "This is the character data test, append more 資料，更多測試資料";
            $node->deleteData(40, 5);
            $this->assertEqualsData($node->data, "This is the character data test, append 資料，更多測試資料");
            $node->deleteData(45, 2);
            $this->assertEqualsData($node->data, "This is the character data test, append 資料，更多資料");
        }, $type . '.deleteData() with non-ascii data');
        $this->assertTest(function () use(&$create) {
            $node = $create();
            $this->assertEqualsData($node->data, 'test');
            $node->data = "🌠 test 🌠 TEST";
            $node->deleteData(5, 8);
            // Counting UTF-16 code units
            $this->assertEqualsData($node->data, "🌠 teST");
        }, $type . '.deleteData() with non-BMP data');
    }
    public function testCharacterDataDeleteData()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/CharacterData-deleteData.html');
        $this->testNode(function () {
            return $this->doc->createTextNode('test');
        }, 'Text');
        $this->testNode(function () {
            return $this->doc->createComment('test');
        }, 'Comment');
    }
}
