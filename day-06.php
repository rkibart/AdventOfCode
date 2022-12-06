<?php
$data = file_get_contents( 'input-06.txt' );

/* --- Part One --- */
$firstPartResult = solution( $data, 4 );
echo "<b>$firstPartResult</b><br />";

/* --- Part Two --- */
$secondPartResult = solution( $data, 14 );
echo "<b>$secondPartResult</b><br />";

function solution( string $data, int $frameLength ) : int {
    $dataLength = strlen( $data );
    for( $i = 0; $i < $dataLength; $i++ ) {
        $result = 0;
        $marker[]= $data[$i];
        if( count( $marker ) >= $frameLength) {            
            if( count( array_unique( $marker) ) !== $frameLength ) {
                array_shift( $marker );
            } else {
                $result = $i + 1;
                break;
            }
        }
    }
    return $result;
}

