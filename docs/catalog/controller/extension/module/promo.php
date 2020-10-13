<?php

class ControllerExtensionModulePromo extends Controller
{
  public function index($setting){
    $this->load->language('extension/module/promo');

    $text_strings = array(
      'heading_title',
    );

    foreach ($text_strings as $text) {
      $data[$text] = $this->language->get($text);
    }

    //custom fileds
    $custom_fileds = array(
      'txt_first',
      'txt_second',
      'txt_third',
      'txt_after',
      'txt_gray',
      //
      'image_first',
      'image_second',
      'image_third',
    );

    foreach ($custom_fileds as $custom_filed) {
      $data[$custom_filed] = $setting[$custom_filed];
    }

    return $this->load->view('extension/module/promo', $data);

  }
}
