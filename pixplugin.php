<?
/*
Plugin Name: Pix-Plugin Auto_Inserter

Version: 1.0

Plugin URI: http://www.pixplugin.com/wordpress-autoinsert-plugin/

Description: Inserts automatically generated pictures into all posts, using the PixPlugin service

Author: PixPlugin

Author URI: http://www.pixplugin.com/wordpress-autoinsert-plugin/

*/

function pixplugin_PrintHeaderCode($Header)
{
      print ("<iframe height=1 width=1 marginheight=0 marginwitdh=0 frameborder=0 src='http://www.pixplugin.com/pagecheckin.php'></iframe><br>") ;
}

function pixplugin_GetPicForArticle($ArticleText)
{
  $keywords = pixplugin_keyword_extract($ArticleText);
 // $MyPair = GetRandomPhotographerPair();
  $PicCode = pixplugin_GetLinklessPicCode($keywords);
  //$PicCode = GetPicCode($keywords,trim($MyPair['name']),trim($MyPair['url']));
  
  return $PicCode . $ArticleText;
}

function pixplugin_GetLinklessPicCode ($Subject)
{
  $DashedSubject = pixplugin_SpacesToDashes($Subject);
  $WordArray =explode(' ', $Subject);
  $FirstWord = $WordArray[0];

  $AlignArray = array('right','left');
  $MyAlign = $AlignArray[array_rand($AlignArray)];
  $LinkHTML = "<img hspace=5 vspace=5 align='$MyAlign' src='http://www.pixplugin.com/images/$DashedSubject/$FirstWord.jpg'>";
  pixplugin_keyword_extract($Subject);

  return $LinkHTML;
};

function pixplugin_SpacesToDashes($string)
{
   return (str_replace (' ', '-', $string));
}

function pixplugin_keyword_extract($text){
    $text = strip_tags($text);
    $text = str_replace(",","", $text);
    $text = str_replace(".","", $text);
    $text = str_replace(";","", $text);
    $text = strtolower($text);
    $punc =". , : ; ' ? ! ( ) \" \\";
    $punc = explode(" ",$punc);
    foreach($punc as $value){
        $text = str_replace($value, " ", $text);
    }
    $commonWords = "about,that's,this,that,than,then,them,there,their,they,it's,its,with,which,were,where,whose,when,what,her's,he's,have,br,more,people,do,don't,will,won't,place,recent,also,into,after,wants,her,he,she,you,are,aren't,yes,no,in,out";
    $commonWords = strtolower($commonWords);
    $words = explode(" ", $text);
    $commonWords = explode(",", $commonWords);
    foreach ($words as $value) {
        $common = false;
        if (strlen($value) > 3){
            foreach($commonWords as $commonWord){
                if ($commonWord == $value){
                    $common = true;
                }
            }
            if($common != true){
                $keywords[] = $value;
            }
        }
    }

    $keywords = array_count_values($keywords);
    arsort($keywords);

    return pixplugin_first_words(implode(' ', array_keys($keywords)), 5, '');
}

function pixplugin_first_words($string, $num, $tail='&nbsp;...')
{
        /** words into an array **/
        $words = str_word_count($string, 2);

        /*** get the first $num words ***/
        $firstwords = array_slice( $words, 0, $num);

        /** return words in a string **/
        return  implode(' ', $firstwords).$tail;
}

add_filter('the_content', 'pixplugin_GetPicForArticle');
add_action('wp_head', 'pixplugin_PrintHeaderCode');

