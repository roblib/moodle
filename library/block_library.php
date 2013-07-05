<?php

//$Id: block_html.php,v 1.8 2005/05/19 20:09:57 defacer Exp $

class block_library extends block_base {

	function init() {
		$this->title = get_string('pluginname', 'block_library');
	}

	function instance_allow_config() {
		return true;
	}
	function applicable_formats() {
		return array (
			'all' => true
		);
	}

	function specialization() {
    global $CFG;
    if($CFG->configtitle)
    {
    $this->config->title = $CFG->configtitle;
   
    }
    else {
      $this->config->title = 'UPEI Library Resources';
    }
     $this->title = $this->config->title;
		
	}

	function has_config() {
		return true;
	}

	function instance_allow_multiple() {
		return true;
	}

	function get_content() {
		global $CFG, $editing, $COURSE, $USER;
		require_once ($CFG->libdir . '/rsslib.php');
		require_once ($CFG->libdir.'/simplepie/moodle_simplepie.php');

		//require_login();
		if ($this->content !== NULL) {
			return $this->content;
		}

		$this->content = new stdClass;
    global $CFG;
    $this->config->text=$CFG->configcontent;
		$this->content->text = $this->config->text;
		$this->content->text .=getRSS("http://resources.library.upei.ca/dbofdbs/courseDBRssFeed.php?course=$COURSE->idnumber");
		//$this->content->footer = "<div style='text-align: center;'> <a href='http://www.upei.ca/library' target='_blank'>Robertson Library</a></div>";
		//$this->content->footer='<br><form action="http://www.oxfordreference.com.rlproxy.upei.ca/views/SEARCH_RESULTS.html" method="get" target="_blank"><input name="category" value="t62" type="hidden" /><input name="scope" value="book" type="hidden" /><input name="index" value="default" type="hidden" /><input name="q" type="text" />
    //<input value="Define This" type="submit" /></form>';
		$this->content->text .=getStaticStuff();
    
        return $this->content;
	}
}

function getRSS($rssURL) {
	$output = '';
  $simplepie = new SimplePie();
  $simplepie->set_feed_url($rssURL);
  $simplepie->init();
  if ($simplepie->get_item_quantity() != 0) {
    $title = $simplepie->get_title();
    $output .= $title;
  }
	$output=$output.'<ul style="list-style-position: outside; margin-left: 0; padding-left: 1em; margin-top: 0; padding-top 0;">';

	if ($simplepie->get_item_quantity() != 0) {
    foreach ($simplepie->get_items() as $item) {
      $_title = $item->get_title();
      $_url = $item->get_link();
      $output = $output . "<li><a href='$_url' target=\"_blank\">$_title</a></li>";
    }
  }
	$output=$output."</ul>";
	return $output;
}
function getStaticStuff(){
	$craftyLink .= "<script src=\"https://libraryh3lp.com/js/libraryh3lp.js?multi\" type=\"text/javascript\"></script>
  <div class=\"needs-js\" style=\"display: none\" oldblock=\"block\">Library ASK US requires JavaScript. </div>
  <div class=\"libraryh3lp\" style=\"display: block\" oldblock=\"block\" jid=\"upeimoodle@chat.libraryh3lp.com\"><iframe style=\"border-right: #4d759a 1px solid; border-top: #4d759a 1px solid; border-left: #4d759a 1px solid; width: 170px; border-bottom: #4d759a 1px solid; height: 180px\" src=\"https://libraryh3lp.com/chat/upeimoodle@chat.libraryh3lp.com?skin=7721&theme=gota&title=Library%20ASK%20US&identity=library%20staff\" frameborder=\"1\"></iframe></div>
  <div class=\"libraryh3lp\" style=\"display: none\">Library ASK US is currently offline. Please <a href=\"http://library.upei.ca/node/527\">check our other contact options.</a> </div>
  <div><br /></div>" ;

    $craftyLink = $craftyLink."<div  style=\"margin-bottom: 0; padding-bottom: 0;\"><a href='http://library.upei.ca/' target='_blank'>Library Homepage</a><br>";
	$craftyLink .= "<a href = 'http://resources.library.upei.ca/plagiarism/index.htm' target='_blank'>Avoiding Plagiarism</a>";
	$craftyLink .='<form action="http://islandpines.roblib.upei.ca/opac/en-US/skin/roblib/xml/rresult.xml" method="get" target="_blank"><input name="rt" value="keyword" type="hidden" /><input name="tp" value="keyword" type="hidden" /><input name="t" size="17" value="Search Library Catalogue..." onblur="this.value = this.value || this.defaultValue; this.style.color = \'#999\';" type="text" onFocus="this.value=\'\'" style="color: rgb(153, 153, 153);"/><input value="Go" type="submit" /></form></div><br />';

	
	

	return $craftyLink;
}
?>
