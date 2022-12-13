<?php
$data = file( 'input-11-01.txt', FILE_IGNORE_NEW_LINES );
$dataSize = count( $data );

$monkeysPartOne = [];
$monkeysPartTwo = [];

for( $i = 0; $i < $dataSize; $i++ ) {

    if( str_contains( $data[$i], 'Monkey') ) {

        preg_match_all( '/\d+/', $data[$i + 1], $startingItems );
        
        foreach( $startingItems[0] as &$item ) {
            $item = (int) $item;
        }

        $operation = substr( $data[$i + 2], 13 );
        preg_match( '/\d+/', $data[$i + 3], $testDivisibleBy);
        $testDivisibleBy = (int) $testDivisibleBy[0];
        preg_match( '/\d+/', $data[$i + 4], $monkeyIfTestPassed);
        $monkeyIfTestPassed = (int) $monkeyIfTestPassed[0];
        preg_match( '/\d+/', $data[$i + 5], $monkeyIfTestFailed);
        $monkeyIfTestFailed = (int) $monkeyIfTestFailed[0];

        $monkeysPartOne[] = new Monkey($startingItems[0], $operation, $testDivisibleBy, $monkeyIfTestPassed, $monkeyIfTestFailed);
        $monkeysPartTwo[] = new Monkey($startingItems[0], $operation, $testDivisibleBy, $monkeyIfTestPassed, $monkeyIfTestFailed);
        
    }
}

$worryModulo = array_reduce( $monkeysPartTwo, fn( $acc, $el) => $acc = $acc * $el->getDivisibleBy(), 1);

foreach ( $monkeysPartTwo as $monkey  ) {

    $monkey->setWorryModulo($worryModulo);
}

/* --- Part One --- */

for( $round = 0; $round < 20; $round++ ) {

    foreach( $monkeysPartOne as $monkey ) {

        while( $testResult = $monkey->inspectItem() ) {

            $monkeysPartOne[$testResult[0]]->addItem($testResult[1]);
        }

    }
}
$firstPartResult =  array_map( fn( $monkey ) => $monkey->getInspectsCounter(), $monkeysPartOne );

rsort($firstPartResult);

echo '<b>' . $firstPartResult[0] * $firstPartResult[1] . '</b><br />';

/* --- Part Two --- */

for( $round = 0; $round < 10000; $round++ ) {

    foreach( $monkeysPartTwo as $monkey ) {

        while( $testResult = $monkey->inspectItemPartTwo() ) {

            $monkeysPartTwo[$testResult[0]]->addItem($testResult[1]);
        }

    }
}

$secondPartResult =  array_map( fn( $monkey ) => $monkey->getInspectsCounter(), $monkeysPartTwo );

rsort($secondPartResult);

echo '<b>' . $secondPartResult[0] * $secondPartResult[1] . '</b><br />';

class Monkey
{
    private string $operationStr;
    private int $inspectsCounter;
    private ?int $worryModulo;
    function __construct(
        private array $items,
        string $operationStr,
        private int $testDivisibleBy,
        private int $monkeyIfTestPassed,
        private int $monkeyIfTestFailed,
    ) {
        
        $this->operationStr = preg_replace(['/old/', '/new/'], ['$old', '$new'], $operationStr );
        $this->inspectsCounter = 0;
        $this->worryModulo = null;
    }

    public function addItem( int $item ): void {

        $this->items[] = $item;
    }

    public function inspectItem( ): bool|array {

        if( count( $this->items) === 0 ) {
            return false;
        }
        $this->inspectsCounter++;
        $item = array_shift( $this->items );
        $worryLabel = $this->operation( $item );
        $worryLabel = floor( $worryLabel / 3 );

        if( $worryLabel % $this->testDivisibleBy === 0 ) {

            return [ $this->monkeyIfTestPassed , $worryLabel ];

        } else {

            return [ $this->monkeyIfTestFailed, $worryLabel ];
        }
    }

    public function inspectItemPartTwo( ): bool|array {

        if( count( $this->items) === 0 ) {
            return false;
        }
        $this->inspectsCounter++;
        $item = array_shift( $this->items );
        $worryLabel = $this->operation( $item );
        $worryLabel = $worryLabel % $this->worryModulo;

        if( $worryLabel % $this->testDivisibleBy === 0 ) {

            return [ $this->monkeyIfTestPassed , $worryLabel ];

        } else {

            return [ $this->monkeyIfTestFailed, $worryLabel ];
        }
    }

    public function operation( $old ) {

        $new = null;
        eval( $this->operationStr . ';' );

        return $new;
    }

    public function getInspectsCounter(): int {

        return $this->inspectsCounter;
    }
    public function getDivisibleBy(): int {

        return $this->testDivisibleBy;
    }

    public function setWorryModulo( int $worryModulo ) {

        $this->worryModulo = $worryModulo;
    }
}