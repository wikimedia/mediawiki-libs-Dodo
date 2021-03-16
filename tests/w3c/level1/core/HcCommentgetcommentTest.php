<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_commentgetcomment.js.
class HcCommentgetcommentTest extends DomTestCase
{
    public function testHcCommentgetcomment()
    {
        $builder = $this->getBuilder();
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