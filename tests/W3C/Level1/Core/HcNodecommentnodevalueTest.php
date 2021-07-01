<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Comment;
use Wikimedia\Dodo\DOMException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodecommentnodevalue.js.
class HcNodecommentnodevalueTest extends W3CTestHarness
{
    public function testHcNodecommentnodevalue()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'hc_nodecommentnodevalue') != null) {
            return;
        }
        $doc = null;
        $elementList = null;
        $commentNode = null;
        $commentName = null;
        $commentValue = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $elementList = $doc->childNodes;
        for ($indexN10040 = 0; $indexN10040 < count($elementList); $indexN10040++) {
            $commentNode = $elementList->item($indexN10040);
            $commentName = $commentNode->nodeName;
            if ('#comment' == $commentName) {
                $commentValue = $commentNode->nodeValue;
                $this->w3cAssertEquals('value', ' This is comment number 1.', $commentValue);
            }
        }
        $commentNode = $doc->createComment(' This is a comment');
        $commentValue = $commentNode->nodeValue;
        $this->w3cAssertEquals('createdCommentNodeValue', ' This is a comment', $commentValue);
    }
}
