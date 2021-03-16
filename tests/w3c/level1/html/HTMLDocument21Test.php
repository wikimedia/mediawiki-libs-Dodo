<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLDocument21.js.
class HTMLDocument21Test extends DomTestCase
{
    public function testHTMLDocument21()
    {
        $builder = $this->getBuilder();
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
        $doc->open();
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
        $doc->close();
    }
}