<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom\Nodes;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Comment;
use Wikimedia\Dodo\Text;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/CharacterData-substringData.html.
class CharacterDataSubstringDataTest extends WPTTestHarness
{
    public function helperTestNode($create, $type)
    {
        $this->assertTest(function () use (&$create) {
            $node = $create();
            $this->wptAssertEquals($node->data, 'test');
            $this->wptAssertThrowsJs($this->type_error, function () use (&$node) {
                $node->substringData();
            });
            $this->wptAssertThrowsJs($this->type_error, function () use (&$node) {
                $node->substringData(0);
            });
        }, $type . '.substringData() with too few arguments');
        $this->assertTest(function () use (&$create) {
            $node = $create();
            $this->wptAssertEquals($node->data, 'test');
            $this->wptAssertEquals($node->substringData(0, 1, 'test'), 't');
        }, $type . '.substringData() with too many arguments');
        $this->assertTest(function () use (&$create) {
            $node = $create();
            $this->wptAssertEquals($node->data, 'test');
            $this->wptAssertThrowsDom('IndexSizeError', function () use (&$node) {
                $node->substringData(5, 0);
            });
            $this->wptAssertThrowsDom('IndexSizeError', function () use (&$node) {
                $node->substringData(6, 0);
            });
            $this->wptAssertThrowsDom('IndexSizeError', function () use (&$node) {
                $node->substringData(-1, 0);
            });
        }, $type . '.substringData() with invalid offset');
        $this->assertTest(function () use (&$create) {
            $node = $create();
            $this->wptAssertEquals($node->data, 'test');
            $this->wptAssertEquals($node->substringData(0, 1), 't');
            $this->wptAssertEquals($node->substringData(1, 1), 'e');
            $this->wptAssertEquals($node->substringData(2, 1), 's');
            $this->wptAssertEquals($node->substringData(3, 1), 't');
            $this->wptAssertEquals($node->substringData(4, 1), '');
        }, $type . '.substringData() with in-bounds offset');
        $this->assertTest(function () use (&$create) {
            $node = $create();
            $this->wptAssertEquals($node->data, 'test');
            $this->wptAssertEquals($node->substringData(0, 0), '');
            $this->wptAssertEquals($node->substringData(1, 0), '');
            $this->wptAssertEquals($node->substringData(2, 0), '');
            $this->wptAssertEquals($node->substringData(3, 0), '');
            $this->wptAssertEquals($node->substringData(4, 0), '');
        }, $type . '.substringData() with zero count');
        $this->assertTest(function () use (&$create) {
            $node = $create();
            $this->wptAssertEquals($node->data, 'test');
            $this->wptAssertEquals($node->substringData(0x100000000 + 0, 1), 't');
            $this->wptAssertEquals($node->substringData(0x100000000 + 1, 1), 'e');
            $this->wptAssertEquals($node->substringData(0x100000000 + 2, 1), 's');
            $this->wptAssertEquals($node->substringData(0x100000000 + 3, 1), 't');
            $this->wptAssertEquals($node->substringData(0x100000000 + 4, 1), '');
        }, $type . '.substringData() with very large offset');
        $this->assertTest(function () use (&$create) {
            $node = $create();
            $this->wptAssertEquals($node->data, 'test');
            $this->wptAssertEquals($node->substringData(-0x100000000 + 2, 1), 's');
        }, $type . '.substringData() with negative offset');
        $this->assertTest(function () use (&$create) {
            $node = $create();
            $this->wptAssertEquals($node->data, 'test');
            $this->wptAssertEquals($node->substringData('test', 3), 'tes');
        }, $type . '.substringData() with string offset');
        $this->assertTest(function () use (&$create) {
            $node = $create();
            $this->wptAssertEquals($node->data, 'test');
            $this->wptAssertEquals($node->substringData(0, 1), 't');
            $this->wptAssertEquals($node->substringData(0, 2), 'te');
            $this->wptAssertEquals($node->substringData(0, 3), 'tes');
            $this->wptAssertEquals($node->substringData(0, 4), 'test');
        }, $type . '.substringData() with in-bounds count');
        $this->assertTest(function () use (&$create) {
            $node = $create();
            $this->wptAssertEquals($node->data, 'test');
            $this->wptAssertEquals($node->substringData(0, 5), 'test');
            $this->wptAssertEquals($node->substringData(2, 20), 'st');
        }, $type . '.substringData() with large count');
        $this->assertTest(function () use (&$create) {
            $node = $create();
            $this->wptAssertEquals($node->data, 'test');
            $this->wptAssertEquals($node->substringData(2, 0x100000000 + 1), 's');
        }, $type . '.substringData() with very large count');
        $this->assertTest(function () use (&$create) {
            $node = $create();
            $this->wptAssertEquals($node->data, 'test');
            $this->wptAssertEquals($node->substringData(0, -1), 'test');
            $this->wptAssertEquals($node->substringData(0, -0x100000000 + 2), 'te');
        }, $type . '.substringData() with negative count');
        $this->assertTest(function () use (&$create) {
            $node = $create();
            $this->wptAssertEquals($node->data, 'test');
            $node->data = "This is the character data test, other 資料，更多文字";
            $this->wptAssertEquals($node->substringData(12, 4), 'char');
            $this->wptAssertEquals($node->substringData(39, 2), "資料");
        }, $type . '.substringData() with non-ASCII data');
        $this->assertTest(function () use (&$create) {
            $node = $create();
            $this->wptAssertEquals($node->data, 'test');
            $node->data = "🌠 test 🌠 TEST";
            $this->wptAssertEquals($node->substringData(5, 8), "st 🌠 TE");
            // Counting UTF-16 code units
        }, $type . '.substringData() with non-BMP data');
    }
    public function testCharacterDataSubstringData()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/CharacterData-substringData.html');
        $this->helperTestNode(function () {
            return $this->doc->createTextNode('test');
        }, 'Text');
        $this->helperTestNode(function () {
            return $this->doc->createComment('test');
        }, 'Comment');
    }
}
