<?php
if ($ADMIN->fulltree) {
  $settings->add(new admin_setting_configtext('configtitle', 
      new lang_string('configtitle', 'block_library'), 
      '', 
      'UPEI Library Resources', PARAM_TEXT));
  $settings->add(new admin_setting_configtextarea('configcontent', 
      new lang_string('configcontent', 'block_library'), 
      '',
      ' ', PARAM_TEXT));
}

