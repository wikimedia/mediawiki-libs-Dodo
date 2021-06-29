<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Attr;
use Wikimedia\Dodo\Comment;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\W3C\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodecommentnodeattributes.js.
class HcNodecommentnodeattributesTest extends W3CTestHarness
{
    public function testHcNodecommentnodeattributes()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'hc_nodecommentnodeattributes') != null) {
            return;
        }
        $doc = null;
        $commentNode = null;
        $nodeList = null;
        $attrList = null;
        $nodeType = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $nodeList = $doc->childNodes;
        for ($indexN10043 = 0; $indexN10043 < count($nodeList); $indexN10043++) {
            $commentNode = $nodeList->item($indexN10043);
            $nodeType = $commentNode->nodeType;
            if (8 == $nodeType) {
                $attrList = $commentNode->attributes;
                $this->assertNullData('existingCommentAttributesNull', $attrList);
            }
        }
        $commentNode = $doc->createComment('This is a comment');
        $attrList = $commentNode->attributes;
        $this->assertNullData('createdCommentAttributesNull', $attrList);
    }
}
