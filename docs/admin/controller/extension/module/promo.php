<?php

class ControllerExtensionModulePromo extends Controller
{

  private $error = array();

  public function index()
  {

    $this->load->language('extension/module/promo');

    $this->document->setTitle($this->language->get('heading_title'));

    $this->load->model('setting/module');

    if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
      if (!isset($this->request->get['module_id'])) {
        $this->model_setting_module->addModule('promo', $this->request->post);
      } else {
        $this->model_setting_module->editModule($this->request->get['module_id'], $this->request->post);
      }

      $this->session->data['success'] = $this->language->get('text_success');

      $this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
    }

    if (isset($this->error['warning'])) {
      $data['error_warning'] = $this->error['warning'];
    } else {
      $data['error_warning'] = '';
    }

    if (isset($this->error['name'])) {
      $data['error_name'] = $this->error['name'];
    } else {
      $data['error_name'] = '';
    }


    //add
    $text_strings = array(
      'custom_text',
      'custom_image',

      'custom_first',
      'custom_second',
      'custom_third',
    );

    foreach ($text_strings as $text) {
      $data[$text] = $this->language->get($text);
    }

    //add

    //date
    $elements_names = array(
      'first',
      'second',
      'third',
    );
    foreach ($elements_names as $name) {
      if (isset($this->error["txt_$name"])) {
        $data["txt_$name"] = $this->error["txt_$name"];
      } else {
        $data["txt_$name"] = '';
      }
      if (isset($this->error["image_$name"])) {
        $data["error_image_$name"] = $this->error["image_$name"];
      } else {
        $data["error_image_$name"] = '';
      }
    }

    //after
    $after_el_names = array(
      'after',
      'gray',
    );
    foreach ($after_el_names as $name) {
      if (isset($this->error["txt_$name"])) {
        $data["txt_$name"] = $this->error["txt_$name"];
      } else {
        $data["txt_$name"] = '';
      }
    }

    //end add


    $data['breadcrumbs'] = array();

    $data['breadcrumbs'][] = array(
      'text' => $this->language->get('text_home'),
      'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
    );

    $data['breadcrumbs'][] = array(
      'text' => $this->language->get('text_extension'),
      'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
    );

    if (!isset($this->request->get['module_id'])) {
      $data['breadcrumbs'][] = array(
        'text' => $this->language->get('heading_title'),
        'href' => $this->url->link('extension/module/promo', 'user_token=' . $this->session->data['user_token'], true)
      );
    } else {
      $data['breadcrumbs'][] = array(
        'text' => $this->language->get('heading_title'),
        'href' => $this->url->link('extension/module/promo', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], true)
      );
    }

    if (!isset($this->request->get['module_id'])) {
      $data['action'] = $this->url->link('extension/module/promo', 'user_token=' . $this->session->data['user_token'], true);
    } else {
      $data['action'] = $this->url->link('extension/module/promo', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], true);
    }

    $data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

    if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      $module_info = $this->model_setting_module->getModule($this->request->get['module_id']);
    }

    //add
    foreach ($elements_names as $name) {
      if (isset($this->request->post["txt_$name"])) {
        $data["txt_$name"] = $this->request->post["txt_$name"];
      } elseif (!empty($module_info)) {
        $data["txt_$name"] = $module_info["txt_$name"];
      } else {
        $data["txt_$name"] = '';
      }
      if (isset($this->request->post['icon_date'])) {
        $data["image_$name"] = $this->request->post["image_$name"];
      } elseif (!empty($module_info)) {
        $data["image_$name"] = $module_info["image_$name"];
      } else {
        $data["image_$name"] = '';
      }
    }

    //after
    foreach ($after_el_names as $name) {
      if (isset($this->request->post["txt_$name"])) {
        $data["txt_$name"] = $this->request->post["txt_$name"];
      } elseif (!empty($module_info)) {
        $data["txt_$name"] = $module_info["txt_$name"];
      } else {
        $data["txt_$name"] = '';
      }
    }

    //

    if (isset($this->request->post['name'])) {
      $data['name'] = $this->request->post['name'];
    } elseif (!empty($module_info)) {
      $data['name'] = $module_info['name'];
    } else {
      $data['name'] = '';
    }

    if (isset($this->request->post['status'])) {
      $data['status'] = $this->request->post['status'];
    } elseif (!empty($module_info)) {
      $data['status'] = $module_info['status'];
    } else {
      $data['status'] = '';
    }

    $data['header'] = $this->load->controller('common/header');
    $data['column_left'] = $this->load->controller('common/column_left');
    $data['footer'] = $this->load->controller('common/footer');

    $this->response->setOutput($this->load->view('extension/module/promo', $data));

  }

  protected function validate()
  {

    if (!$this->user->hasPermission('modify', 'extension/module/promo')) {
      $this->error['warning'] = $this->language->get('error_permission');
    }

    if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
      $this->error['name'] = $this->language->get('error_name');
    }

    //add
    if ((utf8_strlen($this->request->post['txt_first']) < 3) || (utf8_strlen($this->request->post['txt_first']) > 800)) {
      $this->error['txt_first'] = $this->language->get('error_txt_first');
    }
    if ((utf8_strlen($this->request->post['image_first']) < 3) || (utf8_strlen($this->request->post['image_first']) > 64)) {
      $this->error['image_first'] = $this->language->get('error_image_first');
    }

    if ((utf8_strlen($this->request->post['txt_second']) < 3) || (utf8_strlen($this->request->post['txt_second']) > 800)) {
      $this->error['txt_second'] = $this->language->get('error_txt_second');
    }
    if ((utf8_strlen($this->request->post['image_second']) < 3) || (utf8_strlen($this->request->post['image_second']) > 64)) {
      $this->error['image_second'] = $this->language->get('error_image_second');
    }

    if ((utf8_strlen($this->request->post['txt_third']) < 3) || (utf8_strlen($this->request->post['txt_third']) > 800)) {
      $this->error['txt_third'] = $this->language->get('error_txt_third');
    }
    if ((utf8_strlen($this->request->post['image_third']) < 3) || (utf8_strlen($this->request->post['image_third']) > 64)) {
      $this->error['image_third'] = $this->language->get('error_image_third');
    }

    //after
    if ((utf8_strlen($this->request->post['txt_after']) < 3) || (utf8_strlen($this->request->post['txt_after']) > 800)) {
      $this->error['txt_after'] = $this->language->get('error_txt_after');
    }
    if ((utf8_strlen($this->request->post['txt_gray']) < 3) || (utf8_strlen($this->request->post['txt_gray']) > 800)) {
      $this->error['txt_gray'] = $this->language->get('error_txt_gray');
    }

    //end add

    return !$this->error;

  }

}