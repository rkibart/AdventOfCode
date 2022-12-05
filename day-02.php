<?php
$data = file( 'input-02.txt', FILE_IGNORE_NEW_LINES );

/* --- Part One --- */
$partOneData = [];

foreach( $data as $line ) {
    [$elfBet, $myBet] = explode(' ', $line );
    $partOneData[] = ['elfBet' => transformInput( $elfBet ), 'myBet' => transformInput( $myBet )];
}

$firstPartResult = 0;
$dataLength = count( $partOneData );

for($i = 0; $i < $dataLength; $i++ ) {
    $firstPartResult += bettingRoundScore( $partOneData[$i]['elfBet'], $partOneData[$i]['myBet']); 
}

echo "<b>$firstPartResult</b><br />";

/* --- Part Two --- */
$partTwoData = [];

foreach( $data as $line ) {
    [$elfBet, $myBet] = explode(' ', $line );
    $elfBetTransformed = transformInput( $elfBet );
    $partTwoData[] = ['elfBet' => $elfBetTransformed, 'myBet' => transformSecondInput( $elfBetTransformed, $myBet )];
    
}

$secondPartResult = 0;
$dataLength = count( $partTwoData );

for($i = 0; $i < $dataLength; $i++ ) {
    $secondPartResult += bettingRoundScore( $partTwoData[$i]['elfBet'], $partTwoData[$i]['myBet']); 
}

echo "<b>$secondPartResult</b><br />";

function transformInput( string $str ): int {
    switch( $str ) {
        case 'A':
        case 'X':
            return 0;
            break;
        case 'B':
        case 'Y':
            return 1;
            break;
        case 'C':
        case 'Z':
            return 2;
            break;
    }
}

function transformSecondInput( int $firstInput, string $secondInput ) {
    switch( $secondInput ) {
        case 'X':
            return ( $firstInput + 2 ) % 3;
            break;
        case 'Y':
            return ( $firstInput );
            break;
        case 'Z':
            return ( $firstInput + 1 ) % 3;
            break;
    }
}

function bettingRoundScore(int $elfBet, int $myBet): int {
    if ($elfBet === $myBet ) {
        return $myBet + 1 + 3;
        
    } elseif ( ( $elfBet + 1 ) % 3 === $myBet ) {
        return $myBet + 1 + 6;

    } elseif ( ( $elfBet + 2 ) % 3 === $myBet ) {
        return $myBet + 1 + 0;
    }
}