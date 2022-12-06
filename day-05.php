<?php
/* --- Input Processing --- */

$data = file( 'input-05.txt', FILE_IGNORE_NEW_LINES );
$dataLength = count( $data );

$controlFlag = 0;
$startingStacks = [];
$rearrangementProcedure = [];

for ( $i = 0; $i < $dataLength; $i++ ) {
    $line = $data[$i];

    switch( $controlFlag ) {
        case 0:
            for( $k = 1, $j = 0; $k < strlen( $line ); $k += 4, $j++ ) {
                if( !isset( $startingStacks[$j] ) ) {
                    $startingStacks[$j] = '';
                }
                $startingStacks[$j] = ($line[$k] !== " ") ? $line[$k] . $startingStacks[$j] : $startingStacks[$j];
            }
            break;
        case 1:
            $rearrangementProcedure[] = array_values( array_filter( explode(' ', $line ), fn($el) => is_numeric($el) ) );
            break;
    }

    if( isset($data[ $i + 2] ) && $data[$i + 2] === '') {
        $controlFlag = 1;
        $i += 2;
    }
}

$startingStacks2 = $startingStacks;

/* --- Part One --- */

foreach($rearrangementProcedure as [$move, $from, $to] ) {
    $move = (int) $move;
    $from = (int) $from - 1;
    $to = (int) $to - 1;

    for($i = 0; $i < $move; $i++) {
        $startingStacks[$to] .= substr($startingStacks[$from], -1) ;
        $startingStacks[$from] = substr($startingStacks[$from], 0, -1);
    }
}

$firstPartResult = array_reduce( $startingStacks, fn( $acc, $el ) => $acc . substr( $el, -1 ), '' );
echo "<b>$firstPartResult</b><br />";

/* --- Part Two --- */

foreach($rearrangementProcedure as [$move, $from, $to] ) {
    $move = (int) $move;
    $from = (int) $from - 1;
    $to = (int) $to - 1;

        $startingStacks2[$to] .= substr( $startingStacks2[$from], -$move );
        $startingStacks2[$from] = substr( $startingStacks2[$from], 0, -$move );

}

$secondPartResult = array_reduce( $startingStacks2, fn( $acc, $el ) => $acc . substr( $el, -1 ), '');
echo "<b>$secondPartResult</b><br />";