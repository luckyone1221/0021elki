<?php

class ControllerExtensionModuleVantage extends Controller
{

  private $error = array();

  public function index()
  {

    $this->load->language('extension/module/vantage');

    $this->document->setTitle($this->language->get('heading_title'));

    $this->load->model('setting/module');

    if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
      if (!isset($this->request->get['module_id'])) {
        $this->model_setting_module->addModule('vantage', $this->request->post);
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
      'custom_icon',
      'custom_date',
      'custom_delivery',
      'custom_shedule',
      'custom_quality',
    );

    foreach ($text_strings as $text) {
      $data[$text] = $this->language->get($text);
    }


    //add

    //date
    if (isset($this->error['txt_date'])) {
      $data['error_txt_date'] = $this->error['txt_date'];
    } else {
      $data['error_txt_date'] = '';
    }
    if (isset($this->error['icon_date'])) {
      $data['error_icon_date'] = $this->error['icon_date'];
    } else {
      $data['error_icon_date'] = '';
    }
    //delivery
    if (isset($this->error['txt_delivery'])) {
      $data['error_txt_delivery'] = $this->error['txt_delivery'];
    } else {
      $data['error_txt_delivery'] = '';
    }
    if (isset($this->error['icon_delivery'])) {
      $data['error_icon_delivery'] = $this->error['icon_delivery'];
    } else {
      $data['error_icon_delivery'] = '';
    }
    //shedule
    if (isset($this->error['txt_shedule'])) {
      $data['error_txt_shedule'] = $this->error['txt_shedule'];
    } else {
      $data['error_txt_shedule'] = '';
    }
    if (isset($this->error['icon_shedule'])) {
      $data['error_icon_shedule'] = $this->error['icon_shedule'];
    } else {
      $data['error_icon_shedule'] = '';
    }
    //quality
    if (isset($this->error['txt_quality'])) {
      $data['error_txt_quality'] = $this->error['txt_quality'];
    } else {
      $data['error_txt_quality'] = '';
    }
    if (isset($this->error['icon_quality'])) {
      $data['error_icon_quality'] = $this->error['icon_quality'];
    } else {
      $data['error_icon_quality'] = '';
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
        'href' => $this->url->link('extension/module/vantage', 'user_token=' . $this->session->data['user_token'], true)
      );
    } else {
      $data['breadcrumbs'][] = array(
        'text' => $this->language->get('heading_title'),
        'href' => $this->url->link('extension/module/vantage', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], true)
      );
    }

    if (!isset($this->request->get['module_id'])) {
      $data['action'] = $this->url->link('extension/module/vantage', 'user_token=' . $this->session->data['user_token'], true);
    } else {
      $data['action'] = $this->url->link('extension/module/vantage', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], true);
    }

    $data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

    if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      $module_info = $this->model_setting_module->getModule($this->request->get['module_id']);
    }

    //add

    //date
    if (isset($this->request->post['txt_date'])) {
      $data['txt_date'] = $this->request->post['txt_date'];
    } elseif (!empty($module_info)) {
      $data['txt_date'] = $module_info['txt_date'];
    } else {
      $data['txt_date'] = '';
    }
    if (isset($this->request->post['icon_date'])) {
      $data['icon_date'] = $this->request->post['icon_date'];
    } elseif (!empty($module_info)) {
      $data['icon_date'] = $module_info['icon_date'];
    } else {
      $data['icon_date'] = '';
    }
    //delivery
    if (isset($this->request->post['txt_delivery'])) {
      $data['txt_delivery'] = $this->request->post['txt_delivery'];
    } elseif (!empty($module_info)) {
      $data['txt_delivery'] = $module_info['txt_delivery'];
    } else {
      $data['txt_delivery'] = '';
    }
    if (isset($this->request->post['icon_delivery'])) {
      $data['icon_delivery'] = $this->request->post['icon_delivery'];
    } elseif (!empty($module_info)) {
      $data['icon_delivery'] = $module_info['icon_delivery'];
    } else {
      $data['icon_delivery'] = '';
    }
    //shedule
    if (isset($this->request->post['txt_shedule'])) {
      $data['txt_shedule'] = $this->request->post['txt_shedule'];
    } elseif (!empty($module_info)) {
      $data['txt_shedule'] = $module_info['txt_shedule'];
    } else {
      $data['txt_shedule'] = '';
    }
    if (isset($this->request->post['icon_shedule'])) {
      $data['icon_shedule'] = $this->request->post['icon_shedule'];
    } elseif (!empty($module_info)) {
      $data['icon_shedule'] = $module_info['icon_shedule'];
    } else {
      $data['icon_shedule'] = '';
    }
    //quality
    if (isset($this->request->post['txt_quality'])) {
      $data['txt_quality'] = $this->request->post['txt_quality'];
    } elseif (!empty($module_info)) {
      $data['txt_quality'] = $module_info['txt_quality'];
    } else {
      $data['txt_quality'] = '';
    }
    if (isset($this->request->post['icon_quality'])) {
      $data['icon_quality'] = $this->request->post['icon_quality'];
    } elseif (!empty($module_info)) {
      $data['icon_quality'] = $module_info['icon_quality'];
    } else {
      $data['icon_quality'] = '';
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

    $this->response->setOutput($this->load->view('extension/module/vantage', $data));

  }

  protected function validate()
  {

    if (!$this->user->hasPermission('modify', 'extension/module/vantage')) {
      $this->error['warning'] = $this->language->get('error_permission');
    }

    if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
      $this->error['name'] = $this->language->get('error_name');
    }

    //add

    //date
    if ((utf8_strlen($this->request->post['txt_date']) < 3) || (utf8_strlen($this->request->post['txt_date']) > 64)) {
      $this->error['txt_date'] = $this->language->get('error_txt_date');
    }
    if ((utf8_strlen($this->request->post['icon_date']) < 3) || (utf8_strlen($this->request->post['icon_date']) > 64)) {
      $this->error['icon_date'] = $this->language->get('error_icon_date');
    }
    //delivery
    if ((utf8_strlen($this->request->post['txt_delivery']) < 3) || (utf8_strlen($this->request->post['txt_delivery']) > 64)) {
      $this->error['txt_delivery'] = $this->language->get('error_txt_delivery');
    }
    if ((utf8_strlen($this->request->post['icon_delivery']) < 3) || (utf8_strlen($this->request->post['icon_delivery']) > 64)) {
      $this->error['icon_delivery'] = $this->language->get('error_icon_delivery');
    }
    //shedule
    if ((utf8_strlen($this->request->post['txt_shedule']) < 3) || (utf8_strlen($this->request->post['txt_shedule']) > 64)) {
      $this->error['txt_shedule'] = $this->language->get('error_txt_shedule');
    }
    if ((utf8_strlen($this->request->post['icon_shedule']) < 3) || (utf8_strlen($this->request->post['icon_shedule']) > 64)) {
      $this->error['icon_shedule'] = $this->language->get('error_icon_shedule');
    }
    //quality
    if ((utf8_strlen($this->request->post['txt_quality']) < 3) || (utf8_strlen($this->request->post['txt_quality']) > 64)) {
      $this->error['txt_quality'] = $this->language->get('error_txt_quality');
    }
    if ((utf8_strlen($this->request->post['icon_quality']) < 3) || (utf8_strlen($this->request->post['icon_quality']) > 64)) {
      $this->error['icon_quality'] = $this->language->get('error_icon_quality');
    }
    //end add

    return !$this->error;

  }

}