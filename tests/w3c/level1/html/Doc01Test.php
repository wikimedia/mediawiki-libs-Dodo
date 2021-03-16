<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/doc01.js.
class Doc01Test extends DomTestCase
{
    public function testDoc01()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'doc01') != null) {
            return;
        }
        $vtitle = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'anchor');
        $vtitle = $doc->title;
        $this->assertEqualsData('titleLink', 'NIST DOM HTML Test - Anchor', $vtitle);
    }
}