<?php
$commands = file( 'input-07.txt', FILE_IGNORE_NEW_LINES );
$commandsSize = count( $commands );

$root = new MyDirectory( 'root', null );
$currentDirrectory = $root;

$i = 0;
while( $i < $commandsSize ) {

    $commandParts = explode( ' ', $commands[$i] );

    if( $commandParts[0] === '$' ) {
        if( $commandParts[1] === 'cd' ) {
            switch( $commandParts[2] ) {
                case '/':
                    $currentDirrectory = $root;
                    break;
                case '..':
                    $currentDirrectory = $currentDirrectory->getParrentDirectory();
                    break;
                default:
                    $currentDirrectory = $currentDirrectory->getChildDir( $commandParts[2] );
            }
        }
    } else {
        if( is_numeric( $commandParts[0] ) ) {
            $currentDirrectory->addFile( new MyFile( $commandParts[1], (int) $commandParts[0] ) );
        } else {
            $currentDirrectory->addChildDir( new MyDirectory( $commandParts[1], $currentDirrectory ) );
        }
    }
    $i++;
}

$sizes = [];
getRootDir( $currentDirrectory )->calcDirSizeAndLog();

/* --- Part One --- */

$firstPartResult = array_sum( array_filter( $sizes, fn($size) => $size < 100000 ) );
echo "<b>$firstPartResult</b><br />";

/* --- Part Two --- */

$secondPartResult = rsort($sizes, SORT_NUMERIC);
$unsedSpace = 70000000 - $sizes[0];

$i = 0;
while( $unsedSpace + $sizes[$i] > 30000000 ) {
    $i++;
}
$secondPartResult = $sizes[$i - 1];
echo "<b>$secondPartResult</b><br />";

function getRootDir( MyDirectory $obj ): MyDirectory {
    $that = $obj;
    while( $that->getParrentDirectory() !== null ) {
        $that = $that->getParrentDirectory();
    }
    return $that;
}

class MyDirectory
{
    private string $name;
    private ?MyDirectory $parentDir;
    private array $childDirs;
    private array $files;
    
    function __construct( string $name, ?MyDirectory $parentDir ) {
        $this->name = $name;
        $this->parentDir = $parentDir;
        $this->childDirs = [];
        $this->files = [];
    }

    function addChildDir( MyDirectory $dirObj ): void {
        $this->childDirs[] = $dirObj;
    }

    function getChildDir( string $dirName ): MyDirectory {
        $result = array_filter( $this->childDirs, fn($el) => $el->name === $dirName );
        return array_values( $result )[0];
    }

    function addFile( MyFile $file ): void {
        $this->files[] = $file;
    }
    
    public function calcDirSizeAndLog(): int {
        global $sizes;
        $size = 0;
        foreach( $this->childDirs as $childDir) {
            $size += $childDir->calcDirSizeAndLog();
        }
        foreach( $this->files as $file ) {
            $size += $file->getSize(); 
        }
        $sizes[] = $size;
        return $size;
    }

    public function getParrentDirectory(): ?MyDirectory {
        return $this->parentDir;
    }
}

class MyFile
{
    private string $name;
    private int $size;

    function __construct( string $name, int $size ) {
        $this->name = $name;
        $this->size = $size;
    }
    
    public function getSize(): int {
        return $this->size;
    }
    public function getName(): string {
        return $this->name;
    }
}





