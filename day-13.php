<?php
$data = file_get_contents( 'input-13.txt', FILE_IGNORE_NEW_LINES );
$data = preg_split( '/\r\n|\r|\n/', $data );
$data[] = '';

/* --- Part One --- */
$pairs = [];
for( $i=0; $i < count( $data ); $i++ ) {
    if( $data[$i] === '' ) {
        $pairs[] = $pair;
        $pair = [];
    } else {
        $pair[] = json_decode( $data[$i] );
    }
}

$partOneComparisonResults = [];

foreach( $pairs as $pair ) {

    $partOneComparisonResults[] = compareArrays( ...$pair );
}

$firstPartResult = 0;

foreach ( $partOneComparisonResults as $key => $value ) {

    if( $value > 0 ) {

        $firstPartResult += $key +1;
    }
}
echo '<b>' . $firstPartResult . '</b><br />';

// /* --- Part Two --- */

$secondPartData = array_filter( $data, fn($el) => $el !== '');

foreach( $secondPartData as &$line ) {

    $line = json_decode( $line );

} 
$secondPartData[] = [[2]];
$secondPartData[] = [[6]];


usort( $secondPartData, fn( $a, $b ) => compareArrays( $b, $a ) );

$secondPartResult =  ( array_search( [[2]], $secondPartData ) + 1 ) * ( array_search( [[6]], $secondPartData ) + 1 );
echo '<b>' . $secondPartResult . '</b><br />';

function compareArrays( array $a, array $b ) {
    $index = 0;

    while( true ) {

        if( isset( $a[$index] ) && isset( $b[$index] ) ) {

            if( is_int( $a[$index] ) && is_int( $b[$index] ) ) {

                if( $a[$index] < $b[$index] ) return 1;

                if( $a[$index] > $b[$index] ) return -1;

            } elseif( is_array( $a[$index] ) && is_array( $b[$index] ) ) {

                $compareAB =  compareArrays( $a[$index], $b[$index] );

                if( $compareAB !== 0 ) return $compareAB;

            } elseif( is_int( $a[$index] ) ) {

                $compareAB = compareArrays( array( $a[$index] ), $b[$index] );

                if( $compareAB !== 0 ) return $compareAB;

            } else {

                $compareAB = compareArrays( $a[$index], array($b[$index]) );

                if( $compareAB !== 0 ) return $compareAB;
            }

            $index++; 

        } elseif( isset( $a[$index] ) && ! isset( $b[$index] ) ) {

            return -1;

        } elseif( ! isset( $a[$index] ) && isset( $b[$index] ) ) {

            return 1;

        } else {

            return 0;
        }
    }
}

