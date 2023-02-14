<?php 
/* -----------------------------------------------------------------*/
/*                      Inter4u - Eldon Pinheiro                    */
/*                         Classe - Ted Kappes                      */
/* -----------------------------------------------------------------*/

class Paginator_html extends Paginator { 			
	function previousNext() {
		if($this->getPrevious()) {
        	$print_link .= "<a href=\"" . $this->getPageName() . "?pagina=" . $this->getPrevious() . "\">Anterior</a> ";
		}
		$links = $this->getLinkArr();
        foreach($links as $link) {
        	if($link == $this->getCurrent()) {
	        	$print_link .= " [<b>$link</b>] ";
			}
			else {
				$print_link .= "<a href=\"" . $this->getPageName() . "?pagina=$link\">" . $link . "</a> ";
    	    }
		} 
		if($this->getNext()) {
			$print_link .= "<a href=\"" . $this->getPageName() . "?pagina=" . $this->getNext() . "\">Próxima</a> ";
        }
    return $print_link;
	}  
}
?>				 