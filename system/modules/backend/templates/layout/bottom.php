<?php
/**
 * @package GPL Cart core
 * @author Iurii Makukh <gplcart.software@gmail.com>
 * @copyright Copyright (c) 2015, Iurii Makukh
 * @license https://www.gnu.org/licenses/gpl.html GNU/GPLv3
 */
?>
<footer class="footer hidden-print">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-6">
        <p class="text-muted small">
          &copy; <?php echo date('Y') == 2015 ? date('Y') : '2015 - ' . date('Y'); ?>
          <a href="http://gplcart.com">GPL Cart</a> v<?php echo GC_VERSION; ?>
        </p>	  
      </div>
    </div>
  </div>
</footer>
<?php if(!empty($_scripts_bottom)) { ?>
<?php foreach ($_scripts_bottom as $key => $info) { ?>
<?php if (!empty($info['text'])) { ?>
<script><?php echo $info['asset']; ?></script>
<?php } else { ?>
<script src="<?php echo $this->e($key); ?>"></script>
<?php } ?>
<?php } ?>
<?php } ?>