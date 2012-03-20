#!/usr/bin/php
<?php

$fp = fopen('php://stdin', 'r');

$content="";
while ( $line = fgets($fp, 4096) )
	$content.=$line; 
fclose($fp);
$lines=explode(getenv('CODA_LINE_ENDING'), $content);
for ($a=0;$a<getenv('CODA_LINE_NUMBER');$a++){
	is_in_tag("<?","?>",$a,$isPHP);
	is_in_tag("<script","/script>",$a,$isJAVA);
	is_in_tag("<style","/style>",$a,$isCSS);
}

if ($isPHP==1 || $isJAVA==1){
	//PHP & JavaScript Comment
	$text = '// $$IP$$ ';
}elseif($isCSS==1){
	//CSS Comment
	$text = '/* $$IP$$ */';
}else{
	//HTML Comment
	$text = '<!-- $$IP$$ -->';
}
if (getenv('CODA_SELECTED_TEXT')!=""){
	echo str_replace ('$$IP$$',getenv('CODA_SELECTED_TEXT'),$text);
}

function is_in_tag($openTag,$closeTag,$number_line,&$var){
	if ($var==0) {$var=find_tag($openTag,$number_line);}
	if ($var==1) {	if (find_tag($closeTag,$number_line)==1){ $var=0; } }
}

function find_tag($str,$numline){
	$lines=$GLOBALS["lines"];
	$lin=$lines[$numline];
	if (strstr($lin,$str)){
		if ($numline+1==getenv('CODA_LINE_NUMBER')){
			$pos=strpos($lin,$str)+strlen($str);
			if ($pos<=getenv('CODA_LINE_INDEX')){
				return 1;
			}else{
				return 0;
			}
		}else{
			return 1;
		}
	}else{
		return 0;
	}
	return 0;
}
?>