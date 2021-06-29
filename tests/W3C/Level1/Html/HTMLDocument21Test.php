<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Text;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLDocument21.js.
class HTMLDocument21Test extends W3CTestHarness
{
    public function testHTMLDocument21()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'HTMLDocument21') != null) {
            return;
        }
        $doc = null;
        $docElem = null;
        $preElems = null;
        $preElem = null;
        $preText = null;
        $preValue = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'document');
        // $doc->open();
        if ($builder->contentType == 'text/html') {
            $doc->writeln('<html>');
        } else {
            $doc->writeln("<html xmlns='http://www.w3.org/1999/xhtml'>");
        }
        $doc->writeln('<body>');
        $doc->writeln('<title>Replacement</title>');
        $doc->writeln('</body>');
        $doc->write('<pre>');
        $doc->writeln('Hello, World.');
        $doc->writeln('Hello, World.');
        $doc->writeln('</pre>');
        $doc->write('<pre>');
        $doc->write('Hello, World.');
        $doc->write('Hello, World.');
        $doc->writeln('</pre>');
        $doc->writeln('</body>');
        $doc->writeln('</html>');
        // $doc->close();
    }
}
