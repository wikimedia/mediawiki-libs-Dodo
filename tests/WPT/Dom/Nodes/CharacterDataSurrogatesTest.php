<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom\Nodes;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Comment;
use Wikimedia\Dodo\Text;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/CharacterData-surrogates.html.
class CharacterDataSurrogatesTest extends WPTTestHarness
{
    public function helperTestNode($create, $type)
    {
        $this->assertTest(function () use (&$create) {
            $node = $create();
            $this->wptAssertEquals($node->data, 'test');
            $node->data = "🌠 test 🌠 TEST";
            $this->wptAssertEquals($node->substringData(1, 8), "\\udf20 test \\ud83c");
        }, $type . '.substringData() splitting surrogate pairs');
        $this->assertTest(function () use (&$create) {
            $node = $create();
            $this->wptAssertEquals($node->data, 'test');
            $node->data = "🌠 test 🌠 TEST";
            $node->replaceData(1, 4, '--');
            $this->wptAssertEquals($node->data, "\\ud83c--st 🌠 TEST");
            $node->replaceData(1, 2, "\\udf1f ");
            $this->wptAssertEquals($node->data, "🌟 st 🌠 TEST");
            $node->replaceData(5, 2, '---');
            $this->wptAssertEquals($node->data, "🌟 st---\\udf20 TEST");
            $node->replaceData(6, 2, " \\ud83d");
            $this->wptAssertEquals($node->data, "🌟 st- 🜠 TEST");
        }, $type . '.replaceData() splitting and creating surrogate pairs');
        $this->assertTest(function () use (&$create) {
            $node = $create();
            $this->wptAssertEquals($node->data, 'test');
            $node->data = "🌠 test 🌠 TEST";
            $node->deleteData(1, 4);
            $this->wptAssertEquals($node->data, "\\ud83cst 🌠 TEST");
            $node->deleteData(1, 4);
            $this->wptAssertEquals($node->data, "🌠 TEST");
        }, $type . '.deleteData() splitting and creating surrogate pairs');
        $this->assertTest(function () use (&$create) {
            $node = $create();
            $this->wptAssertEquals($node->data, 'test');
            $node->data = "🌠 test 🌠 TEST";
            $node->insertData(1, '--');
            $this->wptAssertEquals($node->data, "\\ud83c--\\udf20 test 🌠 TEST");
            $node->insertData(1, "\\udf1f ");
            $this->wptAssertEquals($node->data, "🌟 --\\udf20 test 🌠 TEST");
            $node->insertData(5, " \\ud83d");
            $this->wptAssertEquals($node->data, "🌟 -- 🜠 test 🌠 TEST");
        }, $type . '.insertData() splitting and creating surrogate pairs');
    }
    public function testCharacterDataSurrogates()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/CharacterData-surrogates.html');
        $this->helperTestNode(function () {
            return $this->doc->createTextNode('test');
        }, 'Text');
        $this->helperTestNode(function () {
            return $this->doc->createComment('test');
        }, 'Comment');
    }
}
