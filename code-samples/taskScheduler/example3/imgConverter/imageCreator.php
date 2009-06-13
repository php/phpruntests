<?php


class imageCreator {
	
	
	private $chars = array();
	private $image = NULL;
	private $size = 0;
	
	private $pixelGap = 2;
	
	
	public function __construct(array $chars) {

		$this->chars = $chars;
	}
	

	public function draw() {
		
		$s = $this->size = ceil(sqrt(sizeof($this->chars)/3))*$this->pixelGap;
		
		$this->image = ImageCreateTrueColor($this->size-$this->pixelGap+1, $this->size-$this->pixelGap+1);
		
		$bg = ImageColorAllocate($this->image, 255, 255, 255);
		
		ImageFill($this->image, 0, 0, $bg);
		ImageColorTransparent($this->image, $bg);

		$colors = array();

		$x = 0;
		$y = 0;

		for ($i=0; $i<sizeof($this->chars); $i+=3) {
			
			if ($i>0 && $x%$s==0) {
				
				$x = 0;
				$y += $this->pixelGap ;
			}
			
			$r = $this->chars[$i];
			$g = $this->chars[$i+1];
			$b = $this->chars[$i+2];
			
			$v = $r.$g.$b;

			if (!isset($colors[$v]))
				$colors[$v] = ImageColorAllocate($this->image , $r*2, $g*2, $b*2);

			ImageLine($this->image , $x, $y, $x, $y, $colors[$v]);

			$x += $this->pixelGap;
		}
		
		// $this->quadSize();

		// imageellipse($this->image, $size, $size, $size*2, $size*2, $bg);
	}
	
	
	public function quadSize() {
		
		$s = $this->size+$this->pixelGap-1;
		
		$img = ImageCreateTrueColor($s*2, $s*2);
		
		$bg = ImageColorAllocate($this->image , 255, 255, 255);
		
		ImageFill($img, 0, 0, $bg);
		ImageColorTransparent($img, $bg);
		
		imagecopymerge($img, $this->image, $s, $s, 0, 0, $s, $s, 100);

		$tmp = ImageCreateTrueColor($s, $s);
		
		imagecopyresampled($tmp, $this->image, 0, 0, ($s-1), 0, $s, $s, 0-$s, $s);
		imagecopymerge($img, $tmp, 0, $s, 0, 0, $s, $s, 100);
		
		imagecopyresampled($tmp, $this->image, 0, 0, 0, ($s-1), $s, $s, $s, 0-$s);
		imagecopymerge($img, $tmp, $s, 0, 0, 0, $s, $s, 100);
		
		imagecopyresampled($tmp, $this->image, 0, 0, ($s-1), ($s-1), $s, $s, 0-$s, 0-$s);
		imagecopymerge($img, $tmp, 0, 0, 0, 0, $s, $s, 100);
		
		$this->image = $img;
	}
	
	
	
	public function nice() {
		
		$sum = array_sum($this->chars);
		
		$s = ceil(sqrt($sum));
		
		$this->image = ImageCreateTrueColor(sizeof($this->chars)*2, 255);
		
		$bg = ImageColorAllocate($this->image, 255, 255, 255);
		
		ImageFill($this->image, 0, 0, $bg);
		ImageColorTransparent($this->image, $bg);

		$colors = array();
		
		for ($i=0; $i<sizeof($this->chars); $i++) {
	
			$v = $this->chars[$i]*2;

			if (!isset($colors[$v]))
				$colors[$v] = ImageColorAllocate($this->image , $v, $v, $v);

			ImageLine($this->image , $i*2, 255, $i*2, 255-$v, $colors[$v]);
		}		
	}

	
	public function saveImage($name) {
		
		return ImagePNG($this->image, $name);
	}
	
	public function setPixelGap($gap) {
		
		$this->pixelGap = is_numeric($gap) ? $gap+1 : 1;
	}
	
} 


?>