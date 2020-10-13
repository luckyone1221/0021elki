<?php

class ControllerExtensionModuleVantage extends Controller
{
  public function index($setting){
    $this->load->language('extension/module/vantage');

    $text_strings = array(
      'heading_title',
    );

    foreach ($text_strings as $text) {
      $data[$text] = $this->language->get($text);
    }

    //custom fileds
    $custom_fileds = array(
      'txt_date',
      'txt_delivery',
      'txt_shedule',
      'txt_quality',
      //
      'icon_date',
      'icon_delivery',
      'icon_shedule',
      'icon_quality',
    );

    foreach ($custom_fileds as $custom_filed) {
      $data[$custom_filed] = $setting[$custom_filed];
    }


    //$data['heading_title'] = $this->language->get('heading_title');

    return $this->load->view('extension/module/vantage', $data);

  }
}
