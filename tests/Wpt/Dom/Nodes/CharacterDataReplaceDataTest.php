<?php 
namespace Wikimedia\Dodo\Tests\Wpt\Dom;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Comment;
use Wikimedia\Dodo\Text;
use Wikimedia\Dodo\Tests\Wpt\Harness\WptTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/CharacterData-replaceData.html.
class CharacterDataReplaceDataTest extends WptTestHarness
{
    public function testNode($create, $type)
    {
        // Step 2.
        $this->assertTest(function () use(&$create) {
            $node = $create();
            $this->assertEqualsData($node->data, 'test');
            $this->assertThrowsDomData('IndexSizeError', function () use(&$node) {
                $node->replaceData(5, 1, 'x');
            });
            $this->assertThrowsDomData('IndexSizeError', function () use(&$node) {
                $node->replaceData(5, 0, '');
            });
            $this->assertThrowsDomData('IndexSizeError', function () use(&$node) {
                $node->replaceData(-1, 1, 'x');
            });
            $this->assertThrowsDomData('IndexSizeError', function () use(&$node) {
                $node->replaceData(-1, 0, '');
            });
            $this->assertEqualsData($node->data, 'test');
        }, $type . '.replaceData() with invalid offset');
        // Step 3.
        $this->assertTest(function () use(&$create) {
            $node = $create();
            $this->assertEqualsData($node->data, 'test');
            $node->replaceData(2, 10, 'yo');
            $this->assertEqualsData($node->data, 'teyo');
        }, $type . '.replaceData() with clamped count');
        $this->assertTest(function () use(&$create) {
            $node = $create();
            $this->assertEqualsData($node->data, 'test');
            $node->replaceData(2, -1, 'yo');
            $this->assertEqualsData($node->data, 'teyo');
        }, $type . '.replaceData() with negative clamped count');
        $this->assertTest(function () use(&$create) {
            $node = $create();
            $this->assertEqualsData($node->data, 'test');
            $node->replaceData(0, 0, 'yo');
            $this->assertEqualsData($node->data, 'yotest');
        }, $type . '.replaceData() before the start');
        $this->assertTest(function () use(&$create) {
            $node = $create();
            $this->assertEqualsData($node->data, 'test');
            $node->replaceData(0, 2, 'y');
            $this->assertEqualsData($node->data, 'yst');
        }, $type . '.replaceData() at the start (shorter)');
        $this->assertTest(function () use(&$create) {
            $node = $create();
            $this->assertEqualsData($node->data, 'test');
            $node->replaceData(0, 2, 'yo');
            $this->assertEqualsData($node->data, 'yost');
        }, $type . '.replaceData() at the start (equal length)');
        $this->assertTest(function () use(&$create) {
            $node = $create();
            $this->assertEqualsData($node->data, 'test');
            $node->replaceData(0, 2, 'yoa');
            $this->assertEqualsData($node->data, 'yoast');
        }, $type . '.replaceData() at the start (longer)');
        $this->assertTest(function () use(&$create) {
            $node = $create();
            $this->assertEqualsData($node->data, 'test');
            $node->replaceData(1, 2, 'o');
            $this->assertEqualsData($node->data, 'tot');
        }, $type . '.replaceData() in the middle (shorter)');
        $this->assertTest(function () use(&$create) {
            $node = $create();
            $this->assertEqualsData($node->data, 'test');
            $node->replaceData(1, 2, 'yo');
            $this->assertEqualsData($node->data, 'tyot');
        }, $type . '.replaceData() in the middle (equal length)');
        $this->assertTest(function () use(&$create) {
            $node = $create();
            $this->assertEqualsData($node->data, 'test');
            $node->replaceData(1, 1, 'waddup');
            $this->assertEqualsData($node->data, 'twaddupst');
            $node->replaceData(1, 1, 'yup');
            $this->assertEqualsData($node->data, 'tyupaddupst');
        }, $type . '.replaceData() in the middle (longer)');
        $this->assertTest(function () use(&$create) {
            $node = $create();
            $this->assertEqualsData($node->data, 'test');
            $node->replaceData(1, 20, 'yo');
            $this->assertEqualsData($node->data, 'tyo');
        }, $type . '.replaceData() at the end (shorter)');
        $this->assertTest(function () use(&$create) {
            $node = $create();
            $this->assertEqualsData($node->data, 'test');
            $node->replaceData(2, 20, 'yo');
            $this->assertEqualsData($node->data, 'teyo');
        }, $type . '.replaceData() at the end (same length)');
        $this->assertTest(function () use(&$create) {
            $node = $create();
            $this->assertEqualsData($node->data, 'test');
            $node->replaceData(4, 20, 'yo');
            $this->assertEqualsData($node->data, 'testyo');
        }, $type . '.replaceData() at the end (longer)');
        $this->assertTest(function () use(&$create) {
            $node = $create();
            $this->assertEqualsData($node->data, 'test');
            $node->replaceData(0, 4, 'quux');
            $this->assertEqualsData($node->data, 'quux');
        }, $type . '.replaceData() the whole string');
        $this->assertTest(function () use(&$create) {
            $node = $create();
            $this->assertEqualsData($node->data, 'test');
            $node->replaceData(0, 4, '');
            $this->assertEqualsData($node->data, '');
        }, $type . '.replaceData() with the empty string');
        $this->assertTest(function () use(&$create) {
            $node = $create();
            $this->assertEqualsData($node->data, 'test');
            $node->data = "This is the character data test, append 資料，更多資料";
            $node->replaceData(33, 6, 'other');
            $this->assertEqualsData($node->data, "This is the character data test, other 資料，更多資料");
            $node->replaceData(44, 2, "文字");
            $this->assertEqualsData($node->data, "This is the character data test, other 資料，更多文字");
        }, $type . '.replaceData() with non-ASCII data');
        $this->assertTest(function () use(&$create) {
            $node = $create();
            $this->assertEqualsData($node->data, 'test');
            $node->data = "🌠 test 🌠 TEST";
            $node->replaceData(5, 8, '--');
            // Counting UTF-16 code units
            $this->assertEqualsData($node->data, "🌠 te--ST");
        }, $type . '.replaceData() with non-BMP data');
    }
    public function testCharacterDataReplaceData()
    {
        $this->source_file = 'vendor/web-platform-tests/wpt/dom/nodes/CharacterData-replaceData.html';
        $this->testNode(function () {
            return $this->doc->createTextNode('test');
        }, 'Text');
        $this->testNode(function () {
            return $this->doc->createComment('test');
        }, 'Comment');
    }
}
