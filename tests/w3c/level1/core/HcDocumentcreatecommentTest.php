<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_documentcreatecomment.js.
class HcDocumentcreatecommentTest extends DomTestCase
{
    public function testHcDocumentcreatecomment()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'hc_documentcreatecomment') != null) {
            return;
        }
        $doc = null;
        $newCommentNode = null;
        $newCommentValue = null;
        $newCommentName = null;
        $newCommentType = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $newCommentNode = $doc->createComment('This is a new Comment node');
        $newCommentValue = $newCommentNode->nodeValue;
        $this->assertEqualsData('value', 'This is a new Comment node', $newCommentValue);
        $newCommentName = $newCommentNode->nodeName;
        $this->assertEqualsData('strong', '#comment', $newCommentName);
        $newCommentType = $newCommentNode->nodeType;
        $this->assertEqualsData('type', 8, $newCommentType);
    }
}