<?php 
/*
 * Smarty plugin
 * ------------------------------------------------------------
 * Type:       modifier
 * Name:       bbcode2html
 * Purpose:    Converts BBCode style tags to HTML
 * Author:     Andre Rabold
 * Version:    1.4
 * Remarks:    Notice that this function does not check for
 *             correct syntax. Try not to use it with invalid
 *             BBCode because this could lead to unexpected
 *             results ;-)
 *             It seems that this function ignores manual 
 *             line breaks. IMO this can be fixed by adding 
 *             '/\n/' => "<br>" to $preg
 *
 * What's new: - Rewrote some preg expressions for more
 *               stability.
 *             - renamed CSS classes to be more generic. (Example
 *               CSS file attached.)
 *             - Support for escaped tags. Add a backslash
 *               infront of a tag if you don't want to transform
 *               it. For example: \[b]
 *
 *             Version 1.3c
 *             - Fixed a bug with <li>...</li> tags (thanks
 *               to Rob Schultz for pointing this out)
 *
 *             Version 1.3b
 *             - Added more support for phpBB2:
 *               [list]...[/list:u] unordered lists
 *               [list]...[/list:o] ordered lists
 *             
 *             Version 1.3
 *             - added support for phpBB2 like tag identifier
 *               like [b:b6a0cef7ea]This is bold[/b:b6a0cef7ea]
 *               (thanks to Rob Schultz)
 *             - added support for quotes within the quote tag
 *               so [quote="foo"]bar[/quote] does work now
 *               correctly
 *             - removed str_replace functions
 *
 *             Version 1.2
 *             - now supports CSS classes:
 *                  ng_email      (mailto links)
 *                  ng_url        (www links)
 *                  ng_quote      (quotes)
 *                  ng_quote_body (quotes)
 *                  ng_code       (source code)
 *                  ng_list       (html lists)
 *                  ng_list_item  (list items)
 *             - replaced slow ereg_replace() functions
 *             - Alterned [quote] and [code] to use CSS classes
 *               instead of HTML <blockquote />, <hr />, ... tags.
 *             - Additional BBCode tags [list] and [*] to display
 *               nice HTML lists. Example:
 *                 [list]
 *                   [*]first item
 *                   [*]second item
 *                   [*]third item
 *                 [/list]
 *               The [list] tag can have an additional parameter:
 *                 [list]   unorderer list with bullets
 *                 [list=1] ordered list 1,2,3,4,...
 *                 [list=i] ordered list i,ii,iii,iv,...
 *                 [list=I] ordered list I,II,III,IV,...
 *                 [list=a] ordered list a,b,c,d,...
 *                 [list=A] ordered list A,B,C,D,...
 *             - produces well-formed output
 *             - cleaned up the code
 * ------------------------------------------------------------
 */

function smarty_modifier_bbcode2html($str) {

	# Formatierungen
  $str = preg_replace('#\[b\](.*)\[\/b\]#isU', "<b>$1</b>", $str);
  $str = preg_replace('#\[i\](.*)\[\/i\]#isU', "<i>$1</i>", $str);
  $str = preg_replace('#\[u\](.*)\[\/u\]#isU', "<u>$1</u>", $str);
  $str = preg_replace('#\[center\](.*)\[\/center\]#isU', "<center>$1</center>", $str);
  $str = preg_replace('#\[color=(.*)\](.*)\[\/color\]#isU', "<span style=\"color: $1\">$2</span>", $str);
  $str = preg_replace('#\[size=(8|10|12)\](.*)\[\/size\]#isU', "<span style=\"font-size: $1 pt\">$2</span>", $str);

  # Links
  $str = preg_replace('#\[url\](.*)\[\/url\]#isU', "<a href=\"$1\">$1</a>", $str);
  $str = preg_replace('#\[ url=(.*)\](.*)\[\/url\]#isU', "<a href=\"$1\">$2</a>", $str);

  # Grafiken
  $str = preg_replace('#\[img\](.*)\[\/img\]#isU', "<img src=\"$1\" alt=\"$1\" />", $str);

  # Zitate
  $str = preg_replace('#\[quote\](.*)\[\/quote\]#isU', "<div class=\"zitat\">$1</div>", $str);
  
  # Quelltext
  $str = preg_replace('#\[code\](.*)\[\/code\]#isU', "<div class=\"code\">$1</div>", $str);

  # Listen
  $str = preg_replace('#\[list\](.*)\[\/list\]#isU', "<ul>$1</ul>", $str);
  $str = preg_replace('#\[list=(1|a)\](.*)\[\/list\]#isU', "<ol type=\"$1\">$2</ol>", $str);
  $str = preg_replace("#\[*\](.*)\\r\\n#U", "<li>$1</li>", $str);
  
  return $str;
}
?>