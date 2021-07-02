<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom\Nodes;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Text;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Node-constants.html.
class NodeConstantsTest extends WPTTestHarness
{
    public function testConstants($objects, $constants, $msg)
    {
        global $objects;
        foreach ($objects as $arr) {
            $o = $arr[0];
            $desc = $arr[1];
            $this->assertTest(function () use(&$constants, &$o) {
                foreach ($constants as $d) {
                    $this->wptAssertTrue(isset($o[$d[0]]), 'Object ' . $o . " doesn't have " . $d[0]);
                    $this->wptAssertEquals($o[$d[0]], $d[1], 'Object ' . $o . ' value for ' . $d[0] . ' is wrong');
                }
            }, 'Constants for ' . $msg . ' on ' . $desc . '.');
        }
    }
    public function testNodeConstants()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/Node-constants.html');
        $objects = null;
        // setup()
        $objects = [[Node, 'Node interface object'], [Node::class, 'Node prototype object'], [$this->doc->createElement('foo'), 'Element object'], [$this->doc->createTextNode('bar'), 'Text object']];
        $this->testConstants($objects, [['ELEMENT_NODE', 1], ['ATTRIBUTE_NODE', 2], ['TEXT_NODE', 3], ['CDATA_SECTION_NODE', 4], ['ENTITY_REFERENCE_NODE', 5], ['ENTITY_NODE', 6], ['PROCESSING_INSTRUCTION_NODE', 7], ['COMMENT_NODE', 8], ['DOCUMENT_NODE', 9], ['DOCUMENT_TYPE_NODE', 10], ['DOCUMENT_FRAGMENT_NODE', 11], ['NOTATION_NODE', 12]], 'nodeType');
        $this->testConstants($objects, [['DOCUMENT_POSITION_DISCONNECTED', 0x1], ['DOCUMENT_POSITION_PRECEDING', 0x2], ['DOCUMENT_POSITION_FOLLOWING', 0x4], ['DOCUMENT_POSITION_CONTAINS', 0x8], ['DOCUMENT_POSITION_CONTAINED_BY', 0x10], ['DOCUMENT_POSITION_IMPLEMENTATION_SPECIFIC', 0x20]], 'createDocumentPosition');
    }
}
