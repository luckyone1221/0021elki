<?php include DIR_TEMPLATE.'module/universal_import_functions.tpl'; ?>
  
  <ul class="nav nav-tabs">
    <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $_language->get('tab_general'); ?></a></li>
    <li><a href="#tab-address" data-toggle="tab"><?php echo $_language->get('tab_address'); ?></a></li>
    <li class="pull-right"><a href="#tab-functions" data-toggle="tab"><?php echo $_language->get('tab_functions'); ?></a></li>
  </ul>
  
  <div class="tab-content alternateColors">
    <div class="tab-pane active" id="tab-general">
      <?php if(isset($profile['item_identifier']) && $profile['item_identifier'] == $type.'_id') dataField($type.'_id', $_language->get('entry_'.$type.'_id'), $columns, $profile, $_language); ?>
      <?php dataField('customer_group_id', $_language->get('entry_customer_group'), $columns, $profile, $_language, 'select', $customer_groups); ?>
      <?php dataField('firstname', $_language->get('entry_firstname'), $columns, $profile, $_language); ?>
      <?php dataField('lastname', $_language->get('entry_lastname'), $columns, $profile, $_language); ?>
      <?php dataField('email', $_language->get('entry_email'), $columns, $profile, $_language); ?>
      <?php dataField('telephone', $_language->get('entry_telephone'), $columns, $profile, $_language); ?>
      <?php dataField('fax', $_language->get('entry_fax'), $columns, $profile, $_language); ?>
      <?php /* dataField('custom_field', $_language->get('entry_custom_field'), $columns, $profile, $_language); */ ?>
      <?php /* dataField('password', $_language->get('entry_password'), $columns, $profile, $_language); */ ?>
      <div class="form-group" style="margin-bottom:0">
        <label class="col-sm-2 control-label"><?php echo $_language->get('entry_password'); ?></label>
        <div class="col-md-4"><?php dataField('password', null, $columns, $profile, $_language); ?></div>
        <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $_language->get('entry_pwd_hash_i'); ?>"><?php echo $_language->get('entry_pwd_hash'); ?></span></label>
        <div class="col-md-4">
          <input type="radio" class="switch" name="pwd_hash" onclick="javascript:$('#pwd_salt').slideUp();"   value="0" data-label="<?php echo $_language->get('text_pwd_clear'); ?>" <?php if (empty($profile['pwd_hash'])) echo 'checked'; ?>/>
          <input type="radio" class="switch" name="pwd_hash" onclick="javascript:$('#pwd_salt').slideDown();" value="1" data-label="<?php echo $_language->get('text_pwd_hash'); ?>" <?php if (!empty($profile['pwd_hash'])) echo 'checked'; ?>/>
        </div>
      </div>
      <hr class="dotted"/>
      <div id="pwd_salt"<?php if (empty($profile['pwd_hash'])) echo ' style="display:none"';?>><?php dataField('salt', $_language->get('entry_salt'), $columns, $profile, $_language); ?></div>
      <?php dataField('newsletter', $_language->get('entry_newsletter'), $columns, $profile, $_language, 'checkbox'); ?>
      <?php dataField('status', $_language->get('entry_status'), $columns, $profile, $_language, 'enabled'); ?>
      <?php dataField('approved', $_language->get('entry_approved'), $columns, $profile, $_language, 'radio'); ?>
      <?php dataField('safe', $_language->get('entry_safe'), $columns, $profile, $_language, 'radio'); ?>
    </div>
    
    <div class="tab-pane" id="tab-address">
      <?php dataField('address][1][firstname', $_language->get('entry_firstname'), $columns, $profile, $_language); ?>
      <?php dataField('address][1][lastname', $_language->get('entry_lastname'), $columns, $profile, $_language); ?>
      <?php dataField('address][1][company', $_language->get('entry_company'), $columns, $profile, $_language); ?>
      <?php dataField('address][1][address_1', $_language->get('entry_address_1'), $columns, $profile, $_language); ?>
      <?php dataField('address][1][address_2', $_language->get('entry_address_2'), $columns, $profile, $_language); ?>
      <?php dataField('address][1][city', $_language->get('entry_city'), $columns, $profile, $_language); ?>
      <?php dataField('address][1][postcode', $_language->get('entry_postcode'), $columns, $profile, $_language); ?>
      <?php dataField('address][1][country_id', $_language->get('entry_country'), $columns, $profile, $_language); ?>
      <?php dataField('address][1][zone_id', $_language->get('entry_zone'), $columns, $profile, $_language); ?>
      <?php dataField('address][1][default', $_language->get('entry_default'), $columns, $profile, $_language, 'radio'); ?>
    </div>
    
    <div class="tab-pane" id="tab-functions">
    
      <ul class="nav nav-pills nav-stacked col-md-2">
        <li class="active"><a href="#tab-extra-func-1" data-toggle="pill"><?php echo $_language->get('tab_functions'); ?></a></li>
        <li><a href="#tab-extra-func-2" data-toggle="pill"><?php echo $_language->get('tab_extra'); ?></a></li>
        <li><a href="#tab-extra-func-4" data-toggle="pill"><?php echo $_language->get('tab_disable_cfg'); ?></a></li>
      </ul>
      <div class="tab-content col-md-10" style="min-height:400px;padding-bottom:120px">
        <div class="tab-pane active" id="tab-extra-func-1">
          <div class="well">
            <h4><?php echo $_language->get('tab_functions'); ?></h4>
            <p><?php echo $_language->get('info_extra_functions'); ?></p>
          </div>
          
          <?php extraImportFunctions($columns, $profile, $_language, $languages); ?>
        </div>
        <div class="tab-pane" id="tab-extra-func-2">
          <div class="well">
            <h4><?php echo $_language->get('tab_extra'); ?></h4>
            <p><?php echo $_language->get('info_extra_field'); ?></p>
          </div>
          
          <?php dataField('_extra_', $_language->get('entry_extra'), $columns, $profile, $_language); ?>
          <?php if (!empty($profile['extra'])) { foreach ($profile['extra'] as $extra) { ?>
            <?php dataField($extra, $_language->get('entry_extra'), $columns, $profile, $_language); ?>
          <?php }} ?>
          <div class="row">
            <div class="col-md-offset-2 col-md-7">
              <button type="button" class="btn btn-success btn-block add-extra"><i class="fa fa-plus"></i> <?php echo $_language->get('text_add_extra_field'); ?></button>
            </div>
          </div>
          
          <hr class="dotted"/>
          
          <?php dataFieldML('_extra_', $_language->get('entry_extra_ml'), $columns, $profile, $_language, $languages, $type); ?>
          <?php if (!empty($profile['extraml'])) { foreach ($profile['extraml'] as $extra) { ?>
            <?php dataFieldML($extra, $_language->get('entry_extra_ml'), $columns, $profile, $_language, $languages, $type); ?>
          <?php }} ?>
          <div class="row">
            <div class="col-md-offset-2 col-md-7">
              <button type="button" class="btn btn-success btn-block add-extra-ml"><i class="fa fa-plus"></i> <?php echo $_language->get('text_add_extra_field_ml'); ?></button>
            </div>
          </div>
        </div>
        <div class="tab-pane" id="tab-extra-func-4">
          <div class="well">
            <h4><?php echo $_language->get('tab_disable_cfg'); ?></h4>
            <p><?php echo $_language->get('info_disable_cfg'); ?></p>
          </div>
          
          <div class="row">
            <label class="col-sm-2 control-label"><?php echo $_language->get('entry_disable_config'); ?></label>
            <div class="col-md-8">
              <input class="form-control" type="text" name="disable_cfg" value="<?php echo isset($profile['disable_cfg']) ? $profile['disable_cfg'] : ''; ?>" placeholder="<?php echo $_language->get('placeholder_disable_config'); ?>"/>
            </div>
          </div>
        </div>
      </div>
    </div>
    
  </div>
  
  <hr />

  <div class="pull-right">
    <button type="button" class="btn btn-default cancel" data-step="3"><i class="fa fa-reply"></i> <?php echo $_language->get('text_previous_step'); ?></button>
    <button type="button" class="btn btn-success submit" data-step="3"><i class="fa fa-check"></i> <?php echo $_language->get('text_next_step'); ?></button>
  </div>
<div class="spacer"></div>