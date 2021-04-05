<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Comment;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodecommentnodevalue.js.
class HcNodecommentnodevalueTest extends W3cTestHarness
{
    public function testHcNodecommentnodevalue()
    {
        $builder = $this->getBuilder();
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
                $this->assertEqualsData('value', ' This is comment number 1.', $commentValue);
            }
        }
        $commentNode = $doc->createComment(' This is a comment');
        $commentValue = $commentNode->nodeValue;
        $this->assertEqualsData('createdCommentNodeValue', ' This is a comment', $commentValue);
    }
}
