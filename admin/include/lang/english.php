<?php
function lang($para) 
{
	static $langs = array(
		'HOME' 			=> 'Home',
		'CATEGORIES' 	=> 'Categories',
		'ITEMS' 		=> 'Items',
		'MEMBERS' 		=> 'Members',
		'STATISTICS' 	=> 'Statistics',
		'COMMENTS' 	=> 'Comments',
		'LOGS' 			=> 'Logs');

	return  $langs[$para];
}