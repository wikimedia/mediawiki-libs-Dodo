<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodecommentnodetype.js.
class HcNodecommentnodetypeTest extends DomTestCase
{
    public function testHcNodecommentnodetype()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'hc_nodecommentnodetype') != null) {
            return;
        }
        $doc = null;
        $testList = null;
        $commentNode = null;
        $commentNodeName = null;
        $nodeType = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $testList = $doc->childNodes;
        for ($indexN10040 = 0; $indexN10040 < count($testList); $indexN10040++) {
            $commentNode = $testList->item($indexN10040);
            $commentNodeName = $commentNode->nodeName;
            if ('#comment' == $commentNodeName) {
                $nodeType = $commentNode->nodeType;
                $this->assertEqualsData('existingCommentNodeType', 8, $nodeType);
            }
        }
        $commentNode = $doc->createComment('This is a comment');
        $nodeType = $commentNode->nodeType;
        $this->assertEqualsData('createdCommentNodeType', 8, $nodeType);
    }
}