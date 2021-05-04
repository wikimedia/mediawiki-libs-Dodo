<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Comment;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\W3c\Harness\W3cTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_commentgetcomment.js.
class HcCommentgetcommentTest extends W3cTestHarness
{
    public function testHcCommentgetcomment()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'hc_commentgetcomment') != null) {
            return;
        }
        $doc = null;
        $elementList = null;
        $child = null;
        $childName = null;
        $childValue = null;
        $commentCount = 0;
        $childType = null;
        $attributes = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $elementList = $doc->childNodes;
        for ($indexN1005E = 0; $indexN1005E < count($elementList); $indexN1005E++) {
            $child = $elementList->item($indexN1005E);
            $childType = $child->nodeType;
            if (8 == $childType) {
                $childName = $child->nodeName;
                $this->assertEqualsData('nodeName', '#comment', $childName);
                $childValue = $child->nodeValue;
                $this->assertEqualsData('nodeValue', ' This is comment number 1.', $childValue);
                $attributes = $child->attributes;
                $this->assertNullData('attributes', $attributes);
                $commentCount += 1;
            }
        }
        $this->assertTrueData('atMostOneComment', $commentCount < 2);
    }
}
