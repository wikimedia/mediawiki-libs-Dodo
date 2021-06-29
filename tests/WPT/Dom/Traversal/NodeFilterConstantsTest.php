<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\NodeFilter;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/traversal/NodeFilter-constants.html.
class NodeFilterConstantsTest extends WPTTestHarness
{
    public function testConstants($objects, $constants, $msg)
    {
        global $objects;
        foreach ($objects as $arr) {
            $o = $arr[0];
            $desc = $arr[1];
            $this->assertTest(function () use(&$constants, &$o) {
                foreach ($constants as $d) {
                    $this->assertTrueData(isset($o[$d[0]]), 'Object ' . $o . " doesn't have " . $d[0]);
                    $this->assertEqualsData($o[$d[0]], $d[1], 'Object ' . $o . ' value for ' . $d[0] . ' is wrong');
                }
            }, 'Constants for ' . $msg . ' on ' . $desc . '.');
        }
    }
    public function testNodeFilterConstants()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/traversal/NodeFilter-constants.html');
        $objects = null;
        // setup()
        $objects = [[NodeFilter, 'NodeFilter interface object']];
        $this->testConstants($objects, [['FILTER_ACCEPT', 1], ['FILTER_REJECT', 2], ['FILTER_SKIP', 3]], 'acceptNode');
        $this->testConstants($objects, [['SHOW_ALL', 0xffffffff], ['SHOW_ELEMENT', 0x1], ['SHOW_ATTRIBUTE', 0x2], ['SHOW_TEXT', 0x4], ['SHOW_CDATA_SECTION', 0x8], ['SHOW_ENTITY_REFERENCE', 0x10], ['SHOW_ENTITY', 0x20], ['SHOW_PROCESSING_INSTRUCTION', 0x40], ['SHOW_COMMENT', 0x80], ['SHOW_DOCUMENT', 0x100], ['SHOW_DOCUMENT_TYPE', 0x200], ['SHOW_DOCUMENT_FRAGMENT', 0x400], ['SHOW_NOTATION', 0x800]], 'whatToShow');
    }
}
