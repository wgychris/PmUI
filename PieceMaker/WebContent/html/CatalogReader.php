<?php

global $arg_first;

function _FILLER($c) {
 return ( $c === ' ' || $c === ',' || $c === '=' || $c === "\n" || $c === "\r" || $c === "\t" );
}
function _SEP($c) {
 return ( $c === "'" || $c === '"' );
}
function _NESTERS($c) {
 return ( $c === '{' || $c === '[' || $c === '(' );
}
function _NESTERE($c) {
 return ( $c === '}' || $c === ']' || $c === ')' );
}

function char_in( $c, $list ) {
 $O=strlen($list);
 for ( $o=0; $o<$O; $o++ ) if ( $list[$o] === $c ) return TRUE;
 return FALSE;
}

function string_argument( $arg, $argument ) {
 global $arg_first;
 $cEnd = ' ';
 $arg_first="";
 // Advance past spaces and interim commas, equal signs, newlines, skip #comments
 while ( isset($argument[$arg]) && ( _FILLER($argument[$arg]) || $argument[$arg] === '#' ) ) {
  if ( $argument[$arg] === '#' ) {
   while ( $argument[$arg] !== '\n'
        && $argument[$arg] !== '\r'
        && $arg < strlen($argument) ) $arg++;
  }
  else $arg++;
 }
 // Handle nested {} [] (), or quotes "" '' ``
 if ( isset($argument[$arg])
  && ( _NESTERS($argument[$arg]) || _SEP($argument[$arg]) ) ) { // Delimiters
  $nests=1;
  $cStart=$argument[$arg];
  $arg++;
  switch ( $cStart ) {
   case '{': $cEnd = '}'; break;
   case '[': $cEnd = ']'; break;
   case '(': $cEnd = ')'; break;
   case "'": $cEnd = "'"; break;
   case '"': $cEnd = '"'; break;
   case '`': $cEnd = '`'; break;
  }
  //echo 'cStart('.$cStart.')=cEnd('.$cEnd.')';
  while ( $arg < strlen($argument) && $nests > 0 ) {
   if ( $argument[$arg] === $cEnd[0] ) {
    $nests--;
    if ( $nests == 0 ) break;
   } else if ( $argument[$arg] === $cStart[0] ) $nests++;
   $arg_first.=($argument[$arg]);
   $arg++;
  }
  $arg++;
 } else { // No delimiters, stop when you hit = , [ { (
  while ( $arg < strlen($argument) ) {
   if ( char_in( $argument[$arg], "\n\r[{(,= " ) === TRUE ) break;
   $arg_first.=$argument[$arg];
   $arg++;
  }
 }
 // Advance past spaces and interim commas, equal signs, newlines, skip #comments
 while ( isset($argument[$arg]) && ( _FILLER($argument[$arg]) || $argument[$arg] === '#' ) ) {
  if ( $argument[$arg] === '#' ) {
   while ( $argument[$arg] !== '\n'
    && $argument[$arg] !== '\r'
    && $arg < strlen($argument) ) $arg++;
  }
  else $arg++;
 }
 //echo '>'.$arg.'<';
 return $arg;
}

function read_carousel_catalog($filenamepath="catalog.txt") {
global $arg_first;
$FILLER =array( ' ', ',', '=', '\n', '\r', '\t' );
$SEP    =array( '\'', '"', '\'' );
$NESTERS=array( '{', '[', '(' );
$NESTERE=array( '}', ']', ')' );

$catalog_file=file_get_contents($filenamepath);
$length=strlen($catalog_file);
$place=0;
$catalog=array();
//echo "Loaded file $length bytes\n";

// Debug info
if ( 0 ) {
echo "char_in('c','abcdefg') : ";
var_dump(char_in('c','abcdefg'));
echo "_NESTERS('[') :";
var_dump(_NESTERS('['));
echo "char_in('x','abcdefg') : ";
var_dump(char_in('x','abcdefg'));
echo "_NESTERS(' ') :";
var_dump(_NESTERS(' '));

while ( $place < $length ) {
 $place=string_argument($place,$catalog_file);
 echo $place.':'.$arg_first;
}
}// Debug info

while ( $place < $length ) {
 $place=string_argument($place,$catalog_file);
 //echo $arg_first . '---' . $place . PHP_EOL;
 if ( stripos($arg_first, 'carouselitem') !== FALSE ) {
//  echo 'item:'.PHP_EOL;
  $index=count($catalog);
  $catalog[]=array();
  $place=string_argument($place,$catalog_file);
//  echo $arg_first . '---' . $place . PHP_EOL;
  $content=$arg_first;
//  echo "Content of message:\n".$content;
  $section=strlen($content);
  $segment=0;
  while ( $segment < $section ) {
   $segment=string_argument($segment,$content);
   $property=$arg_first;
   if ( strlen($property) <= 0 ) continue;
   if ( stripos($property, 'text') !== FALSE ) {
    $segment=string_argument($segment,$content);
    $inner=$arg_first;
    $inner_len=strlen($inner);
    $in=0;
    if ( !isset($catalog[$index]['text']) ) $catalog[$index]['text']=array();
    $number=count($catalog[$index]['text']);
    $catalog[$index]['text'][]=array();
    while ( $in < $inner_len ) {
     $in=string_argument($in,$inner);
     $inner_property=$arg_first;
     $in=string_argument($in,$inner);
     $catalog[$index]['text'][$number][$inner_property]=$arg_first;
    }
   } else if ( stripos($property, 'stencil') !== FALSE ) {
    $segment=string_argument($segment,$content);
    $inner=$arg_first;
    $inner_len=strlen($inner);
    $in=0;
    if ( !isset($catalog[$index]['stencil']) ) $catalog[$index]['stencil']=array();
    $number=count($catalog[$index]['stencil']);
    $catalog[$index]['stencil'][]=array();
    while ( $in < $inner_len ) {
     $in=string_argument($in,$inner);
     $inner_property=$arg_first;
     $in=string_argument($in,$inner);
     $catalog[$index]['stencil'][$number][$inner_property]=$arg_first;
    }
   } else {
    //echo $arg_first . '---' . $segment . ':' . $section . PHP_EOL;
    $segment=string_argument($segment,$content);
    $catalog[$index][$property]=$arg_first;
    //echo $index . ' : ' . $property .'='.$arg_first . PHP_EOL;
   }
  }
  //echo count($catalog);
 }
}

return $catalog;
}

function read_catalog_grid($filenamepath="catalog.grid.txt") {
global $arg_first;
$FILLER =array( ' ', ',', '=', '\n', '\r', '\t' );
$SEP    =array( '\'', '"', '\'' );
$NESTERS=array( '{', '[', '(' );
$NESTERE=array( '}', ']', ')' );

$catalog_file=file_get_contents($filenamepath);
$length=strlen($catalog_file);
$place=0;
$catalog=array();
while ( $place < $length ) {
 $place=string_argument($place,$catalog_file);
 if ( stripos($arg_first, 'row') !== FALSE ) {
  $index=count($catalog);
  $catalog[]=array();
  $place=string_argument($place,$catalog_file);
  $content=$arg_first;
  $section=strlen($content);
  $segment=0;
  while ( $segment < $section ) {
   $segment=string_argument($segment,$content);
   $property=$arg_first;
   if ( strlen($property) <= 0 ) continue;
   $segment=string_argument($segment,$content);
   $catalog[$index][$property]=$arg_first;
  }
 }
}

return $catalog;
}
