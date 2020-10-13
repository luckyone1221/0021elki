<?php
// Heading
$_['heading_title']     = 'Промо';

// Text
$_['text_extension']    = 'Модули';
$_['text_success']      = 'Настройки успешно изменены!';
$_['text_edit']         = 'Редактирование';

// Entry
$_['entry_name']        = 'Название модуля';
$_['entry_status']      = 'Статус';

//custom
$_['custom_text']        = 'Текст';
$_['custom_image']       = 'Имя картинки(example.jpg)';
$_['custom_first']       = '1-я колонка';
$_['custom_second']      = '2-я колонка';
$_['custom_third']       = '3-я колонка';

// Error
$_['error_permission']  = 'У вас недостаточно прав для внесения изменений!';
$_['error_name']        = 'Название должно содержать от 3 до 64 символов!';


$elements_names = array(
  'first',
  'second',
  'third',
  'after',
  'gray',
);

foreach ($elements_names as $name) {
  $_["error_txt_$name"]    = 'Название должно содержать от 3 до 800 символов!';
  $_["error_image_$name"]  = 'Название должно содержать от 3 до 64 символов!';
}


//$_['error_']        = 'Название должно содержать от 3 до 64 символов!';




