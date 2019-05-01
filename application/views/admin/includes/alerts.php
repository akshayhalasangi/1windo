 <?php 
 //print_r($this->session->flashdata());
 
 if(!empty($this->session->flashdata())){
	
  ?>

 <div class="alert alert-<?= $this->session->flashdata('alertclass'); ?>" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true" class="la la-close"></span>
    </button>
    <strong><?= ucfirst($this->session->flashdata('alertclass')); ?></strong> <?= $this->session->flashdata('message'); ?>.
</div>
<?php }  