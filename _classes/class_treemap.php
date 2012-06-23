<?php
/*

author:
 
 - Pierre Lindenbaum PhD plindenbaum (at) yahoo (dot) fr http://www.integragen.com
 
 
original java code from http://www.cs.umd.edu/hcil/treemap-history/Treemaps-Java-Algorithms.zip
 by  
  - Martin Wattenberg, w(at)bewitched.com
  - Ben Bederson, bederson(at)cs.umd.edu
    University of Maryland, Human-Computer Interaction Lab
    http://www.cs.umd.edu/hcil




Permission is hereby granted, free of charge, to any person obtaining
a copy of this software and associated documentation files (the
``Software''), to deal in the Software without restriction, including
without limitation the rights to use, copy, modify, merge, publish,
distribute, sublicense, and/or sell copies of the Software, and to
permit persons to whom the Software is furnished to do so, subject to
the following conditions:

The above copyright notice and this permission notice shall be included
in all copies or substantial portions of the Software.

The name of the authors when specified in the source files shall be 
kept unmodified.

THE SOFTWARE IS PROVIDED ``AS IS'', WITHOUT WARRANTY OF ANY KIND, EXPRESS
OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
IN NO EVENT SHALL 4XT.ORG BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE,
ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE
USE OR OTHER DEALINGS IN THE SOFTWARE.


$Id: $
$Author: $
$Revision: $
$Date: $
$Locker: $
$RCSfile: $
$Source: $
$State: $
$Name: $
$Log: $
*************************************************************************/
/**
 *
 * Dimension
 * eq of java.awt.Color
 *
 */
class Color
	{
	var $r;
	var $g;
	var $b;
	/* init with red green blue */
	function Color($r=0,$g=0,$b=0)
		{
		$this->r=$r;
		$this->g=$g;
		$this->b=$b;
		}
	/* return "rgb(r,b,b)" */
	function toSVG()
		{
		return "rgb(".$this->r.",".
				$this->g.",".
				$this->b.")";
		}
	}

/**
 *
 * Dimension
 * eq of java.awt.Dimension
 *
 */
class Dimension
	{
	var $width;
	var $height;
	
	/* constructor */
	function Dimension($width=0.0, $height=0.0)
		{
		$this->setSize($width, $height);
		}
	
		
	function setSize($width, $height)
		{
		$this->width=$width;
		$this->height=$height;
		}
	
	function setWidth($width)
		{
		$this->width=$width;
		}
			
	function setHeight($height)
		{
		$this->height=$height;
		}			
		
	function getWidth()
		{
		return $this->width;
		}	
	
	function getHeight()
		{
		return $this->height;
		}
		
	function toString()
	 	{
        	return "Dimension(".$this->getWidth().",".$this->getHeight().")";
    		}	
	}

/**
 *
 * Dimension
 * eq of java.awt.Rectangle
 *
 */
class Rectangle extends Dimension
	{
	var $x;
	var $y;
	
	
	function Rectangle($x=0, $y=0,$width=0.0, $height=0.0)
		{
		$this->setBounds($x,$y,$width, $height);
		}
	
	function setRectangle(&$rect)
		{
		$this->setBounds(
			$rect->getX(),
			$rect->getY(),
			$rect->getWidth(),
			$rect->getHeight()
			);
		}
		
	function setBounds($x,$y,$width, $height)
		{
		$this->setSize($width, $height);
		$this->setLocation($x,$y);
		}
	
	function setLocation($x, $y)
		{
		$this->x=$x;
		$this->y=$y;
		}	
	
	function setX($x)
		{
		$this->x=$x;
		}		
	
	function setY($y)
		{
		$this->y=$y;
		}		

	function getCenterX()
		{
		return $this->getX()+$this->getWidth()/2.0;
		}
	
	function getCenterY()
		{
		return $this->getY()+$this->getHeight()/2.0;
		}	
			
	function getX()
		{
		return $this->x;
		}	
	
	function getY()
		{
		return $this->y;
		}
		
	function toString()
	 	{
        	return "Rectangle(".
			$this->getX().",".
			$this->getY().",".
			$this->getWidth().",".
			$this->getHeight().
			")";
    		}	
	}
/**
 *
 * A TreeMapItem is a "square" in the TreeMap
 * it is initialized with a positive number used as
 * the weight of this square
 *
 */ 
