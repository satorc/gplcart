<?php
/**
 * @package GPL Cart core
 * @author Iurii Makukh <gplcart.software@gmail.com>
 * @copyright Copyright (c) 2015, Iurii Makukh
 * @license https://www.gnu.org/licenses/gpl.html GNU/GPLv3
 */
?>
<?php if (!empty($aliases) || $_filtering) { ?>
<form method="post" id="aliases">
  <input type="hidden" name="token" value="<?php echo $_token; ?>">
  <div class="panel panel-default">
    <?php if($this->access('alias_delete')) { ?>
    <div class="panel-heading clearfix">
      <div class="btn-group pull-left">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
          <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
          <li>
            <a data-action="delete" data-action-confirm="<?php echo $this->text('Are you sure? It cannot be undone!'); ?>" href="#">
              <?php echo $this->text('Delete'); ?>
            </a>
          </li>
        </ul>
      </div>
    </div>
    <?php } ?>
    <div class="panel-body">
      <table class="table table-condensed aliases">
        <thead>
          <tr>
            <th>
              <input type="checkbox" id="select-all" value="1">
            </th>
            <th>
              <a href="<?php echo $sort_alias_id; ?>">
                <?php echo $this->text('ID'); ?> <i class="fa fa-sort"></i>
              </a>
            </th>
            <th>
              <a href="<?php echo $sort_alias; ?>">
                <?php echo $this->text('Alias'); ?> <i class="fa fa-sort"></i>
              </a>
            </th>
            <th>
              <a href="<?php echo $sort_id_key; ?>">
                <?php echo $this->text('Entity type'); ?> <i class="fa fa-sort"></i>
              </a>
            </th>
            <th>
              <a href="<?php echo $sort_id_value; ?>">
                <?php echo $this->text('Entity ID'); ?> <i class="fa fa-sort"></i>
              </a>
            </th>
            <th></th>
          </tr>
          <tr class="filters active">
            <th></th>
            <th></th>
            <th>
              <input class="form-control" name="alias" value="<?php echo $filter_alias; ?>" placeholder="<?php echo $this->text('Any'); ?>">
            </th>
            <th>
              <select name="id_key" class="form-control">
                <option value="any"><?php echo $this->text('Any'); ?></option>
                <?php foreach ($id_keys as $id_key) { ?>
                <option value="<?php echo $this->e($id_key); ?>"<?php echo $filter_id_key == $id_key ? ' selected' : '' ?>>
                <?php echo $this->e($id_key); ?>
                </option>
                <?php } ?>
              </select>
            </th>
            <th></th>
            <th>
              <button type="button" class="btn btn-default clear-filter" title="<?php echo $this->text('Reset filter'); ?>">
                <i class="fa fa-refresh"></i>
              </button>
              <button type="button" class="btn btn-default filter" title="<?php echo $this->text('Filter'); ?>">
                <i class="fa fa-search"></i>
              </button>
            </th>
          </tr>
        </thead>
        <tbody>
          <?php if ($_filtering && empty($aliases)) { ?>
          <tr>
            <td colspan="6">
              <?php echo $this->text('No results'); ?>
              <a href="#" class="clear-filter"><?php echo $this->text('Reset'); ?></a>
            </td>
          </tr>
          <?php } ?>
          <?php foreach ($aliases as $id => $alias) { ?>
          <tr>
            <td class="middle">
              <input type="checkbox" class="select-all" name="selected[]" value="<?php echo $id; ?>">
            </td>
            <td class="middle">
              <?php echo $this->e($id); ?>
            </td>
            <td class="middle">
              <?php echo $this->e($alias['alias']); ?>
            </td>
            <td class="middle">
              <?php echo $this->e($alias['entity']); ?>
            </td>
            <td class="middle">
              <?php echo $this->e($alias['id_value']); ?>
            </td>
            <td></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
      <?php echo $_pager; ?>
    </div>
  </div>
</form>
<?php } else { ?>
<div class="row">
  <div class="col-md-12">
    <?php echo $this->text('You have no aliases yet'); ?>
  </div>
</div>
<?php } ?>
