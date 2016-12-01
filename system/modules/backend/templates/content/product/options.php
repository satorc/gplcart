<div>
  <div id="option-form">
    <?php if (!empty($fields['option'])) { ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h4 class="panel-title">
          <?php echo $this->text('Options'); ?>
        </h4>
      </div>
      <div class="panel-body table-responsive">
        <table class="table table-condensed option">
          <thead>
            <tr>
              <?php foreach ($fields['option'] as $field_id => $option) { ?>
              <th class="active">
                <span<?php echo $option['required'] ? ' class="required"' : ''; ?>><?php echo $option['title']; ?></span>
                <div class="btn-group btn-group-sm pull-right">
                  <?php if ($this->access('field_add')) { ?>
                  <a target="_blank" href="<?php echo $this->url("admin/content/field/value/$field_id"); ?>" class="btn btn-default">
                    <i class="fa fa-plus"></i>
                  </a>
                  <?php } ?>
                  <a href="#" class="btn btn-default refresh-fields" data-field-type="option"><i class="fa fa-refresh"></i></a>
                </div>
              </th>
              <?php } ?>
              <th><?php echo $this->text('SKU'); ?></th>
              <th><?php echo $this->text('Price'); ?></th>
              <th><?php echo $this->text('Stock'); ?></th>
              <th><?php echo $this->text('Image'); ?></th>
              <th><?php echo $this->text('Action'); ?></th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($product['combination'])) { ?>
            <?php foreach ($product['combination'] as $row => $combination) { ?>
            <tr class="<?php echo $this->error("combination.$row", 'bg-danger'); ?>">
              <?php foreach ($fields['option'] as $field_id => $option) { ?>
              <td class="<?php echo $this->error("combination.$row", 'active'); ?>">
                <div class="<?php echo $this->error("combination.$row.fields.$field_id", 'has-error'); ?>">
                  <select data-field-id="<?php echo $option['field_id']; ?>" data-live-search="true" class="form-control selectpicker" name="product[combination][<?php echo $row; ?>][fields][<?php echo $field_id; ?>]">
                    <option value="" selected disabled><?php echo $this->text('Select'); ?></option>
                    <?php foreach ($option['values'] as $value) { ?>
                    <option value="<?php echo $value['field_value_id']; ?>"<?php echo (!empty($combination['fields']) && in_array($value['field_value_id'], $combination['fields'])) ? ' selected' : ''; ?>><?php echo $this->escape($value['title']); ?></option>
                    <?php } ?>
                  </select>
                  <div class="help-block">
                    <?php echo $this->error("combination.$row.fields.$field_id"); ?>
                  </div>
                </div>
              </td>
              <?php } ?>
              <td>
                <div class="<?php echo $this->error("combination.$row.sku", 'has-error'); ?>">
                  <input maxlength="255" class="form-control" name="product[combination][<?php echo $row; ?>][sku]" value="<?php echo isset($combination['sku']) ? $this->escape($combination['sku']) : ''; ?>" placeholder="<?php echo $this->text('Generate automatically'); ?>">
                  <div class="help-block">
                    <?php echo $this->error("combination.$row.sku"); ?>
                  </div>
                </div>
              </td>
              <td>
                <div class="<?php echo $this->error("combination.$row.price", 'has-error'); ?>">
                  <input class="form-control" name="product[combination][<?php echo $row; ?>][price]" value="<?php echo isset($combination['price']) ? $this->escape($combination['price']) : 0; ?>">
                  <div class="help-block"><?php echo $this->error("combination.$row.price"); ?></div>
                </div>
              </td>
              <td>
                <div class="<?php echo $this->error("combination.$row.stock", 'has-error'); ?>">
                  <input class="form-control" name="product[combination][<?php echo $row; ?>][stock]" value="<?php echo $combination['stock']; ?>">
                  <div class="help-block">
                    <?php echo $this->error("combination.$row.stock"); ?>
                  </div>
                </div>
              </td>
              <td>
                <?php if ($combination['thumb']) { ?>
                <a href="#" class="btn btn-default select-image"><img style="height:20px;width:20px;" src="<?php echo $this->escape($combination['thumb']); ?>"></a>
                <input type="hidden" name="product[combination][<?php echo $row; ?>][thumb]" value="<?php echo $this->escape($combination['thumb']); ?>">
                <?php } else { ?>
                <a href="#" class="btn btn-default select-image"><i class="fa fa-image"></i></a>
                <input type="hidden" name="product[combination][<?php echo $row; ?>][thumb]" value="">
                <?php } ?>
                <input type="hidden" name="product[combination][<?php echo $row; ?>][file_id]" value="<?php echo $combination['file_id']; ?>">
                <input type="hidden" name="product[combination][<?php echo $row; ?>][path]" value="<?php echo $this->escape($combination['path']); ?>">
              </td>
              <td><a href="#" class="btn btn-danger remove-option-combination"><i class="fa fa-trash"></i></a></td>
            </tr>
            <?php if($this->error("combination.$row.exists", true)) { ?>
            <tr class="bg-danger">
              <td colspan="<?php echo (count($fields['option']) + 5); ?>">
              <?php echo $this->escape($this->error("combination.$row.exists")); ?>
              </td>
            </tr>
            <?php } ?>
            <?php } ?>
            <?php } ?>
          </tbody>
          <tfoot>
            <tr>
              <?php $row = 0; ?>
              <?php foreach ($fields['option'] as $option) { ?>
              <td>
                <select data-field-id="<?php echo $option['field_id']; ?>" data-live-search="true" class="form-control selectpicker">
                  <option value="" selected disabled><?php echo $this->text('Select'); ?></option>
                  <?php foreach ($option['values'] as $value) { ?>
                  <option value="<?php echo $value['field_value_id']; ?>"><?php echo $this->escape($value['title']); ?></option>
                  <?php } ?>
                </select>
              </td>
              <?php } ?>
              <td><input class="form-control" value=""></td>
              <td><input class="form-control" value=""></td>
              <td><input class="form-control" value=""></td>
              <td><a href="#" class="btn btn-default select-image"><i class="fa fa-image"></i></a></td>
              <td><a href="#" class="btn btn-default add-option-combination"><i class="fa fa-plus"></i></a></td>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
    <?php } ?>
  </div>
</div>