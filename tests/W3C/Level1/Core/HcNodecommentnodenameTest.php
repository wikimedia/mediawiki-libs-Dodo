<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Comment;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\W3C\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodecommentnodename.js.
class HcNodecommentnodenameTest extends W3CTestHarness
{
    public function testHcNodecommentnodename()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'hc_nodecommentnodename') != null) {
            return;
        }
        $doc = null;
        $elementList = null;
        $commentNode = null;
        $nodeType = null;
        $commentName = null;
        $commentNodeName = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $elementList = $doc->childNodes;
        for ($indexN10044 = 0; $indexN10044 < count($elementList); $indexN10044++) {
            $commentNode = $elementList->item($indexN10044);
            $nodeType = $commentNode->nodeType;
            if (8 == $nodeType) {
                $commentNodeName = $commentNode->nodeName;
                $this->assertEqualsData('existingNodeName', '#comment', $commentNodeName);
            }
        }
        $commentNode = $doc->createComment('This is a comment');
        $commentNodeName = $commentNode->nodeName;
        $this->assertEqualsData('createdNodeName', '#comment', $commentNodeName);
    }
}
