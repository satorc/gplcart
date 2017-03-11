<?php
/**
 * @package GPL Cart core
 * @author Iurii Makukh <gplcart.software@gmail.com>
 * @copyright Copyright (c) 2015, Iurii Makukh
 * @license https://www.gnu.org/licenses/gpl.html GNU/GPLv3
 */
?>
<div class="panel panel-default">
  <?php if ($this->access('language_add')) { ?>
  <div class="panel-heading clearfix">
    <div class="btn-toolbar pull-right">
      <a class="btn btn-default add" href="<?php echo $this->url("admin/settings/language/add"); ?>">
        <i class="fa fa-plus"></i> <?php echo $this->text('Add'); ?>
      </a>
    </div>
  </div>
  <?php } ?>
  <div class="panel-body table-responsive">
    <table class="table table-condensed languages">
      <thead>
        <tr>
          <th><?php echo $this->text('Name'); ?></th>
          <th><?php echo $this->text('Native name'); ?></th>
          <th><?php echo $this->text('Code'); ?></th>
          <th><?php echo $this->text('Enabled'); ?></th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($languages as $code => $language) { ?>
        <tr data-code="<?php echo $code; ?>">
          <td class="middle">
            <?php echo $this->escape($language['name']); ?>
            <?php if (!empty($language['default'])) { ?>
            (<?php echo mb_strtolower($this->text('Default')); ?>)
            <?php } ?>
          </td>
          </td>
          <td class="middle"><?php echo $this->escape($language['native_name']); ?></td>
          <td class="middle"><?php echo $this->escape($code); ?></td>
          <td class="middle">
            <?php if (empty($language['status'])) { ?>
            <i class="fa fa-square-o"></i>
            <?php } else { ?>
            <i class="fa fa-check-square-o"></i>
            <?php } ?>
          </td>
          <td class="middle">
            <ul class="list-inline">
              <?php if ($this->access('language_edit')) { ?>
              <li>
                <a href="<?php echo $this->url("admin/settings/language/edit/$code"); ?>">
                  <?php echo mb_strtolower($this->text('Edit')); ?>
                </a>
              </li>
              <li>
                <a href="<?php echo $this->url('', array('refresh' => $code)); ?>" onclick="return confirm(GplCart.text('Are you sure?'));">
                  <?php echo mb_strtolower($this->text('Clear cache')); ?>
                </a>
              </li>
              <?php } ?>
              <?php if($this->access('translation_add') && $this->access('file_upload')) { ?>
              <li>
                <a href="<?php echo $this->url("admin/settings/language/upload-translation/$code"); ?>">
                  <?php echo mb_strtolower($this->text('Upload translation')); ?>
                </a>
              </li>
              <?php } ?>
            </ul>
          </td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</div>