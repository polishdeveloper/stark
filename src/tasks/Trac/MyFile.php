<?php




/** Task One
function solution($S) {
    $stack = array();
    $txtLen = mb_strlen($S);
    for($i = 0; $i < $txtLen; $i++) {
        $char = $S[$i];
        if (!array_key_exists($char, $stack)) {
            $stack[$char] = true;
        } else {
            unset($stack[$char]);
        }
    }
    return count($stack) < 2 ? 1 : 0;
}
 *
 */


/** Task Two
 *

function solution($S) {
$charToBitsCount = array(
'0' => 0, //0000
'1' => 1, //0001
'2' => 1, //0010
'3' => 2, //0011
'4' => 1, //0100
'5' => 2, //0101
'6' => 2, //0110
'7' => 3, //0111
'8' => 1, //1000
'9' => 2, //1001
'A' => 2, //1010
'B' => 3, //1011
'C' => 2, //1100
'D' => 3, //1101
'E' => 3, //1110
'F' => 4, //1111
);
$bitsCount = 0;
$txtLen = mb_strlen($S);
for($i = 0; $i < $txtLen; $i++) {
$bitsCount += $charToBitsCount[$S[$i]];
}
return $bitsCount;
}



 */

/**
function solution($A) {
    $jumpsCount = 0;
    $next = 0;

    while(array_key_exists($next, $A)) {
        if ($A[$next] === null) {
            return -1; //I was already here so we have cycle, exit
        }
        $previous = $next;
        $next = $next + $A[$next];
        $A[$previous] = null;
        $jumpsCount++;
    }
    return $jumpsCount;
}
**/



















function S($text) {
    $stack = array();
    $txtLen = mb_strlen($text);
    for($i = 0; $i < $txtLen; $i++) {
        $char = $text[$i];
        if (!array_key_exists($char, $stack)) {
            $stack[$char] = true;
        } else {
            unset($stack[$char]);
        }
    }
    return count($stack) < 2;
}





function check($palindrom) {
    $txtLength = mb_strlen($palindrom);
    $charsToCheck = floor($txtLength / 2);

    //A = 0.5 = 0
    //AA = 1 = 1
    //AAA = 1.5 = 1
    //AAAA = 2

    for ($i = 0; $i < $txtLength; $i++) {
        if ($palindrom[$i] != $palindrom[$txtLength - $i - 1]) {
            return false;

        }
        return true;
    }
}


var_dump(
    array(
        'a' => S('a'),
        'muum' => S('muum'),
        'aa' => S('aa'),
        'ab' => S('ab'),
        'aaa' => S('aaa'),
        'aca' => S('aca'),
        'kayak' => S('kayak'),
        'akayk' => S('akyak'),
        'codilitytilidoc' => S('codilitytilidoc'),
        'neveroddoreven' => S('neveroddoreven'),
        'dooernedeevrvn' => S('dooernedeevrvn'),
        'aabcba' => S('aabcba'),
    ));
