<?php

$letters = array_flip( range('a', 'z') );

$data = file( 'input-12.txt', FILE_IGNORE_NEW_LINES );
foreach( $data as &$line ) {

    $line = str_split( $line, 1);
}
$dataSize = count( $data );

/* --- Part One --- */

$elevations = [];
$visitedArr = [];
$distanceArr = [];
$startPoint = [];
$endPoint = [];

for( $i = 0; $i < $dataSize; $i++ ) {
    for( $j = 0; $j < count( $data[$i] ); $j++ ) {

        if( $data[$i][$j] === 'S' ) {
            $startPoint = [$i, $j];
            $data[$i][$j] = 'a';
        }
        if( $data[$i][$j] === 'E' ) {
            $endPoint = [$i, $j];
            $data[$i][$j] = 'z';
        }
        $elevations[$i][$j] = $letters[$data[$i][$j]];
        $visitedArr[$i][$j] = false;
        $distanceArr[$i][$j] = INF;
    }
}

$searchList[] = $startPoint;
$distanceArr[$startPoint[0]][$startPoint[1]] = 0;

while ( count( $searchList ) > 0 ) {

    $curentNode = array_shift( $searchList );

    $children = neighbours( $curentNode, $elevations );

    foreach( $children as $child ) {

        if( ! $visitedArr[$child[0]][$child[1]] ) {

            $distance = distance( $curentNode , $child, $elevations );

            if( in_array( $child, $searchList ) ) {

                if ( $distanceArr[$curentNode[0]][$curentNode[1]] + $distance < $distanceArr[$child[0]][$child[1]]) {

                    $distanceArr[$child[0]][$child[1]] = $distanceArr[$curentNode[0]][$curentNode[1]] + $distance;
                }
  
            } else {
                $distanceArr[$child[0]][$child[1]] = $distanceArr[$curentNode[0]][$curentNode[1]] + $distance;
                $searchList[] = $child;
            }
        }
    }
    usort( $searchList, fn($a, $b) => $distanceArr[$a[0]][$a[1]] <=> $distanceArr[$b[0]][$b[1]] );

    $visitedArr[$curentNode[0]][$curentNode[1]] = true;
}

$firstPartResult = $distanceArr[$endPoint[0]][$endPoint[1]];
echo '<b>' . $firstPartResult . '</b><br />';

// /* --- Part Two --- */

for( $i = 0; $i < $dataSize; $i++) {
    for( $j = 0; $j < count( $data[$i] ); $j++ ) {

        $visitedArr[$i][$j] = false;
        $distanceArr[$i][$j] = INF;
    }
}

$searchList = [];
$searchList[] = $endPoint;
$distanceArr[$endPoint[0]][$endPoint[1]] = 0;
$firstA = true;
$secondPartResult = 0;

while ( count( $searchList) > 0 && $firstA ) {

    $curentNode = array_shift( $searchList );

    if( $elevations[$curentNode[0]][$curentNode[1]] === 0 ) {

        $firstA = false;
        $secondPartResult = $distanceArr[$curentNode[0]][$curentNode[1]];
    }

    $children = neighbours( $curentNode, $elevations );

    foreach( $children as $child ) {

        if( ! $visitedArr[$child[0]][$child[1]] ) {

            $distance = distancePart2( $curentNode , $child, $elevations );

            if( in_array( $child, $searchList ) ) {

                if ( $distanceArr[$curentNode[0]][$curentNode[1]] + $distance < $distanceArr[$child[0]][$child[1]] ) {

                    $distanceArr[$child[0]][$child[1]] = $distanceArr[$curentNode[0]][$curentNode[1]] + $distance;
                }
                 
            } else {
                $distanceArr[$child[0]][$child[1]] = $distanceArr[$curentNode[0]][$curentNode[1]] + $distance;
                $searchList[] = $child;
            }
            
        }
    }
    usort( $searchList, fn($a, $b) => $distanceArr[$a[0]][$a[1]] <=> $distanceArr[$b[0]][$b[1]] );

    $visitedArr[$curentNode[0]][$curentNode[1]] = true;
    
}

echo '<b>' . $secondPartResult . '</b><br />';

function neighbours( array $node, array $array ) {

    [$i, $j] = $node;

    $neighbours = [];

    if( isset( $array[$i - 1] ) && isset( $array[$i - 1][$j]) ) $neighbours[] = [$i - 1, $j];
    if( isset( $array[$i + 1] ) && isset( $array[$i + 1][$j]) ) $neighbours[] = [$i + 1, $j];
    if( isset( $array[$i] ) && isset( $array[$i][$j - 1]) ) $neighbours[] = [$i, $j - 1];
    if( isset( $array[$i] ) && isset( $array[$i][$j + 1]) ) $neighbours[] = [$i, $j + 1];

    return $neighbours;
}

function distance( array $point1, array $point2, array $weightsArr) {

    $distance  = $weightsArr[$point2[0]][$point2[1]] - $weightsArr[$point1[0]][$point1[1]] ;

    return $distance > 1 ? INF : 1;
}

function distancePart2( array $point1, array $point2, array $weightsArr) {

    $distance  = $weightsArr[$point2[0]][$point2[1]] - $weightsArr[$point1[0]][$point1[1]] ;

    return $distance < - 1 ? INF : 1;
}
