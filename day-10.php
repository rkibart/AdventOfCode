<?php
$data = file( 'input-10.txt', FILE_IGNORE_NEW_LINES );
foreach( $data as &$row ) {
    $row = explode( ' ', $row );
}

$cycle = 1;
$xRegister = 1;
$drawedCRTLines = array_fill(0, 6, '');
$signalStrength = 0;

foreach($data as $instructionLine ) {
    $instrunction = $instructionLine[0];
    $value = $instructionLine[1] ?? null;

    switch( $instrunction ) {

        case 'noop':
            if( $cycle % 40 === 20) {

                $signalStrength += $cycle * $xRegister;
            }

            beforeCycleTick($cycle, $xRegister, $drawedCRTLines);
            $cycle++;
            break;

        case 'addx':
            if( $cycle % 40 === 20) {
                
                $signalStrength += $cycle * $xRegister;
            }

            beforeCycleTick($cycle, $xRegister, $drawedCRTLines);
            $cycle++;

            if( $cycle % 40 === 20) {

                $signalStrength += $cycle * $xRegister;
            }

            beforeCycleTick($cycle, $xRegister, $drawedCRTLines);
            $cycle++;
            $xRegister += $value;
            break;
    }
}

function  beforeCycleTick( int $cycle, int $register, array &$printArray): void {

    $line = floor( ( $cycle - 1 ) / 40 );
    $position = $cycle - 1 - 40 * $line;

    if( $position >= $register - 1 && $position <= $register + 1 ) {
        $printArray[$line] .= '#';
    }else {
        $printArray[$line] .= '_';
    }
}
/* --- Part One --- */

$firstPartResult = $signalStrength;
echo "<b>$firstPartResult</b><br />";

/* --- Part Two --- */

foreach($drawedCRTLines as $line ) {
    echo $line . '<br />';
}