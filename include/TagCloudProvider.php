<?php

/**
* @author     Sagar Bhosale : sagar@codenext.co.in
* @version    1.0
*
* @section LICENSE
*
* This program is free software; you can redistribute it and/or
* modify it under the terms of the GNU General Public License as
* published by the Free Software Foundation; either version 2 of
* the License, or (at your option) any later version.
*
* This program is distributed in the hope that it will be useful, but
* WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
* General Public License for more details at
* http://www.gnu.org/copyleft/gpl.html
*
* @section DESCRIPTION
*
* TagCloudProvider is a wrapper class for writing the HTML for using the
* TagCloud class.
*
* The reason why this is awesome is because it is server-side. The server-side
* nature eradicates the need to check the output in every browser available out
* there. It is difficult to find a browser which can not render images. ;)
*/

class TagCloudProvider
{
	private $mTagCloudClassPath;
	public function __construct($path)
	{
		$this->mTagCloudClassPath = $path;
	}

	public function create($width, $height, Array $words)
	{
		if(!is_file($this->mTagCloudClassPath))
			throw new Exception("Invalid path");
		if(!is_int($width) || !is_int($height))
			throw new Exception("Invalid width or height");
		echo "<img src=\"".$this->mTagCloudClassPath."?w=".$width."&h=".$height;
		foreach($words as $word)
		{
			echo "&t[]=".urlencode($word);
		}
		echo "\" />";
	}
}