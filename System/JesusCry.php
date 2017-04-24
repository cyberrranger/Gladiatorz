<?php
/**
 * Gladiatorz ist ein Browsergame
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, see <http://www.gnu.org/licenses/>.
 *
 * Verbatim copying and distribution of this entire article is permitted in any medium without royalty provided this notice is preserved. 
 * 
 * @link http://www.gladiatorgame.de
 * @version 0.9
 * @copyright Copyright: 2009-2011 Patrick Schön
 * @author Patrick Schön <info@cyberrranger.com>
 */

// contributed to stefan johne - copyright jesuscry class libary

// JesusCry command line class
class CommandLine
{

}

// microtime class
class MicroTime
{
  function getMicroTime($Mode)
  {
    // $Mode = start -> start time
    // $Mode = close -> close time
    
    list($Usec, $Sec) = explode(" ", microtime());
    $this->$Mode = ((float)$Usec + (float)$Sec);
  }
  
  function showMicroTime($Places)
  {
    return number_format($this->close - $this->start, $Places, ".", ".");
  }
  
  function saveMicroTime($Path, $Gentime)
  {
    $File = fopen($Path,'a');
    fwrite($File,$Gentime.'|');
  }
  
  function showAvgMicroTime($Path, $Places)
  { 
    $File = fopen($Path,'r');
	
	while(!feof($File))
    {
      $Line = fgets($File, 1024);
      $Text = $Text.$Line;
    }
	
	$AvgGentime = explode('|', $Text);
	
	$Schleife = 0;
    while($Schleife < count($AvgGentime))
    {
      $Average = $Average + $AvgGentime[$Schleife];
      $Schleife++;
    }
  
    return number_format($Average / count($AvgGentime), $Places, ".", ".");
  }
}

// class to operate with dirs and files
class FileSystem
{
  function countDirs($Path, $CountSubDirs=false) // $Path have to be a dir
  {
    $CountedDirs = 0;
    $Dir = dir($Path);
	
    while($Entry = $Dir->read())
    {
	  if(substr($Path,strlen($Path)-1,1) == '/')
	  {
	    $FullPath = $Path.$Entry;
	  }
	  else
	  {
	    $FullPath = $Path.'/'.$Entry;
	  }
	  
	  if(is_dir($FullPath) && $Entry != '.' && $Entry != '..')
	  {
	    $CountedDirs++;
		
		if($CountSubDirs == true)
		{
		  $CountedDirs = $CountedDirs + $this->countDirs($FullPath, $CountSubDirs=true);
		}
	  }
    }
	
    $Dir->close();
	return $CountedDirs;
  }
  
  function countFiles($Path, $CountInSubDirs=false, $Extensions=false) // $Path have to be a dir
  {
    $CountedFiles = 0;
	
	if($Extensions != false)
	{
	  // have to be a file extension - dont use . in front of extension name
	  // explode with , the extensions char | explode ext groups (count not/count only)
	  $ExtensionGroups = explode('|', preg_replace(' ','',$Extensions));
	  
	  $DontCount = explode(',', $ExtensionGroups[0]); // dont count this extensions
	  $CountOnly = explode(',', $ExtensionGroups[1]); // count only this extensions
	}
	else
	{
	  $DontCount = array();
	  $CountOnly = array();
	}
		
	$Dir = dir($Path);
	
    while($Entry = $Dir->read())
    {
	  if(substr($Path,strlen($Path)-1,1) == '/')
	  {
	    $FullPath = $Path.$Entry;
	  }
	  else
	  {
	    $FullPath = $Path.'/'.$Entry;
	  }
	  
	  $PathInfo = pathinfo($FullPath);
	  
	  if(is_file($FullPath) && !in_array($PathInfo['extension'], $DontCount))
	  {
	    if(in_array($PathInfo['extension'], $CountOnly) || count($CountOnly) == 0)
		{
	      $CountedFiles++;
		}
	  }
	  
	  if(is_dir($FullPath) && $Entry != '.' && $Entry != '..' && $CountInSubDirs == true)
	  {
	    $CountedFiles = $CountedFiles + $this->countFiles($FullPath, $CountSubDirs=true, $Extensions);
	  }
	}
	
	$Dir->close();
	return $CountedFiles;
  }
  
