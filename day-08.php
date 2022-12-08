<?php
$data = file( 'input-08.txt', FILE_IGNORE_NEW_LINES );
foreach( $data as &$row ) {
    $row = str_split( $row, 1 );
}
unset( $row );

$columnSize = count( $data );
$rowSize = count( $data[0] );

// plant the forest
for( $i = 0; $i < $columnSize; $i++ ) {    
    for( $j = 0; $j < $rowSize; $j++ ) {
        $data[$i][$j] = new Tree ( $data[$i][$j] ); 
    }
}

/* --- Part One --- */

// examine forest
for( $i = 0; $i < $columnSize; $i++ ) {

    // visibility from left
    $tempTallestTree = $data[$i][0]->getHeight();
    $data[$i][0]->visibleLeft = true;

    for( $j = 0; $j < $rowSize ; $j++ ) {
        $treeHeight = $data[$i][$j]->getHeight();
        if ( $treeHeight > $tempTallestTree ) {
            $tempTallestTree = $treeHeight;
            $data[$i][$j]->visibleLeft = true;
            if ( $treeHeight === 9 ) {
                break;
            }
        }
    }

    // visibility from right
    $tempTallestTree = $data[$i][$rowSize - 1]->getHeight();
    $data[$i][$rowSize - 1]->visibleRight = true;

    for( $j = $rowSize - 1; $j >= 0 ; $j-- ) {
        $treeHeight = $data[$i][$j]->getHeight();
        if ( $treeHeight > $tempTallestTree ) {
            $tempTallestTree = $treeHeight;
            $data[$i][$j]->visibleRight = true;
            if ( $treeHeight === 9 ) {
                break;
            }
        }
    }
}

for( $j = 0; $j < $rowSize; $j++ ) {
    
    // visibility from top
    $tempTallestTree = $data[0][$j]->getHeight();
    $data[0][$j]->visibleTop = true;

    for( $i = 0; $i < $columnSize; $i++ ) {
        $treeHeight = $data[$i][$j]->getHeight();
        if ( $treeHeight > $tempTallestTree ) {
            $tempTallestTree = $treeHeight;
            $data[$i][$j]->visibleTop = true;
            if ( $treeHeight === 9 ) {
                break;
            }
        }
    }

    // visibility from bottom
    $tempTallestTree = $data[$columnSize - 1][$j]->getHeight();
    $data[$columnSize - 1][$j]->visibleBottom = true;

    for( $i = $rowSize - 1; $i >= 0; $i-- ) {
        $treeHeight = $data[$i][$j]->getHeight();
        if ( $treeHeight > $tempTallestTree ) {
            $tempTallestTree = $treeHeight;
            $data[$i][$j]->visibleBottom = true;
            if ( $treeHeight === 9 ) {
                break;
            }
        }
    }
}


$firstPartResult = array_sum( array_map( fn($row) => count( array_filter( $row, fn($el) => $el->isVisible() ) ), $data ) ) ;
echo "<b>$firstPartResult</b><br />";

/* --- Part Two --- */

for($i = 0; $i < $columnSize; $i++ ) {

    for( $j = 0; $j < $rowSize; $j++ ) {

        $step = 1;
        while( isset( $data[$i][$j - $step] ) ) {
            $data[$i][$j]->viewingDistanceLeft = $step;
            if( $data[$i][$j - $step]->getHeight() >= $data[$i][$j]->getHeight()) {
                break;
            }
            $step++;
        }
        $step = 1;
        while( isset( $data[$i][$j + $step] ) ) {
            $data[$i][$j]->viewingDistanceRight = $step;
            if( $data[$i][$j + $step]->getHeight() >= $data[$i][$j]->getHeight()) {
                break;
            }
            $step++;
        }
        $step = 1;
        while( isset( $data[$i - $step][$j] ) ) {
            $data[$i][$j]->viewingDistanceTop = $step;
            if( $data[$i-$step][$j]->getHeight() >= $data[$i][$j]->getHeight()) {
                break;
            }
            $step++;
        }
        $step = 1;
        while( isset( $data[$i + $step][$j] ) ) {

            $data[$i][$j]->viewingDistanceBottom = $step;
            if( $data[$i+$step][$j]->getHeight() >= $data[$i][$j]->getHeight()) {
                break;
            }
            $step++;
        }
    }
}

$secondPartResult = max( array_map( fn($row) => max( array_map( fn($el) => $el->getViewingDistance(), $row ) ), $data ) ) ;
echo "<b>$secondPartResult</b><br />";

class Tree
{
    private int $height;
    public ?bool $visibleLeft;
    public ?bool $visibleRight;
    public ?bool $visibleTop;
    public ?bool $visibleBottom;

    public int $viewingDistanceLeft;
    public int $viewingDistanceRight;
    public int $viewingDistanceTop;
    public int $viewingDistanceBottom;

    function __construct( string $height ) {
        $this->height = (int) $height;
        $this->visibleLeft = null;
        $this->visibleRight = null;
        $this->visibleTop = null;
        $this->visibleBottom = null;
        $this->viewingDistanceLeft = 0;
        $this->viewingDistanceRight = 0;
        $this->viewingDistanceTop = 0;
        $this->viewingDistanceBottom = 0;
    }
    
    public function getHeight(): int {
        return $this->height;
    }
    public function isVisible(): bool {
        return $this->visibleLeft || $this->visibleRight || $this->visibleTop || $this->visibleBottom;
    }

    public function getViewingDistance(): int {
            return $this->viewingDistanceLeft * $this->viewingDistanceRight * $this->viewingDistanceTop * $this->viewingDistanceBottom;
    }

}