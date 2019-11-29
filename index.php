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

// add_filter( 'comment_text', 'encode_code_in_comment', 9);


namespace Syntax_Highlighting_Code_Comments;
/**
 * Make <code>...</code> in (comment) text syntax-highlighted.
 *
 * The <code> element may be optionally wrapped in a <pre> tags, and then start and end on a line by itself. In other
 * words it must be presented as a block/paragraph and not inline; when <code> appears inline in paragraphs, it will
 * not be syntax-highlighted.
 *
 * @param string $comment_text Comment text.
 * @return string Syntax-highlighted.
 */
function filter_comment_text( $comment_text ) {
	// Fix all code syntax in old comments
	$comment = str_replace('[code]', '<code>', $comment);
	$comment = str_replace('[/code]', '</code>', $comment);

	$comment = htmlspecialchars_decode($comment);

	if ( ! function_exists( '\Syntax_Highlighting_Code_Block\render_block' ) ) {
		return $comment_text;
	}
	$pattern = implode(
		'',
		[
			'(?<=^|\n)',
			'(<pre[^>]*?>\s*)?',
			'<code[^>]*?>(?P<contents>[^<]*?)</code>',
			'(\s*</pre>)?',
			'(?=\r?\n|$)',
		]
	);
	return preg_replace_callback(
		"#{$pattern}#si",
		static function( $matches ) {
			$attributes = [];
			$contents   = $matches['contents'];
			$before     = '<pre><code>';
			$after      = '</code></pre>';
			return \Syntax_Highlighting_Code_Block\render_block( $attributes, $before . $contents . $after );
		},
		$comment_text
	);
}
add_filter( 'comment_text', __NAMESPACE__ . '\filter_comment_text', 20 );

