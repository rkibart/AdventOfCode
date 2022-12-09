<?php
use Ds\Set;

$data = file( 'input-09.txt', FILE_IGNORE_NEW_LINES );
foreach( $data as &$row ) {
    $row = explode( ' ', $row );
}


$ropeWithTenKnots = new RopeWithKnots( 10 );  // including Head
$visitedBySecondKnot = new Set;
$visitedByTenthKnot = new Set;

foreach($data as $move ) {
    $direction = $move[0];
    $steps = (int) $move[1];

    while ( $steps > 0 ) {
        $ropeWithTenKnots->move($direction);
        $visitedBySecondKnot->add( $ropeWithTenKnots->getKnotXY( 2 ) );
        $visitedByTenthKnot->add( $ropeWithTenKnots->getKnotXY( 10 ));
        $steps--;
    }
}

/* --- Part One --- */
$firstPartResult = $visitedBySecondKnot->count();
echo "<b>$firstPartResult</b><br />";

/* --- Part Two --- */
$secondPartResult = $visitedByTenthKnot->count();
echo "<b>$secondPartResult</b><br />";

class RopeWithKnots {

    private int $knotsNumber;
    private array $knotsCoordinates;

    public function __construct( int $knotsNumber) {

        $this->knotsNumber = $knotsNumber;
        $this->knotsCoordinates = array_fill(0, $knotsNumber, [0, 0] );
    }

    public function move (string $direction ) {

        $this->moveHead( $direction );
        
        for( $i = 1; $i < $this->knotsNumber; $i++ ) {
            $this->moveKnot($i);
        }
    }

    private function moveHead( string $direction ) {
        switch( $direction ) {
            case 'L':
                $this->knotsCoordinates[0][0]--;
                break;
            case 'R':
                $this->knotsCoordinates[0][0]++;
                break;
            case 'U':
                $this->knotsCoordinates[0][1]++;
                break;
            case 'D':
                $this->knotsCoordinates[0][1]--;
                break;
        }
    }

    private function moveKnot( int $number) {

        $differenceX =  $this->knotsCoordinates[$number-1][0] - $this->knotsCoordinates[$number][0];
        $differenceY =  $this->knotsCoordinates[$number-1][1] - $this->knotsCoordinates[$number][1];

        if( pow( $differenceX, 2 ) > 1 ) {
            
            switch( $differenceY ) {
                case 1:
                    $this->knotsCoordinates[$number][1]++;
                    break;
                case -1:
                    $this->knotsCoordinates[$number][1]--;
                    break;
            }
            $this->knotsCoordinates[$number][0] = $differenceX > 0 ? $this->knotsCoordinates[$number][0] + 1 : $this->knotsCoordinates[$number][0] - 1;
        }
        if( pow( $differenceY, 2 ) > 1 ) {

            switch( $differenceX ) {
                case 1:
                    $this->knotsCoordinates[$number][0]++;
                    break;
                case -1:
                    $this->knotsCoordinates[$number][0]--;
                    break;
            }
            $this->knotsCoordinates[$number][1] = $differenceY > 0 ? $this->knotsCoordinates[$number][1] + 1 : $this->knotsCoordinates[$number][1] - 1;
        }
    }

    public function getKnotXY( int $knotNumber ): array {
        return [$this->knotsCoordinates[$knotNumber - 1][0], $this->knotsCoordinates[$knotNumber - 1][1]];
    } 
    public function getTailXY(): array {
        return [$this->knotsCoordinates[$this->knotsNumber - 1][0], $this->knotsCoordinates[$this->knotsNumber - 1][1]];
    } 
}