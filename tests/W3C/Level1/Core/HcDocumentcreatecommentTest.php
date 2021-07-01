<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Comment;
use Wikimedia\Dodo\DOMException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_documentcreatecomment.js.
class HcDocumentcreatecommentTest extends W3CTestHarness
{
    public function testHcDocumentcreatecomment()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
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
        $this->w3cAssertEquals('value', 'This is a new Comment node', $newCommentValue);
        $newCommentName = $newCommentNode->nodeName;
        $this->w3cAssertEquals('strong', '#comment', $newCommentName);
        $newCommentType = $newCommentNode->nodeType;
        $this->w3cAssertEquals('type', 8, $newCommentType);
    }
}
