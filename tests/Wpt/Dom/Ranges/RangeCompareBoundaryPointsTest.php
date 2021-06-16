<?php 
namespace Wikimedia\Dodo\Tests\Wpt\Dom;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Range;
use Wikimedia\Dodo\Tests\Wpt\Harness\WptTestHarness;
// @see vendor/web-platform-tests/wpt/dom/ranges/Range-compareBoundaryPoints.html.
class RangeCompareBoundaryPointsTest extends WptTestHarness
{
    public function testRangeCompareBoundaryPoints()
    {
        $this->doc = $this->loadWptHtmlFile('vendor/web-platform-tests/wpt/dom/ranges/Range-compareBoundaryPoints.html');
        $testRangesCached = [];
        $testRangesCached[] = $this->doc->createRange();
        $testRangesCached[0]->detach();
        for ($i = 0; $i < count($this->testRangesShort); $i++) {
            try {
                $testRangesCached[] = rangeFromEndpoints(eval($this->testRangesShort[$i]));
            } catch (Exception $e) {
                $testRangesCached[] = null;
            }
        }
        $testRangesCachedClones = [];
        $testRangesCachedClones[] = $this->doc->createRange();
        $testRangesCachedClones[0]->detach();
        for ($i = 1; $i < count($testRangesCached); $i++) {
            if ($testRangesCached[$i]) {
                $testRangesCachedClones[] = $testRangesCached[$i]->cloneRange();
            } else {
                $testRangesCachedClones[] = null;
            }
        }
        // We want to run a whole bunch of extra tests with invalid "how" values (not
        // 0-3), but it's excessive to run them for every single pair of ranges --
        // there are too many of them.  So just run them for a handful of the tests.
        $extraTests = [
            0,
            // detached
            1 + array_search('[paras[0].firstChild, 2, paras[0].firstChild, 8]', $testRanges),
            1 + array_search('[paras[0].firstChild, 3, paras[3], 1]', $testRanges),
            1 + array_search('[testDiv, 0, comment, 5]', $testRanges),
            1 + array_search('[foreignDoc.documentElement, 0, foreignDoc.documentElement, 1]', $testRanges),
        ];
        for ($i = 0; $i < count($testRangesCached); $i++) {
            $range1 = $testRangesCached[$i];
            $range1Desc = $i . ' ' . ($i == 0 ? '[detached]' : $testRanges[$i - 1]);
            for ($j = 0; $j <= count($testRangesCachedClones); $j++) {
                $range2 = null;
                $range2Desc = null;
                if ($j == count($testRangesCachedClones)) {
                    $range2 = $range1;
                    $range2Desc = 'same as first range';
                } else {
                    $range2 = $testRangesCachedClones[$j];
                    $range2Desc = $j . ' ' . ($j == 0 ? '[detached]' : $testRanges[$j - 1]);
                }
                $hows = [Range\START_TO_START, Range\START_TO_END, Range\END_TO_END, Range\END_TO_START];
                if (array_search($i, $extraTests) != -1 && array_search($j, $extraTests) != -1) {
                    // TODO: Make some type of reusable utility function to do this
                    // work.
                    array_push($hows, -1, 4, 5, $NaN, -0, +$Infinity, -$Infinity);
                    foreach ([65536, -65536, 65536 * 65536, 0.5, -0.5, -72.5] as $addend) {
                        array_push($hows, -1 + $addend, 0 + $addend, 1 + $addend, 2 + $addend, 3 + $addend, 4 + $addend);
                    }
                    foreach ($hows as $how) {
                        $hows[] = strval($how);
                    }
                    array_push($hows, '6.5536e4', null, null, true, false, '', 'quasit');
                }
                for ($k = 0; $k < count($hows); $k++) {
                    $how = $hows[$k];
                    $this->assertTest(function () use(&$range1, &$range2, &$how) {
                        $this->assertNotEqualsData($range1, null, 'Creating context range threw an exception');
                        $this->assertNotEqualsData($range2, null, 'Creating argument range threw an exception');
                        // Convert how per WebIDL.  TODO: Make some type of reusable
                        // utility function to do this work.
                        // "Let number be the result of calling ToNumber on the input
                        // argument."
                        $convertedHow = intval($how);
                        // "If number is NaN, +0, −0, +∞, or −∞, return +0."
                        if (isNaN($convertedHow) || $convertedHow == 0 || $convertedHow == $Infinity || $convertedHow == -$Infinity) {
                            $convertedHow = 0;
                        } else {
                            // "Let posInt be sign(number) * floor(abs(number))."
                            $posInt = ($convertedHow < 0 ? -1 : 1) * floor(abs($convertedHow));
                            // "Let int16bit be posInt modulo 2^16; that is, a finite
                            // integer value k of Number type with positive sign and
                            // less than 2^16 in magnitude such that the mathematical
                            // difference of posInt and k is mathematically an integer
                            // multiple of 2^16."
                            //
                            // "Return int16bit."
                            $convertedHow = $posInt % 65536;
                            if ($convertedHow < 0) {
                                $convertedHow += 65536;
                            }
                        }
                        // Now to the actual algorithm.
                        // "If how is not one of
                        //   START_TO_START,
                        //   START_TO_END,
                        //   END_TO_END, and
                        //   END_TO_START,
                        // throw a "NotSupportedError" exception and terminate these
                        // steps."
                        if ($convertedHow != Range\START_TO_START && $convertedHow != Range\START_TO_END && $convertedHow != Range\END_TO_END && $convertedHow != Range\END_TO_START) {
                            $this->assertThrowsDomData('NOT_SUPPORTED_ERR', function () use(&$range1, &$how, &$range2) {
                                $range1->compareBoundaryPoints($how, $range2);
                            }, "NotSupportedError required if first parameter doesn't convert to 0-3 per WebIDL");
                            return;
                        }
                        // "If context object's root is not the same as sourceRange's
                        // root, throw a "WrongDocumentError" exception and terminate
                        // these steps."
                        if ($this->furthestAncestor($range1->startContainer) != $this->furthestAncestor($range2->startContainer)) {
                            $this->assertThrowsDomData('WRONG_DOCUMENT_ERR', function () use(&$range1, &$how, &$range2) {
                                $range1->compareBoundaryPoints($how, $range2);
                            }, "WrongDocumentError required if the ranges don't share a root");
                            return;
                        }
                        // "If how is:
                        //   START_TO_START:
                        //     Let this point be the context object's start.
                        //     Let other point be sourceRange's start.
                        //   START_TO_END:
                        //     Let this point be the context object's end.
                        //     Let other point be sourceRange's start.
                        //   END_TO_END:
                        //     Let this point be the context object's end.
                        //     Let other point be sourceRange's end.
                        //   END_TO_START:
                        //     Let this point be the context object's start.
                        //     Let other point be sourceRange's end."
                        $thisPoint = $convertedHow == Range\START_TO_START || $convertedHow == Range\END_TO_START ? [$range1->startContainer, $range1->startOffset] : [$range1->endContainer, $range1->endOffset];
                        $otherPoint = $convertedHow == Range\START_TO_START || $convertedHow == Range\START_TO_END ? [$range2->startContainer, $range2->startOffset] : [$range2->endContainer, $range2->endOffset];
                        // "If the position of this point relative to other point is
                        //   before
                        //     Return −1.
                        //   equal
                        //     Return 0.
                        //   after
                        //     Return 1."
                        $position = getPosition($thisPoint[0], $thisPoint[1], $otherPoint[0], $otherPoint[1]);
                        $expected = null;
                        if ($position == 'before') {
                            $expected = -1;
                        } elseif ($position == 'equal') {
                            $expected = 0;
                        } elseif ($position == 'after') {
                            $expected = 1;
                        }
                        $this->assertEqualsData($range1->compareBoundaryPoints($how, $range2), $expected, 'Wrong return value');
                    }, $i . ',' . $j . ',' . $k . ': context range ' . $range1Desc . ', argument range ' . $range2Desc . ', how ' . $this->formatValue($how));
                }
            }
        }
        $testDiv->style->display = 'none';
    }
}
