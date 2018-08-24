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
    return array(
      'all' => true
    );
  }

  function specialization() {
    global $CFG;
    if ($CFG->configtitle) {
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
    require_once($CFG->libdir . '/rsslib.php');
    require_once($CFG->libdir . '/simplepie/moodle_simplepie.php');

    //require_login();
    if ($this->content !== NULL) {
      return $this->content;
    }

    $this->content = new stdClass;
    global $CFG;
    $this->config->text = $CFG->configcontent;
    $this->content->text = $this->config->text;
    $this->content->text .= getRSS("http://resources.library.upei.ca/dbofdbs/courseDBRssFeed.php?course=$COURSE->idnumber");
    $this->content->text .= getStaticStuff();

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
  $output = $output . '<ul style="list-style-position: outside; margin-left: 0; padding-left: 1em; margin-top: 0; padding-top 0;">';

  if ($simplepie->get_item_quantity() != 0) {
    foreach ($simplepie->get_items() as $item) {
      $_title = $item->get_title();
      $_url = $item->get_link();
      $output = $output . "<li><a href='$_url' target=\"_blank\">$_title</a></li>";
    }
  }
  $output = $output . "</ul>";
  return $output;
}

function getStaticStuff() {
  $craftyLink = <<<EOF
    <!-- LibraryH3lp Block -->
    <!-- Place this div in your web page where you want your chat widget to appear. -->
    <div class="needs-js">JavaScript disabled or chat unavailable.</div>
    <script type="text/javascript">
      (function() {
        var x = document.createElement("script"); x.type = "text/javascript"; x.async = true;
        x.src = (document.location.protocol === "https:" ? "https://" : "http://") + "ca.libraryh3lp.com/js/libraryh3lp.js?235";
        var y = document.getElementsByTagName("script")[0]; y.parentNode.insertBefore(x, y);
      })();
    </script>
EOF;

  $craftyLink .= "<div  style=\"margin-bottom: 0; padding-bottom: 0;\"><a href='http://library.upei.ca/' target='_blank'>Library Homepage</a><br>";
  $craftyLink .= "<a href = 'https://moodle31.upei.ca/course/view.php?id=444' target='_blank'>Academic Integrity</a>";
  $craftyLink .= <<<EOF
<!-- Search OneSearch Block -->
<script type="text/javascript">
  (function() {
    var x = document.createElement("script"); x.type = "text/javascript"; x.async = true;
    x.src = "https://support.ebscohost.com/eit/scripts/ebscohostsearch.js";
    var y = document.getElementsByTagName("script")[0]; y.parentNode.insertBefore(x, y);
  })();
</script>
<form action="" method="post" onsubmit="return ebscoHostSearchGo(this);">
    <div style="font-size: 10pt;">
        <input class="form-text" id="ebscohostwindow" name="ebscohostwindow" type="hidden" value="1" />
        <input id="ebscohosturl" name="ebscohosturl" type="hidden" value="http://search.ebscohost.com/login.aspx?direct=true&amp;site=eds-live&amp;scope=site&amp;custid=uprince&amp;groupid=main&amp;profid=eds&amp;mode=and&amp;lang=en&amp;authtype=ip,guest" />
        <input id="ebscohostsearchsrc" name="ebscohostsearchsrc" type="hidden" value="db" /> <input id="ebscohostsearchmode" name="ebscohostsearchmode" type="hidden" value="+AND+" />
        <input id="ebscohostkeywords" name="ebscohostkeywords" type="hidden" value="" />
        <div class="acc-header" style="margin-top: 20px;"><span >Search the Library</span></div>
    </div>

    <div style="padding-top:5px;">
        <input class="form-text" id="ebscohostsearchtext" name="ebscohostsearchtext" onblur="if(this.value == '') { this.value='Find articles, books, &amp; more'}; this.style.color = '#999';" onchange="trackEbscoSearchTerms('OneSearch', 'Search', this.value);" onfocus="if (this.value == 'Find articles, books, &amp; more') {this.value=''}; this.style.color = '#000';" size="30" type="text" value="Find articles, books, &amp; more" /> <input id="onesearchbutton" onclick="validateOneSearch();" type="submit" value="Search" />
        <div id="guidedFieldSelectors" style="font-size: 10pt;">
            <input checked="checked" class="radio" data_messageid="findfield_default_text" id="guidedField_0" name="searchFieldSelector" type="hidden" value="" />
        </div>
    </div>
</form>
EOF;
  return $craftyLink;
}

?>

