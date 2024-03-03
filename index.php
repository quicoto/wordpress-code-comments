<?php
/*

	Plugin Name: WordPress Code Comments
	Plugin URI: https://php.quicoto.com
	Description: A WordPress plugin which escapes the HTML inside the code blocks in comments
	Author: Ricard Torres
	Version: 1.0
	Author URI: https://ricard.blog
*/

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
function filter_comment_text( $comment ) {
	// Fix all code syntax in old comments
	$comment = str_replace('[code]', '<code>', $comment);
	$comment = str_replace('[/code]', '</code>', $comment);

	$comment = htmlspecialchars_decode($comment);

	$encoded = preg_replace_callback( '/<code>(.*?)<\/code>/ims',
		function ($matches) {
			$attributes = [];
			$attributes['highlightedLines'] = false;
			$attributes['showLineNumbers'] = false;
			$attributes['wrapLines'] = false;
			$attributes['language'] = '';
			$contents   = $matches[1];
			$before     = '<pre><code>';
			$after      = '</code></pre>';
			return \Syntax_Highlighting_Code_Block\render_block( $attributes, $before . $contents . $after );
		},
		$comment
	);

  if ($encoded) {
		$encoded = str_replace(['’', '‘'], "'", $encoded);
		$encoded = str_replace(['”'], '"', $encoded);
		return $encoded;
	}

	return $comment;
}
add_filter( 'comment_text', __NAMESPACE__ . '\filter_comment_text', 20 );
