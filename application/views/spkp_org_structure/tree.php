<script>
	$(function(){
       $("#bar_profile").addClass("active open");
       $("#struktur_organisasi").addClass("active");
      
    }); 		
</script>
<div class="row-fluid">
   <div class="span12">
	   <h3 class="page-title">
		 Struktur Organisasi 
	   </h3>
   </div>
</div>
<iframe id="preview-frame" src="<?php echo base_url();?>spkp_org_structure/tree" name="preview-frame" frameborder="0" noresize="noresize" height="900" width="100%"></iframe>