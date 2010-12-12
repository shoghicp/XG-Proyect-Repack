<?php

##############################################################################
# *																			 #
# * XG PROYECT																 #
# *  																		 #
# * @copyright Copyright (C) 2008 - 2009 By lucky from xgproyect.net      	 #
# *																			 #
# *																			 #
# *  This program is free software: you can redistribute it and/or modify    #
# *  it under the terms of the GNU General Public License as published by    #
# *  the Free Software Foundation, either version 3 of the License, or       #
# *  (at your option) any later version.									 #
# *																			 #
# *  This program is distributed in the hope that it will be useful,		 #
# *  but WITHOUT ANY WARRANTY; without even the implied warranty of			 #
# *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the			 #
# *  GNU General Public License for more details.							 #
# *																			 #
##############################################################################

define('INSIDE'  , true);
define('INSTALL' , false);
define('IN_ADMIN', true);

$xgp_root = './../';
include($xgp_root . 'extension.inc.php');
include($xgp_root . 'common.' . $phpEx);

if ($user['authlevel'] < 3) die(message ($lang['404_page']));

/*include_once ($xgp_root . 'includes/classes/class.gitbase.php');
include_once ($xgp_root . 'includes/classes/class.git.php');
include_once ($xgp_root . 'includes/classes/class.gitcheckout.php');
include_once ($xgp_root . 'includes/classes/class.gitclone.php');

include_once ($xgp_root . 'includes/classes/class.http.php');


final class GitHttpClone extends GitClone
{
    private $_http;


    protected function initMod()
    {
        $http = & $this->_http;
        $http = new http_class;

        $http->timeout     = 30;
        $http->user_agent  = "PHPGit/1.0 (http://cesar.la/projects/phpgit)";
        $http->prefer_curl = 0;
    }


    protected function wgetFile($file)
    {
        $http  = & $this->_http;
        $url   = $this->url."/".$file;
        $error = $http->GetRequestArguments($url, $arguments);
        if ($error!="") {
            $this->throwException($error);
        }
        $error = $http->Open($arguments);
        if ($error!="") {
            $this->throwException($error);
        }
        $error = $http->SendRequest($arguments);
        if ($error!="") {
            $this->throwException($error);
        }
        $error = $http->ReadReplyHeaders($headers);
        if ($error!="") {
            $this->throwException($error);
        }
        if ($http->response_status != 200) {
            $http->Close();
            $error = "Page not found $url";
            $this->throwException($error);
        }

        $content = "";
        while (true) {
            $error = $http->ReadReplyBody($body, 1000);
            if ($error!="" || strlen($body) == 0) {
                if ($error!="") {
                    $this->throwException($error);
                }
                break;
            }
            $content .= $body;
        }
        if (strlen($content) != $headers['content-length']) {
            $this->throwException("Mismatch size");
        }
        $http->Close();
        return $content;
    }
}


$phplibtextcat = new GitHttpClone($xgp_root. 'includes/classes/');
$phplibtextcat->setRepoURL(GIT_REPO);
$phplibtextcat->setRepoPath($xgp_root);
try {
    $phplibtextcat->doClone();
} catch(Exception $e) {
    message("Se ha encontrado un error:<br> ".$e->getMessage());
        
}
*/
if (is_writable($xgp_root . 'install.zip')) {

    if (!$gestor = fopen($xgp_root.'install.zip', 'w')) {
    	message("No se puede guardar el archivo de instalacion", "OverviewPage.php", 4);
    }

    // Escribir $contenido a nuestro archivo abierto.
    if (fwrite($gestor, getRemoteFile(GET_REPO)) === FALSE) {
   	 message("No se puede guardar el archivo de instalacion", "OverviewPage.php", 4);

    }
    fclose($gestor);

} else {
    message("No se puede guardar el archivo de instalacion, no hay permisos de escritura", "OverviewPage.php", 4);
}

$zip = zip_open($xgp_root . 'install.zip');
if ($zip) {
  while ($zip_entry = zip_read($zip)) {
    $fp = fopen($xgp_root .zip_entry_name($zip_entry), "w");
    if (zip_entry_open($zip, $zip_entry, "r")) {
      $buf = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
      fwrite($fp,"$buf");
      zip_entry_close($zip_entry);
      fclose($fp);
    }
  }
  zip_close($zip);
}


?>