class TreeMapItem extends Rectangle
	{
	var $weight;/* weight */
	var $url;/* url for hyperlink xml escaped */
	var $label;/* label for this item default is weight */
	var $fill;/* a Color for filling */
	var $stroke;/* a color for stroking */
	var $id;/* optional id for xml dom */
	
	/* TreeMapItem initialized with a number, the 'weight' of this square */
	function TreeMapItem($weight)
		{
		$this->weight=$weight;
		$this->setBounds(0.0,0.0,0.0,0.0);
		$this->url=NULL;
		$this->label=$weight;
		$this->fill=NULL;
		$this->stroke=new Color(255,255,255);
		}
	
	function setFill($color)
		{
		$this->fill=$color;
		}	

	function setID($id)
		{
		$this->id=$id;
		}	
			
	function setStroke($color)
		{
		$this->stroke=$color;
		}
			
	function getRGBFill()
		{
		if($this->fill==NULL) return "none";
		return $this->fill->toSVG();
		}	
	
	function getRGBStroke()
		{
		if($this->stroke==NULL) return "none";
		return $this->stroke->toSVG();
		}	
		
	function getWeight()
		{
		return $this->weight;
		}
	
	function setURL($url)
		{
		$this->url=$url;
		}
	
	function setLabel($label)
		{
		$this->label=$label;
		}	
	
	/* print this item as SVG using stream $out and using parameters from its owner $treemap */
	function toSVG(&$treemap,$out)
		{
		$fontsize= $treemap->getFontSize();
		fwrite($out, "<g");
		if($this->id!=null) fwrite($out," id='".$this->id."'");
		fwrite($out,">");
		
		if($this->url!=NULL)
			{
			fwrite($out,"<a xlink:title='".$this->url."' xlink:href='".$this->url."'>");
			}
		fwrite($out, "<rect  x='".$this->getX()."' y='".$this->getY()."' ".
			"width='".$this->getWidth()."' height='".$this->getHeight()."' ". 
			" fill='".$this->getRGBFill()."' stroke='".$this->getRGBStroke()."' ".
			"/>");
		if($this->label!=NULL)
			{
			while(!( $this->getHeight()> $fontsize && $this->getWidth() > strlen($this->label)*$fontsize))
				{
				--$fontsize;
				if($fontsize<=4) break;
				}
			
			fwrite($out, "<text  ".
				"x='".$this->getCenterX()."' ".
				"y='".($this->getCenterY()+$fontsize/2.0)."' ".
				"stroke='".$this->getRGBStroke()."' font-size='".$fontsize."'>".
				$this->label.
				"</text>");
			}
		if($this->url!=NULL)
			{
			fwrite($out,"</a>");
			}
		fwrite($out, "</g>");
		}		
		
	}
/* used to sort treeMap Item */
function treemap_item_compare(&$a,&$b)
	{
	if($a->getWeight()== $b->getWeight())
		{
		return 0;
		}
	else if($a->getWeight()< $b->getWeight())
		{
		return 1;
		}
	return -1;
	}

/**
 * The Treemap : a container of TreeMapItem(s)
 * contains the treemap algorithm
 *
 */
