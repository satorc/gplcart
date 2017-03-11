<?php
/**
 * @package GPL Cart core
 * @author Iurii Makukh <gplcart.software@gmail.com>
 * @copyright Copyright (c) 2015, Iurii Makukh
 * @license https://www.gnu.org/licenses/gpl.html GNU/GPLv3
 */
?>
<div class="panel panel-default">
  <div class="panel-body table-responsive">
    <table class="table table-condensed shipping-methods">
      <thead>
        <tr>
          <th><?php echo $this->text('ID'); ?></th>
          <th><?php echo $this->text('Name'); ?></th>
          <th><?php echo $this->text('Description'); ?></th>
          <th><?php echo $this->text('Module'); ?></th>
          <th><?php echo $this->text('Status'); ?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($methods as $id => $method) { ?>
        <tr>
          <td><?php echo $this->escape($id); ?></td>
          <td><?php echo $this->escape($method['title']); ?></td>
          <td><?php echo empty($method['description']) ? '' : $this->escape($method['description']); ?></td>
          <td><?php echo empty($method['module']) ? $this->text('Unknown') : $this->escape($method['module']); ?></td>
          <td><?php echo empty($method['status']) ? $this->text('Disabled') : $this->text('Enabled'); ?></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</div>