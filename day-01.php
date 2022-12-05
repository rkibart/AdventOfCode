<?php

$data = file( 'input-01.txt', FILE_IGNORE_NEW_LINES );

$data[] = '';

$dataSize = count( $data );

/* --- Part One --- */

$firstPartResult = 0;
$elfCalories = 0;

for($i = 0; $i < $dataSize; $i++ ) {

    if( $data[$i] !== '' ) {
        $elfCalories += (int) $data[$i];

    } else {
        
        $firstPartResult = $elfCalories > $firstPartResult ? $elfCalories : $firstPartResult;
        $elfCalories = 0;
    }
}

echo "<b>$firstPartResult</b><br />";

/* --- Part Two --- */

$topThreeElves = [0, 0, 0];
$elfCalories = 0;

for($i = 0; $i < $dataSize ; $i++ ) {

    if( $data[$i] !== '' ) {

        $elfCalories += (int) $data[$i];

    } else {
        
        $topThreeElves[0] = $elfCalories > $topThreeElves[0] ? $elfCalories : $topThreeElves[0];
        sort( $topThreeElves );
        $elfCalories = 0;

    }
}

$secondPartResult = array_sum( $topThreeElves );

echo "<b>$secondPartResult</b><br />";