<?php
$data = file( 'input-04.txt', FILE_IGNORE_NEW_LINES );
$dataLength = count( $data );

foreach( $data as &$line ) {
    [$area1, $area2] = explode( ',', $line);
    $line = ['area1' => explode( '-', $area1 ), 'area2' => explode( '-', $area2 )];
}
unset( $line );

/* --- Part One --- */

$firstPartResult = 0;

foreach( $data as $line) {
    if( ( $line['area1'][0] >= $line['area2'][0] && $line['area1'][1] <= $line['area2'][1] ) || ( $line['area2'][0] >= $line['area1'][0] && $line['area2'][1] <= $line['area1'][1] ) ) {

        $firstPartResult ++;
    }
}

echo "<b>$firstPartResult</b><br />";

/* --- Part Two --- */

$secondPartResult = count( $data );

foreach( $data as $line) {
    if( ($line['area1'][1] < $line['area2'][0] || $line['area1'][0] > $line['area2'][1] ) ) {

        $secondPartResult --;
    }
}

echo "<b>$secondPartResult</b><br />";