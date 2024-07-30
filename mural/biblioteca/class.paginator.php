<?php
/* -----------------------------------------------------------------*/
/*                      Inter4u - Eldon Pinheiro                    */
/*                         Classe - Ted Kappes                      */
/* -----------------------------------------------------------------*/

class Paginator {
					var $previous;	
					var $current;
					var $next;
					var $page;
					var $total_pages;
					var $link_arr;
					var $range1;
					var $range2;
					var $num_rows;
					var $first;
					var $last;
					var $first_of;
					var $second_of;
					var $limit;
					var $prev_next;
					var $base_page_num;
					var $extra_page_num;
					var $total_items;
					var $pagename;
			function Paginator($page,$num_rows) 
			{ 
			    if(!$page)
					{
			    $this->page=1;
					} else {
				  $this->page=$page;
				  }
				  $this->num_rows=$num_rows;
					$this->total_items = $this->num_rows;
			}
			function set_Limit($limit=5)
			{
			    $this->limit = $limit;
					$this->setBasePage();
					$this->setExtraPage();
			}
			function setBasePage()
			{
			    $div=$this->num_rows/$this->limit;	
				  $this->base_page_num=floor($div);
			}
			function setExtraPage()
			{
				  $this->extra_page_num=$this->num_rows - ($this->base_page_num*$this->limit);
			}
			
			function set_Links($prev_next=5)
			{
			    $this->prev_next = $prev_next;
			}
			function getTotalItems()
			{
			$this->total_items = $this->num_rows;
			return $this->total_items;
			}
			function getRange1()
			{
			    $this->range1=($this->limit*$this->page)-$this->limit;	
			    return $this->range1;
			}
			function getRange2()
			{
			    if($this->page==$this->base_page_num + 1)
 	        {
	        $this->range2=$this->extra_page_num;
				  } else { $this->range2=$this->limit;
					}
				  return $this->range2;
			}
			function getFirstOf()
			{
			    $this->first_of=$this->range1 + 1;
			    return $this->first_of;
			}
			function getSecondOf()
			{
			    if($this->page==$this->base_page_num + 1)
 	        {
				  $this->second_of=$this->range1 + $this->extra_page_num;
				  } else { $this->second_of=$this->range1 + $this->limit;
					       }
				  return $this->second_of;
			}
			function getTotalPages()
			{
			    if($this->extra_page_num)
					{
					$this->total_pages = $this->base_page_num + 1;
					} else {
				  $this->total_pages = $this->base_page_num;
					       }
					return $this->total_pages;
			}
			function getFirst()
			{
			    $this->first=1;
			    return $this->first;
			}
			function getLast()
			{
			    if($this->page == $this->total_pages)
					{
					$this->last=0;
					}else { $this->last = $this->total_pages;
					      }
					return $this->last;  
			}
			function getPrevious()
			{
			    if($this->page > 1)
	        {
	        $this->previous = $this->page - 1;
	        }
					return $this->previous;
			}
			function getCurrent()
			{
			    $this->current = $this->page;
					return $this->current;
			}
			function getPageName()
			{
			    $this->pagename = $_SERVER['PHP_SELF'];;
					return $this->pagename;
			}
			function getNext()
			{   
			    $this->getTotalPages();
			    if($this->total_pages != $this->page)
				  {
				  $this->next = $this->page + 1;
				  }
					return $this->next;
			}
			function getLinkArr()
      {
       $top = $this->getTotalPages()- $this->getCurrent();
       if($top <= $this->prev_next)
         {
         $top = $top;
	       $top_range = $this->getCurrent() + $top;
	       } else { $top = $this->prev_next; $top_range = $this->getCurrent() + $top; }
				 
	     $bottom = $this->getCurrent() -1;
       if($bottom <= $this->prev_next)
	       {
	       $bottom = $bottom;
	       $bottom_range = $this->getCurrent() - $bottom;
	       } else { $bottom = $this->prev_next; $bottom_range = $this->getCurrent() - $bottom; } 
	 
	       $j=0;
       foreach(range($bottom_range, $top_range) as $i)
	       {
	       $this->link_arr[$j] = $i;
		     $j++;
		     }
		   return $this->link_arr;
      }
	}
?>	