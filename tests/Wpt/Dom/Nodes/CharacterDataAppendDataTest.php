<?php 
namespace Wikimedia\Dodo\Tests\Wpt\Dom;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Comment;
use Wikimedia\Dodo\Text;
use Wikimedia\Dodo\Tests\Wpt\Harness\WptTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/CharacterData-appendData.html.
class CharacterDataAppendDataTest extends WptTestHarness
{
    public function testNode($create, $type)
    {
        $this->assertTest(function () use(&$create) {
            $node = $create();
            $this->assertEqualsData($node->data, 'test');
            $node->appendData('bar');
            $this->assertEqualsData($node->data, 'testbar');
        }, $type . ".appendData('bar')");
        $this->assertTest(function () use(&$create) {
            $node = $create();
            $this->assertEqualsData($node->data, 'test');
            $node->appendData('');
            $this->assertEqualsData($node->data, 'test');
        }, $type . ".appendData('')");
        $this->assertTest(function () use(&$create) {
            $node = $create();
            $this->assertEqualsData($node->data, 'test');
            $node->appendData(", append more 資料，測試資料");
            $this->assertEqualsData($node->data, "test, append more 資料，測試資料");
            $this->assertEqualsData(count($node), 25);
        }, $type . '.appendData(non-ASCII)');
        $this->assertTest(function () use(&$create) {
            $node = $create();
            $this->assertEqualsData($node->data, 'test');
            $node->appendData(null);
            $this->assertEqualsData($node->data, 'testnull');
        }, $type . '.appendData(null)');
        $this->assertTest(function () use(&$create) {
            $node = $create();
            $this->assertEqualsData($node->data, 'test');
            $node->appendData(null);
            $this->assertEqualsData($node->data, 'testundefined');
        }, $type . '.appendData(undefined)');
        $this->assertTest(function () use(&$create) {
            $node = $create();
            $this->assertEqualsData($node->data, 'test');
            $node->appendData('', 'bar');
            $this->assertEqualsData($node->data, 'test');
        }, $type . ".appendData('', 'bar')");
        $this->assertTest(function () use(&$create) {
            $node = $create();
            $this->assertEqualsData($node->data, 'test');
            $this->assertThrowsJsData($this->type_error, function () use(&$node) {
                $node->appendData();
            });
            $this->assertEqualsData($node->data, 'test');
        }, $type . '.appendData()');
    }
    public function testCharacterDataAppendData()
    {
        $this->source_file = 'vendor/web-platform-tests/wpt/dom/nodes/CharacterData-appendData.html';
        $this->testNode(function () {
            return $this->doc->createTextNode('test');
        }, 'Text');
        $this->testNode(function () {
            return $this->doc->createComment('test');
        }, 'Comment');
    }
}
