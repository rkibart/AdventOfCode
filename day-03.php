<?php
$data = file( 'input-03.txt', FILE_IGNORE_NEW_LINES );
$dataLength = count( $data );
$priorityKeys = array_combine( array_merge( range('a', 'z'), range('A', 'Z') ), range( 1, 52 ) );

/* --- Part One --- */

$firstPartResult = 0;
$sharedItemsQuantity = [];

foreach($priorityKeys as $key => $value) {
    $sharedItemsQuantity[$key] = 0;
    $sharedItemsQuantityPartTwo[$key] = 0;
} 

foreach( $data as $rucksack ) {
    [$firstCompartment, $secondCompartment] = str_split( $rucksack, strlen( $rucksack) / 2 );
    $checkedItems = [];

    foreach( str_split($firstCompartment ) as $char )  {
        if( ! in_array( $char, $checkedItems ) ) {
            if(strpos( $secondCompartment, $char ) !== false ) {
                $sharedItemsQuantity[$char]++;
            }
        }
        $checkedItems[] = $char;
    }
}

foreach ( $sharedItemsQuantity as $item => $quantity ) {
    $priority = $priorityKeys[$item];
    $firstPartResult += $quantity * $priority;    
}

echo "<b>$firstPartResult</b><br />";

/* --- Part Two --- */

$secondPartResult = 0;

for($i = 0; $i < $dataLength; $i += 3 ) {

    $firstElfRucksuck = str_split( $data[$i] );
    $checkedItems = [];
    foreach( $firstElfRucksuck as $char ) {
        if( ! in_array( $char, $checkedItems ) ) {
            if( strpos( $data[$i + 1], $char ) !== false && strpos( $data[$i +2], $char ) !== false ) {

                $sharedItemsQuantityPartTwo[$char]++;
            }
        }
        $checkedItems[] = $char;
    }
}

foreach ( $sharedItemsQuantityPartTwo as $item => $quantity ) {
    $priority = $priorityKeys[$item];
    $secondPartResult += $quantity * $priority;    
}

echo "<b>$secondPartResult</b><br />";