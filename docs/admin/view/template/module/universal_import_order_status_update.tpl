<?php include DIR_TEMPLATE.'module/universal_import_functions.tpl'; ?>
  
  <ul class="nav nav-tabs">
    <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $_language->get('tab_quick_update'); ?></a></li>
  </ul>
  <div class="tab-content alternateColors">
  
    <div class="tab-pane active" id="tab-general">
      <div class="well quickUpdateWell">
        <h4><?php echo $_language->get('tab_quick_update'); ?></h4>
        <p><?php echo $_language->get('text_quick_update_identifier'); ?></p>
        <?php
          if (!isset($profile['item_identifier'])) {
            echo 'Please select an item identifier in step 2';
          } else {
          if ($profile['item_identifier'] == $type.'_id') {
            dataField($type.'_id', $_language->get('entry_'.$type.'_id'), $columns, $profile, $_language);
          } else {
            dataField($profile['item_identifier'], $_language->get('entry_'.$profile['item_identifier']), $columns, $profile, $_language);
            }
          }
        ?>
      </div>
      <?php dataField('order_status_id', $_language->get('entry_order_status'), $columns, $profile, $_language, 'select', $order_statuses); ?>
      <?php dataField('tracking_no', $_language->get('entry_tracking_no'), $columns, $profile, $_language); ?>
      <?php dataField('tracking_url', $_language->get('entry_tracking_url'), $columns, $profile, $_language); ?>
      <?php dataField('notify', $_language->get('entry_notify'), $columns, $profile, $_language, 'radio'); ?>
      
      <ul class="nav nav-tabs nav-language">
      <?php $f=1; foreach ($languages as $language) { ?>
      <li class="tab-lang-<?php echo $language['language_id']; ?> <?php if($f) echo 'active'; $f=0; ?>"><a href=".tab-lang-<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="<?php echo $language['image']; ?>" alt=""/> <?php echo $language['name']; ?></a></li>
      <?php } ?>
      </ul>
      <div class="tab-content">
        <input type="hidden" name="columns[comment]" value=""/>
        <?php $f=1; foreach ($languages as $language) { ?>
        <div class="tab-lang-<?php echo $language['language_id']; ?> tab-pane <?php if($f) echo ' active'; $f=0; ?>">
          <div class="form-group" style="margin-bottom:0">
            <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $_language->get('help_field_order_status_comment'); ?>"><?php echo $_language->get('entry_order_status_comment'); ?></span></label>
            <div class="col-md-10">
              <textarea rows="8" name="defaults[comment][<?php echo $language['language_id']; ?>]" class="form-control"><?php if (isset($profile['defaults']['comment'][$language['language_id']])) echo $profile['defaults']['comment'][$language['language_id']]; ?></textarea>
            </div>
          </div>
          <!--<input type="text" name="defaults[comment]" value="<?php if (isset($profile['defaults']['comment'])) echo $profile['defaults']['comment']; ?>"/>-->
        </div>
        <?php } ?>
      </div>
      
      <hr class="dotted"/>
      <?php /*dataField('quantity', $_language->get('entry_quantity'), $columns, $profile, $_language, 'text'); ?>
      <?php dataField('status', $_language->get('entry_status'), $columns, $profile, $_language, 'enabled'); */?>
    </div>
    
  </div>
  
  <hr />

  <div class="pull-right">
    <button type="button" class="btn btn-default cancel" data-step="3"><i class="fa fa-reply"></i> <?php echo $_language->get('text_previous_step'); ?></button>
    <button type="button" class="btn btn-success submit" data-step="3"><i class="fa fa-check"></i> <?php echo $_language->get('text_next_step'); ?></button>
  </div>

<div class="spacer"></div>