<?php


function load_extension($extension){
	if (!extension_loaded($extension)) {
		$prefix = (PHP_SHLIB_SUFFIX === 'dll') ? 'php_' : '';
		$done = dl_local($prefix . $extension.'.' . PHP_SHLIB_SUFFIX, LOCAL_EXTENSION_DIR );
		return ($done === false) ? false : true;
	}else{
		return false;
	}
}

/*
    Function: dl_local()
    Reference: http://us2.php.net/manual/en/function.dl.php
    Author: Brendon Crawford <endofyourself |AT| yahoo>
    Usage: dl_local( "mylib.so" , 'path/');
    Returns: Extension Name (NOT the extension filename however)
    NOTE:
        This function can be used when you need to load a PHP extension (module,shared object,etc..),
        but you do not have sufficient privelages to place the extension in the proper directory where it can be loaded. This function
        will load the extension from the CURRENT WORKING DIRECTORY only.
        If you need to see which functions are available within a certain extension,
        use "get_extension_funcs()". Documentation for this can be found at
        "http://us2.php.net/manual/en/function.get-extension-funcs.php".
*/

function dl_local( $extensionFile,  $currentDir ) {
    //make sure that we are ABLE to load libraries
    if( !(bool)ini_get( "enable_dl" ) || (bool)ini_get( "safe_mode" ) ) {
     echo( "dh_local(): Loading extensions is not permitted.\n" );
	return false;
    }

     //check to make sure the file exists
    if( !file_exists( $extensionFile ) ) {
     echo( "dl_local(): File '$extensionFile' does not exist.\n" );
	return false;
    }
   
    //check the file permissions
    if( !is_executable( $extensionFile ) ) {
     echo( "dl_local(): File '$extensionFile' is not executable.\n" );
	return false;
    }

 //we figure out the path
 $currentExtPath = ini_get( "extension_dir" );
 $subDirs = preg_match_all( "/\//" , $currentExtPath , $matches );
 unset( $matches );
 
     //lets make sure we extracted a valid extension path
    if( !(bool)$subDirs ) {
     echo( "dl_local(): Could not determine a valid extension path [extension_dir].\n" );
	return false;
    }
 
 $extPathLastChar = strlen( $currentExtPath ) - 1;
 
    if( $extPathLastChar == strrpos( $currentExtPath , "/" ) ) {
     $subDirs--;
    }

 $backDirStr = "";
     for( $i = 1; $i <= $subDirs; $i++ ) {
     $backDirStr .= "..";
        if( $i != $subDirs ) {
         $backDirStr .= "/";
        }
    }

 //construct the final path to load
 $finalExtPath = $backDirStr . $currentDir . $extensionFile;
 
    //now we execute dl() to actually load the module
     if( !dl( $finalExtPath ) ) {
     return false;
    }

 //if the module was loaded correctly, we must bow grab the module name
 $loadedExtensions = get_loaded_extensions();
 $thisExtName = $loadedExtensions[ sizeof( $loadedExtensions ) - 1 ];
 
 //lastly, we return the extension name
  return $thisExtName;

}//end dl_local() 

?>
