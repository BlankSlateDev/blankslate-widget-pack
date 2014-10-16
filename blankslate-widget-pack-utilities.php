<?php
	function shortenString($string, $numChars = 25){
		$sliced = false;
		$string = trim( $string );
		
		//Take only first numChars characters
		if ( strlen($string) > $numChars ){
			$string = substr($string, 0, $numChars);
			$sliced = true;
		}
		
		//If was shortened, add ellipses to end of string.
		if ($sliced){
			$string .= '...';
		}
		
		return $string;
	}

	function BlankSlate_Get_All_Wordpress_Menus(){
		return get_terms( 'nav_menu', array( 'hide_empty' => true ) ); 
	}