  function countLines($Path, $CountInSubDirs=false, $Extensions=false) // $Path have to be a dir (or a file ?)
  {
    $CountedLines = 0;
	
	if($Extensions != false)
	{
	  // have to be a file extension - dont use . in front of extension name
	  // explode with , the extensions char | explode ext groups (count not/count only)
	  $ExtensionGroups = explode('|', preg_replace(' ','',$Extensions));
	  
	  $DontCount = explode(',', $ExtensionGroups[0]); // dont count this extensions
	  $CountOnly = explode(',', $ExtensionGroups[1]); // count only this extensions
	}
	else
	{
	  $DontCount = array();
	  $CountOnly = array();
	}
		
	$Dir = dir($Path);
	
    while($Entry = $Dir->read())
    {
	  if(substr($Path,strlen($Path)-1,1) == '/')
	  {
	    $FullPath = $Path.$Entry;
	  }
	  else
	  {
	    $FullPath = $Path.'/'.$Entry;
	  }
	  
	  $PathInfo = pathinfo($FullPath);
	  
	  if(is_file($FullPath) && !in_array($PathInfo['extension'], $DontCount))
	  {
	    if(in_array($PathInfo['extension'], $CountOnly) || count($CountOnly) == 0)
	    {
		  $ReadFile = @fopen($FullPath, "r");
          while($Line = fgets($ReadFile, 1024))
		  {
            $CountedLines++;
          }
		  
		  fclose($ReadFile);
		}
	  }
	  
	  if(is_dir($FullPath) && $Entry != '.' && $Entry != '..' && $CountInSubDirs == true)
	  {
	    $CountedLines = $CountedLines + $this->countLines($FullPath, $CountSubDirs=true, $Extensions);
	  }
	}
	
	$Dir->close();
	return $CountedLines;
  }
  
  function countChars($Path, $CountInSubDirs=false, $Extensions=false) // $Path have to be a dir (or a file ?)
  {
    $CountedChars = 0;
	
	if($Extensions != false)
	{
	  // have to be a file extension - dont use . in front of extension name
	  // explode with , the extensions char | explode ext groups (count not/count only)
	  $ExtensionGroups = explode('|', preg_replace(' ','',$Extensions));
	  
	  $DontCount = explode(',', $ExtensionGroups[0]); // dont count this extensions
	  $CountOnly = explode(',', $ExtensionGroups[1]); // count only this extensions
	}
	else
	{
	  $DontCount = array();
	  $CountOnly = array();
	}
		
	$Dir = dir($Path);
	
    while($Entry = $Dir->read())
    {
	  if(substr($Path,strlen($Path)-1,1) == '/')
	  {
	    $FullPath = $Path.$Entry;
	  }
	  else
	  {
	    $FullPath = $Path.'/'.$Entry;
	  }
	  
	  $PathInfo = pathinfo($FullPath);
	  
	  if(is_file($FullPath) && !in_array($PathInfo['extension'], $DontCount))
	  {
	    if(in_array($PathInfo['extension'], $CountOnly) || count($CountOnly) == 0)
	    {
		  $ReadFile = @fopen($FullPath, "r");
          while($Line = fgets($ReadFile, 1024))
		  {
            $CountedChars = $CountedChars + strlen($Line);
          }
		  
		  fclose($ReadFile);
	    }
	  }
	  
	  if(is_dir($FullPath) && $Entry != '.' && $Entry != '..' && $CountInSubDirs == true)
	  {
	    $CountedChars = $CountedChars + $this->countChars($FullPath, $CountSubDirs=true, $Extensions);
	  }
	}
	
	$Dir->close();
	return $CountedChars;
  }
}

// class to separate, get and complete paths
class Path
{
  function completeDirPath($Path)
  {
    if(substr($Path,strlen($Path)-1,1) == '/')
    {
      $FullPath = $Path;
    }
    else
    {
	  $FullPath = $Path.'/';
    }
  }
}

?>
