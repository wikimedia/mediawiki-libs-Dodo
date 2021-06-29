<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Comment;
use Wikimedia\Dodo\Text;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/CharacterData-surrogates.html.
class CharacterDataSurrogatesTest extends WPTTestHarness
{
    public function testNode($create, $type)
    {
        $this->assertTest(function () use(&$create) {
            $node = $create();
            $this->assertEqualsData($node->data, 'test');
            $node->data = "🌠 test 🌠 TEST";
            $this->assertEqualsData($node->substringData(1, 8), "� test �");
        }, $type . '.substringData() splitting surrogate pairs');
        $this->assertTest(function () use(&$create) {
            $node = $create();
            $this->assertEqualsData($node->data, 'test');
            $node->data = "🌠 test 🌠 TEST";
            $node->replaceData(1, 4, '--');
            $this->assertEqualsData($node->data, "�--st 🌠 TEST");
            $node->replaceData(1, 2, "� ");
            $this->assertEqualsData($node->data, "🌟 st 🌠 TEST");
            $node->replaceData(5, 2, '---');
            $this->assertEqualsData($node->data, "🌟 st---� TEST");
            $node->replaceData(6, 2, " �");
            $this->assertEqualsData($node->data, "🌟 st- 🜠 TEST");
        }, $type . '.replaceData() splitting and creating surrogate pairs');
        $this->assertTest(function () use(&$create) {
            $node = $create();
            $this->assertEqualsData($node->data, 'test');
            $node->data = "🌠 test 🌠 TEST";
            $node->deleteData(1, 4);
            $this->assertEqualsData($node->data, "�st 🌠 TEST");
            $node->deleteData(1, 4);
            $this->assertEqualsData($node->data, "🌠 TEST");
        }, $type . '.deleteData() splitting and creating surrogate pairs');
        $this->assertTest(function () use(&$create) {
            $node = $create();
            $this->assertEqualsData($node->data, 'test');
            $node->data = "🌠 test 🌠 TEST";
            $node->insertData(1, '--');
            $this->assertEqualsData($node->data, "�--� test 🌠 TEST");
            $node->insertData(1, "� ");
            $this->assertEqualsData($node->data, "🌟 --� test 🌠 TEST");
            $node->insertData(5, " �");
            $this->assertEqualsData($node->data, "🌟 -- 🜠 test 🌠 TEST");
        }, $type . '.insertData() splitting and creating surrogate pairs');
    }
    public function testCharacterDataSurrogates()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/CharacterData-surrogates.html');
        $this->testNode(function () {
            return $this->doc->createTextNode('test');
        }, 'Text');
        $this->testNode(function () {
            return $this->doc->createComment('test');
        }, 'Comment');
    }
}
