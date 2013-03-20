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
* TagCloud is a class for rendering dyanamic tag cloud images.
*
* The reason why this is awesome is because it is server-side. The server-side
* nature eradicates the need to check the output in every browser available out
* there. It is difficult to find a browser which can not render images. ;)
*/

class TagCloud
{
	private $mWidth;
	private $mHeight;
	private $mMaxFontSize;
	private $mMinFontSize;
	private $mWords;
	private $mWobble = 0;
	private $mFontFile;

	/**
	 * Sets the height for the output image
	 *
	 * @param int $val Height in pixels
	 */
	final public function setHeight($val)
	{
		if(!is_integer($val)) throw new Exception("Width must be an integer");
		if($val < 0) throw new Exception("Width can't be negative");
		$this->mHeight = $val;
	}

	/**
	 * Sets the width for the output image
	 *
	 * @param int $val Width in pixels
	 */
	final public function setWidth($val)
	{
		if(!is_integer($val)) throw new Exception("Width must be an integer");
		if($val < 0) throw new Exception("Width can't be negative");
		$this->mWidth = $val;
	}

	/**
	 * Sets the maximum font size to be used for tags
	 *
	 * @param int $val The maximum font size to use in points.
	 */
	final public function setFontSizeMax($val)
	{
		if(!is_integer($val)) throw new Exception("Font size must be an integer");
		if($val < 0) throw new Exception("Font size can't be negative");
		$this->mMaxFontSize = $val;		
	}

	/**
	 * Sets the minimum font size to be used for tags
	 *
	 * @param int $val The minimum font size to use in points.
	 */
	final public function setFontSizeMin($val)
	{
		if(!is_integer($val)) throw new Exception("Font size must be an integer");
		if($val < 0) throw new Exception("Font size can't be negative");
		$this->mMinFontSize = $val;
	}

	/**
	 * Sets the path of the font to be used
	 *
	 * @param int $val Path of a TRUE TYPE font
	 */
	final public function setFontFile($val)
	{
		if(!is_file($val)) throw new Exception("Font file not valid");
		$this->mFontFile = $val;
	}

	/**
	 * Adds ONE word to the array of tags
	 *
	 * @param string $word Word to be added
	 */
	final public function addWord($word)
	{
		if(!is_string($word)) throw new Exception("Invalid data type for a tag word");
		$this->mWords[] = $word;
	}

	/**
	 * Adds an array of words to the array of tags
	 *
	 * @param Array<string> $words Array of words to be added
	 */
	final public function addWords(Array $words)
	{
		foreach($words as $word)
		{
			$this->addWord($word);
		}
	}

	/**
	 * Sets the tag wobble intensity
	 *
	 * @param int $val Wobble intensity. 0 is no-wobble.
	 */
	final public function setWobble($val)
	{
		if(!is_integer($val)) throw new Exception("Wobble must be an integer");
		if($val < 0) throw new Exception("Wobble can't be negative");
		$this->mWobble = $val;
	}

	/**
	 * Creates and outputs the tag cloud image
	 *
	 */
	final public function createCloud()
	{
		$funcErr = Array();
		if(is_null($this->mWidth)) $funcErr[] = ("Width");
		if(is_null($this->mHeight)) $funcErr[] = ("Height");
		if(is_null($this->mMaxFontSize)) $funcErr[] = ("Max font size");
		if(is_null($this->mMinFontSize)) $funcErr[] = ("Min font size");
		if(is_null($this->mWords)) $funcErr[] = ("Words");
		if(is_null($this->mFontFile)) $funcErr[] = ("Font File");

		if(count($funcErr) > 0)
		{
			$missingElements = implode(", ", $funcErr);
			throw new Exception("Missing values: ".$missingElements);
		}

		$cloudImage = imagecreatetruecolor($this->mWidth, $this->mHeight);

		$backgroundColor = imagecolorallocate($cloudImage, 255, 255, 255);
		$borderColor = imagecolorallocate($cloudImage, 0, 0, 0);

		imagefilledrectangle($cloudImage, 0, 0, $this->mWidth, $this->mHeight, $backgroundColor);

		$sizeSlope = ($this->mMaxFontSize - $this->mMinFontSize) / count($this->mWords);
		$counter = 0;

		$wordAreas = Array();

		$cY = $this->mHeight * 9 / 10;
		$minYValue = $cY;
		foreach($this->mWords as $word)
		{
			$currentWordSize = $this->mMaxFontSize - $sizeSlope * $counter;
			$counter++;

			$dim = imagettfbbox ( $currentWordSize, 0, $this->mFontFile, $word);

			$width = $dim[2] - $dim[0];
			$height = $dim[3] - $dim[5];

			$height *= 1.5;
			$width *= 1.2;

			$cX = 1;
			do
			{
				$GoodDim = TRUE;
				$cX++;
				foreach($wordAreas as $wordArea)
				{
					if(($cX + $width) > $wordArea[0] && $cX < $wordArea[2] && $cY > $wordArea[1] && ($cY - $height) < $wordArea[3])
					{
						$GoodDim = FALSE;
					}
				}
				if($cX++ >= ($this->mWidth - $width) )
				{
					$cY--;
					$cX = 1;
				}
			}while(!$GoodDim);

			$wordAreas[] = Array($cX, $cY - $height, $cX + $width, $cY);
			if(min($this->mHeight, $cY - $height) < $height) break;
			$minYValue = min($minYValue, $cY - $height);

			imagettftext(
				$cloudImage,
				$currentWordSize,
				($this->mWobble>0?rand()%$this->mWobble-($this->mWobble/2):0),
				$cX,
				$cY,
				imagecolorallocate($cloudImage, rand()%100+100, rand()%100+100, rand()%100+100),
				$this->mFontFile,
				$word);
		}

		$CroppedImage = imagecreatetruecolor($this->mWidth, $this->mHeight-$minYValue);
		imagecopy($CroppedImage, $cloudImage, 0, 0, 0, $minYValue, $this->mWidth, $this->mHeight-$minYValue);

		$cloudImage = $CroppedImage;
		imagepng($cloudImage);
		imagedestroy($cloudImage);
	}
};

header('Content-type: image/png');

$tg = new TagCloud();
try
{
	$tg->setWidth(intval($_GET['w']));
	$tg->setHeight(intval($_GET['h']));
	$tg->setFontSizeMax(20);
	$tg->setFontSizeMin(10);
	$tg->setFontFile('resources/Abscissa.ttf');
	$tg->addWords($_GET['t']);
	
	$tg->setWobble(0);
	$tg->createCloud();
}
catch(Exception $e)
{
	readfile('resources/na.png');
}