class TreeMap extends Dimension
	{
	var $treemapitems;
	var $fontsize;
	
	/* constructor with dimension */
	function TreeMap($w=500.0,$h=500.0)
		{
		$this->treemapitems=array();
		$this->setSize($w,$h);
		$this->setFontSize(24);
		}
	/* add a new TreeMapItem */
	function addItem($item)
		{
		if($item->getWeight()<=0) return;
		$this->treemapitems[count($this->treemapitems)]=$item;
		}	
	
	function getFontSize()
		{
		return $this->fontsize;
		}
		
	function setFontSize($fontsize)
		{
		$this->fontsize=$fontsize;
		}	
	
	/* output the treemap as SVG using $out stream */
	function toSVG($out=NULL)
		{
		if($out==NULL)
			{
			$out=fopen("php://output","w")or die ("stdout?");	
			}
		fwrite($out, "<svg width='".$this->getWidth()."' height='".$this->getHeight()."' ". 
			" text-anchor='middle' ".
			" font-family='monospace' ".
			" font-size='".$this->getFontSize()."' ".
			" xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink'>".
		       "<desc>generated with treemapphp.php Pierre Lindenbaum PhD 2006 Integragen plindenbaum (at) yahoo (dot) fr http://www.integragen.com. Original java code from Martin Wattenberg, w(at)bewitched.com and Ben Bederson, bederson(at)cs.umd.edu University of Maryland, Human-Computer Interaction Lab http://www.cs.umd.edu/hcil </desc>".
		       "<rect  x='0' y='0' ".
			"width='".$this->getWidth()."' height='".$this->getHeight()."' stroke='blue' fill='none' ". 
			"/>"
			);
		       
		$this->layout($this->treemapitems,new Rectangle(0,0,$this->getWidth(),$this->getHeight()));
		
		foreach($this->treemapitems as $index=>$item)
		       {
		       $item->toSVG($this,$out);
		       }
		fwrite($out,"</svg>");
		flush($out);
		}	
		
	/* private */
	function layout(&$items, $rect)
		{
		uasort($items,"treemap_item_compare");
		$this->layout2($items,0,count($items),$rect);
		}
	
	/* private */
	function getWeight(&$items, $start,$end)
		{
		$sum=0.0;
		while($start<$end)
			{
			$sum +=$items[$start]->getWeight();
			$start++;
			}
		return $sum;
		}

	/* private */
	function sliceLayout(&$comps,$start,$end,$bounds)
		{
		$end=min(count($comps),$end);
		$total = $this->getWeight($comps,$start,$end);
		$a=0.0;
		$vertical=($bounds->getWidth()<$bounds->getHeight() );
		$pos= ($vertical==TRUE?$bounds->getY():$bounds->getX());
	
		for ($i=$start;$i<$end; $i++)
			{
			$r=new Rectangle();
			$b=  $comps[$i]->getWeight()/$total;
			if ($vertical==TRUE)
				{
				$r->setX($bounds->getX());
				$r->setWidth($bounds->getWidth());
				$r->setY($pos);
				$len = $bounds->getHeight()*$b;
				$r->setHeight($len);
				$pos+=$len;
				}
			else
				{
				$r->setX($pos);
				$len = $bounds->getWidth()*$b;
				$r->setWidth($len);
				$r->setY($bounds->getY());
				$r->setHeight($bounds->getHeight());
				$pos+=$len;
				}
			$comps[$i]->setRectangle($r);
			$a+=$b;
			}
		}

	/* private */		
	function layout2(&$comps,$start,$end,$bounds)
		{
		if ($start>=$end) return;
		
		if ($end-$start<2)
			{
			$this->sliceLayout($comps,$start,$end,$bounds);
			return;
			}
		
		$x=$bounds->getX();
		$y=$bounds->getY();
		$w=$bounds->getWidth();
		$h=$bounds->getHeight();
		
		$total=$this->getWeight($comps,$start, $end);
		$mid=$start;
		$a= $comps[$start]->getWeight()/$total;
		$b=$a;
		
		if ($w<$h)
			{
			// height/width
			while ($mid<$end)
				{
				$aspect=$this->normAspect($h,$w,$a,$b);
				$q= $comps[$mid]->getWeight()/$total;
				if ($this->normAspect($h,$w,$a,$b+$q)>$aspect) break;
				$mid++;
				$b+=$q;
				}
			$this->sliceLayout($comps,$start,$mid+1,new Rectangle($x,$y,$w,($h*$b)));
			$this->layout2($comps,$mid+1,$end,new  Rectangle($x,($y+$h*$b),$w,($h*(1-$b))));
			}
		else
			{
			// width/height
			while ($mid<$end)
				{
				$aspect=$this->normAspect($w,$h,$a,$b);
				$q= $comps[$mid]->getWeight()/$total;
				if ($this->normAspect($w,$h,$a,$b+$q)>$aspect) break;
				$mid++;
				$b+=$q;
				}
			$this->sliceLayout($comps,$start,$mid+1,new Rectangle($x,$y,($w*$b),$h));
			$this->layout2($comps,$mid+1,$end,new  Rectangle(($x+$w*$b),$y,($w*(1-$b)),$h));
			}
		
		}
	/* private */
	function aspect($big,$small,$a,$b)
		{
		return ($big*$b)/($small*$a/$b);
		}
	/* private */
	function normAspect($big, $small,$a,$b)
		{	
		$x=$this->aspect($big,$small,$a,$b);
		if ($x<1) return 1.0/$x;
		return $x;
		}
	}
?>