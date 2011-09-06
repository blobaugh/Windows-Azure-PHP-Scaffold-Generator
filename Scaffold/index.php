<?php
set_time_limit(600);


require_once('Params.class.php');
/** 
 * @command-handler Scaffold
 */ 
class Scaffold
	extends Microsoft_WindowsAzure_CommandLine_PackageScaffolder_PackageScaffolderAbstract
{
    
    
            // this should be in parent
        protected $p;
        
        /**
         * Full path to Document Root
         * @var String
         */
        protected $mAppRoot;
        
        /**
         * Path to scaffolder file
         * @var String
         */
        protected $mScaffolder;
        
        protected $mRootPath;
        
        // this could be
        public function parameters() {
                $this->p = new Params(); 
                
                
                /*
                 * Example of a command line parameter
                 * 
                 * $this->p->add('cmd_param_name', required(true|false), default value, help message string);
                 */               
                
                
                 
                 $this->p->verify(); 

        }
    
    
    
    
    
    
    
    
    
    
	/**
	 * Runs a scaffolder and creates a Windows Azure project structure which can be customized before packaging.
	 * 
	 * @command-name Run
	 * @command-description Runs the scaffolder.
	 * 
	 * @command-parameter-for $scaffolderFile Argv --Phar Required. The scaffolder Phar file path. This is injected automatically.
	 * @command-parameter-for $rootPath Argv|ConfigFile --OutputPath|-out Required. The path to create the Windows Azure project structure. This is injected automatically. 

	 *
	 */
	public function runCommand($scaffolderFile, $rootPath)	{
                /**
                 * DO NOT REMOVE BETWEEN BELOW COMMENT
                 */
                $this->mAppRoot = realpath($rootPath) . "\WebRole";
                $this->mScaffolder = $scaffolderFile;
                $this->mRootPath = $rootPath;
                $this->parameters();
                                
                $this->extractPhar();
                
                unlink($this->mRootPath . "/Params.class.php");
		/**
                 * DO NOT REMOVE BETWEEN ABOVE COMMENT
                 */
	}
        
        private function updateServiceConfig() {
            $this->log("Updating ServiceConfiguration.cscfg\n");
             $contents = file_get_contents($this->mRootPath . "/ServiceConfiguration.cscfg");
             $values = $this->p->valueArray();
            foreach ($values as $key => $value) {
                    $contents = str_replace('$' . $key . '$', $value, $contents);
            }
          
            file_put_contents($this->mRootPath . "/ServiceConfiguration.cscfg", $contents);
       
        
        }
        private function extractPhar() {
            	// Load Phar
		$phar = new Phar($this->mScaffolder);
		
		// Extract to disk
		$this->log("Extracting resources...\n");
		$this->createDirectory($this->mRootPath);
		$this->extractResources($phar, $this->mRootPath);
		$this->log("Extracted resources.\n");
                
        }
        
        private function move($src, $dest){
    
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
                    rename($f->getRealPath(), "$dest/" . $f->getFilename());
                } else if(!$f->isDot() && $f->isDir()) {
                    $this->move($f->getRealPath(), "$dest/$f");
                    @unlink($f->getRealPath());
                }
            }
            @unlink($src);
        }
        
        private function unzip($file, $destFolder) {
            $zip = new ZipArchive();
            if($zip->open($file) === true) {
                $zip->extractTo("$destFolder");
                $zip->close();
            } else {
                echo "Failed to open archive";
            }
        }
        
        private function curlFile($url, $destFolder) {
            $options = array(
                CURLOPT_RETURNTRANSFER => true,     // return web page
                CURLOPT_HEADER         => false,    // don't return headers
                CURLOPT_FOLLOWLOCATION => true,     // follow redirects
                CURLOPT_ENCODING       => "",       // handle all encodings
                CURLOPT_USERAGENT      => "blob curler 1.2", // who am i
                CURLOPT_AUTOREFERER    => true,     // set referer on redirect
                CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
                CURLOPT_TIMEOUT        => 120,      // timeout on response
                CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
            );

            $ch      = curl_init( $url );
            curl_setopt_array( $ch, $options );
            $content = curl_exec( $ch );
            $err     = curl_errno( $ch );
            $errmsg  = curl_error( $ch );
            $header  = curl_getinfo( $ch );
            curl_close( $ch );

            $header['errno']   = $err;
            $header['errmsg']  = $errmsg;
            $header['content'] = $content;
            
            $file = explode("/", $url);
            $file = $file[count($file)-1];
            $this->log("Writing file $destFolder/$file");
            file_put_contents("$destFolder/$file", $header['content']);
            return "$destFolder/$file";
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
