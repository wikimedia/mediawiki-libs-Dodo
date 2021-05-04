<?php 
namespace Wikimedia\Dodo\Tests\Wpt\Dom;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Comment;
use Wikimedia\Dodo\Text;
use Wikimedia\Dodo\Tests\Wpt\Harness\WptTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/CharacterData-insertData.html.
class CharacterDataInsertDataTest extends WptTestHarness
{
    public function testNode($create, $type)
    {
        $this->assertTest(function () use(&$create) {
            $node = $create();
            $this->assertEqualsData($node->data, 'test');
            $this->assertThrowsDomData('INDEX_SIZE_ERR', function () use(&$node) {
                $node->insertData(5, 'x');
            });
            $this->assertThrowsDomData('INDEX_SIZE_ERR', function () use(&$node) {
                $node->insertData(5, '');
            });
        }, $type . '.insertData() out of bounds');
        $this->assertTest(function () use(&$create) {
            $node = $create();
            $this->assertEqualsData($node->data, 'test');
            $this->assertThrowsDomData('INDEX_SIZE_ERR', function () use(&$node) {
                $node->insertData(-1, 'x');
            });
            $this->assertThrowsDomData('INDEX_SIZE_ERR', function () use(&$node) {
                $node->insertData(-0x100000000 + 5, 'x');
            });
        }, $type . '.insertData() negative out of bounds');
        $this->assertTest(function () use(&$create) {
            $node = $create();
            $this->assertEqualsData($node->data, 'test');
            $node->insertData(-0x100000000 + 2, 'X');
            $this->assertEqualsData($node->data, 'teXst');
        }, $type . '.insertData() negative in bounds');
        $this->assertTest(function () use(&$create) {
            $node = $create();
            $this->assertEqualsData($node->data, 'test');
            $node->insertData(0, '');
            $this->assertEqualsData($node->data, 'test');
        }, $type . ".insertData('')");
        $this->assertTest(function () use(&$create) {
            $node = $create();
            $this->assertEqualsData($node->data, 'test');
            $node->insertData(0, 'X');
            $this->assertEqualsData($node->data, 'Xtest');
        }, $type . '.insertData() at the start');
        $this->assertTest(function () use(&$create) {
            $node = $create();
            $this->assertEqualsData($node->data, 'test');
            $node->insertData(2, 'X');
            $this->assertEqualsData($node->data, 'teXst');
        }, $type . '.insertData() in the middle');
        $this->assertTest(function () use(&$create) {
            $node = $create();
            $this->assertEqualsData($node->data, 'test');
            $node->insertData(4, 'ing');
            $this->assertEqualsData($node->data, 'testing');
        }, $type . '.insertData() at the end');
        $this->assertTest(function () use(&$create) {
            $node = $create();
            $node->data = "This is the character data, append more 資料，測試資料";
            $node->insertData(26, ' test');
            $this->assertEqualsData($node->data, "This is the character data test, append more 資料，測試資料");
            $node->insertData(48, "更多");
            $this->assertEqualsData($node->data, "This is the character data test, append more 資料，更多測試資料");
        }, $type . '.insertData() with non-ascii data');
        $this->assertTest(function () use(&$create) {
            $node = $create();
            $this->assertEqualsData($node->data, 'test');
            $node->data = "🌠 test 🌠 TEST";
            $node->insertData(5, '--');
            // Counting UTF-16 code units
            $this->assertEqualsData($node->data, "🌠 te--st 🌠 TEST");
        }, $type . '.insertData() with non-BMP data');
    }
    public function testCharacterDataInsertData()
    {
        $this->doc = $this->loadWptHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/CharacterData-insertData.html');
        $this->testNode(function () {
            return $this->doc->createTextNode('test');
        }, 'Text');
        $this->testNode(function () {
            return $this->doc->createComment('test');
        }, 'Comment');
    }
}
