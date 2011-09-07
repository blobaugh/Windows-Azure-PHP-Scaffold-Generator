<?php




$cmdparams = getCmdParams();

/*
 * Ensure all parameters needed are set to something
 */
checkParams();


rcopy(__DIR__ . "/Scaffold", $cmdparams['out']);

updateScaffoldName();

if($cmdparams['in'] != false) {
    rcopy($cmdparams['in'], $cmdparams['out'] . "/resources/WebRole/");
}

function updateScaffoldName() {
    global $cmdparams;
    
    // Update index.php
    $contents = file_get_contents($cmdparams['out'] . "/index.php");
    $contents = str_replace('{{ScaffoldName}}', $cmdparams['name'], $contents);
    file_put_contents($cmdparams['out'] . "/index.php", $contents);
}


/**
 * Returns all the parameters passed by the command line as key/value pairs.
 * If a flag is used (param with no value) it will be set to true
 * 
 * @global Array $argv
 * @return Array 
 */
function getCmdParams() {
    global $argv;
   
    $params = array();
    for($i = 0; $i < count($argv); $i++) {
        if(substr($argv[$i], 0, 1) == '-') {
            if($i <= count($argv)-2 && substr($argv[($i + 1)], 0, 1) != '-') { 
                // Next item is flag
                $value = $argv[$i + 1];
            } else {
                $value = "true";
            }
            $key = str_replace("-", "", $argv[$i]);
            $params[$key] = $value;
        }
    }
    return $params;
}

function displayHelp() {
    echo "\nSimple packaging and deployment tool for PHP project on Windows Azure";
    echo "\n\nOriginally developed by Ben Lobaugh 2011 <ben@lobaugh.net>";
    echo "\n\nParameters:";
    echo "\n\thelp - Display this menu";
    echo "\n\t[in] - Optional, Source of PHP project to put inside scaffold";
    echo "\n\tout - Output of Windows Azure package";
    echo "\n\tname - Name of new scaffold";
    echo "\n\n\nSee https://github.com/blobaugh/Windows-Azure-PHP-Scaffold-Generator for documentation\n";
}

function checkParams() {
    global $cmdparams, $argv;
    
    if(isset($cmdparams['help']) || count($argv) < 2) {
        displayHelp();
        exit();
    }
    
    if(!isset($cmdparams['in'])) {
        $cmdparams['in'] = false;
    } else if(isset($cmdparams['in']) && !is_dir($cmdparams['in'])) {
        echo "\n\nInput directory does not exist\n";
        displayHelp();
        exit();
    }

    if(!isset($cmdparams['out'])) {
        echo "\n\nMissing or invalid -out parameter\n";
        displayHelp();
        exit();
    }
    
    if(!isset($cmdparams['name'])) {
        echo "\n\nMissing or invalid -name parameter\n";
        displayHelp();
        exit();
    }
}


/**
 * Recursively copy files from one directory to another
 *
 * @param String $src - Source of files being moved
 * @param String $dest - Destination of files being moved
 */
function rcopy($src, $dest){
 
    // If source is not a directory stop processing
    if(!is_dir($src)) return false;
 
    // If the destination directory does not exist create it
    if(!is_dir($dest)) {
        if(!mkdir($dest)) {
            // If the destination directory could not be created stop processing
            return false;
        }
    }
 
    // Open the source directory to read in files
    $i = new DirectoryIterator($src);
    foreach($i as $f) {
        if($f->isFile()) {
            copy($f->getRealPath(), "$dest/" . $f->getFilename());
        } else if(!$f->isDot() && $f->isDir()) {
            rcopy($f->getRealPath(), "$dest/$f");
        }
    }
}

/**
 * Recursively creates a directory structure
 * 
 * @param String $path 
 */
function rmkdir($path) {
    $path = str_replace("\\", "/", $path);
    $path = explode("/", $path);
    
    $rebuild = '';
    foreach($path AS $p) {
        
        if(strstr($p, ":") != false) { 
            //echo "\nFound : in $p\n";
            $rebuild = $p;
            continue;
        }
        $rebuild .= "/$p";
        //echo "Checking: $rebuild\n";
        if(!is_dir($rebuild)) mkdir($rebuild);
    }
}