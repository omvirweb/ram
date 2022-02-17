<?php if($this->session->flashdata('success') === true  && $this->session->flashdata('message') != '') { ?>
<script type="text/javascript">
    $(document).ready(function(){
        show_notify("<?= $this->session->flashdata('message') ?>", true);
    });
</script>
<?php }
if($this->session->flashdata('success') === false  && $this->session->flashdata('message') != '') { ?>
<script type="text/javascript">
    $(document).ready(function(){
        show_notify("<?= $this->session->flashdata('message') ?>", false);
    });
</script>
<?php } ?>