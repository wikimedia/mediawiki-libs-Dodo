<?php 
namespace Wikimedia\Dodo\Tests\Wpt\Dom;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Comment;
use Wikimedia\Dodo\Text;
use Wikimedia\Dodo\CharacterData;
use Wikimedia\Dodo\Tests\Wpt\Harness\WptTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/CharacterData-appendChild.html.
class CharacterDataAppendChildTest extends WptTestHarness
{
    public function create($type)
    {
        switch ($type) {
            case 'Text':
                return $this->doc->createTextNode('test');
                break;
            case 'Comment':
                return $this->doc->createComment('test');
                break;
            case 'ProcessingInstruction':
                return $this->doc->createProcessingInstruction('target', 'test');
                break;
        }
    }
    public function testNode($type1, $type2)
    {
        $this->assertTest(function () use(&$type1, &$type2) {
            $node1 = $this->create($type1);
            $node2 = $this->create($type2);
            $this->assertThrowsDomData('HierarchyRequestError', function () use(&$node1, &$node2) {
                $node1->appendChild($node2);
            }, 'CharacterData type ' . $type1 . ' must not have children');
        }, $type1 . '.appendChild(' . $type2 . ')');
    }
    public function testCharacterDataAppendChild()
    {
        $this->source_file = 'vendor/web-platform-tests/wpt/dom/nodes/CharacterData-appendChild.html';
        $types = ['Text', 'Comment', 'ProcessingInstruction'];
        foreach ($types as $type1) {
            foreach ($types as $type2) {
                $this->testNode($type1, $type2);
            }
        }
    }
}
