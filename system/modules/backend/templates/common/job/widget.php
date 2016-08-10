<div class="modal show">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
        <div id="job-widget-<?php echo $this->escape($job['id']); ?>" class="job-widget">
          <?php if (!empty($job['title'])) { ?>
          <div class="title"><?php echo $this->escape($job['title']); ?></div>
          <?php } ?>
          <div class="progress">
            <div class="progress-bar active progress-bar-striped" style="width:0%">
            </div>
          </div>
          <div class="message">
            <?php if (!empty($job['message']['start'])) { ?>
            <span class="start"><?php echo $this->xss($job['message']['start']); ?></span>
            <?php } ?>
          </div>
        </div>   
      </div>
    </div>
  </div>
</div>
<div class="modal-backdrop fade in"></div>
<script>
    GplCart.job();
</script>