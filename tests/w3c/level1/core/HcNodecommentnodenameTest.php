<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodecommentnodename.js.
class HcNodecommentnodenameTest extends DomTestCase
{
    public function testHcNodecommentnodename()
    {
        $builder = $this->getBuilder();
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