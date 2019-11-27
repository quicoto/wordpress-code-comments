<?php
/*

	Plugin Name: WordPress Code Comments
	Plugin URI: https://php.quicoto.com
	Description: A WordPress plugin which escapes the HTML inside the code blocks in comments
	Author: Ricard Torres
	Version: 1.0
	Author URI: https://ricard.blog
*/

function encode_code_in_comment( $comment ) {
	$encoded = preg_replace_callback( '/<code>(.*?)<\/code>/ims',
		create_function(
			'$matches',
			'$matches[1] = preg_replace(
			array("/^[\r|\n]+/i", "/[\r|\n]+$/i"), "",
			$matches[1]);
			return "<code>" . htmlspecialchars($matches[1]) . "</code>";'
		),
		$source
  );


  if ($encoded) return $encoded;

  return $comment
}

add_filter( 'comment_text', 'encode_code_in_comment', '', 1);

