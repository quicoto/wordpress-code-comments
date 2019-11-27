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
	// Fix all code syntax in old comments
	$comment = str_replace('[code]', '<code>', $comment);
	$comment = str_replace('[/code]', '</code>', $comment);

	$comment = htmlspecialchars_decode($comment);

	$encoded = preg_replace_callback( '/<code>(.*?)<\/code>/ims',
		function ($matches) {
      return '<pre class="wp-block-code"><code class="hljs">' . htmlspecialchars($matches[1]) . '</code></pre>';
    },
		$comment
  );

  if ($encoded) return $encoded;

  return $comment;
}

add_filter( 'comment_text', 'encode_code_in_comment', 9);

