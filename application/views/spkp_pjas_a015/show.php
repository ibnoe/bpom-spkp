<script type="text/javascript">
    $(document).ready(function(){
       $("#bar_datapjas").addClass("active open");
       $("#data_pjas").addClass("active");
	   $('#btn_tambah, #clearfilteringbutton, #refreshdatabutton, #printbutton, #excelbutton').jqxButton({ height: 25, theme: theme });

	   var source = {
			datatype: "json",
			type	: "POST",
			datafields: [
			{ name: 'urut'},
            { name: 'id', type: 'number' },
            { name: 'tahun', type: 'number' },
            { name: 'tanggal', type: 'date' },
            { name: 'nama_propinsi', type: 'string' },
            { name: 'penanggungjawab_nama', type: 'string' },
            { name: 'penanggungjawab_nip', type: 'string' }
        ],
		url: "<?php echo base_url(); ?>spkp_pjas_a015/json_form",
		cache: false,
		updaterow: function (rowid, rowdata, commit) {
			},
		filter: function(){
			$("#jqxgrid").jqxGrid('updatebounddata', 'filter');
		},
		sort: function(){
			$("#jqxgrid").jqxGrid('updatebounddata', 'sort');
		},
		root: 'Rows',
        pagesize: 10,
        beforeprocessing: function(data){		
			if (data != null){
				source.totalrecords = data[0].TotalRows;					
			}
		}
		};		
		var dataadapter = new $.jqx.dataAdapter(source, {
			loadError: function(xhr, status, error){
				alert(error);
			}
		});
     
		$("#jqxgrid").jqxGrid(
		{		
			width: '100%',
			selectionmode: 'singlerow',
			source: dataadapter, theme: theme,columnsresize: true,showtoolbar: true, pagesizeoptions: ['10', '25', '50', '100', '200'],
			showfilterrow: true, filterable: true, sortable: true, autoheight: true, pageable: true, virtualmode: true, editable: false,
			rendergridrows: function(obj)
			{
				return obj.data;    
			},
			columns: [
				{ text: '#', align: 'center', filtertype: 'none', sortable: false, width: '5%', cellsrenderer: function (row) {
				     var dataRecord = $("#jqxgrid").jqxGrid('getrowdata', row);
                     if({add_permission}==true){
                        return "<div style='text-align: center;padding:3px'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>public/images/del.gif' onclick='dodel_form("+dataRecord.urut+","+dataRecord.id+")'></a>  <a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>public/images/edt.gif' onclick='edit_form("+dataRecord.id+");'></a></div>";
                     }else{
                        return "<div style='text-align: center;padding:3px'>&nbsp;</div>";
                     }
				    }
                },
				//Kolom No otomatis menampilkan filed 'urut' hasil query crud->jqxGrid, gunakan format ini untuk mencegah error filter data
				{ text: 'No', align: 'center', filtertype: 'none', width: '4%', cellsrenderer: function (row) {
				     var dataRecord = $("#jqxgrid").jqxGrid('getrowdata', row);
                     return "<div style='width:100%;padding-top:4px;text-align:center'>"+dataRecord.urut+"</div>";
                 }
                },
				// END
                { text: 'Tahun', datafield: 'tahun', columntype: 'textbox', filtertype: 'textbox', width: '10%' },
                { text: 'Tanggal', datafield: 'tanggal', columntype: 'date', filtertype: 'date', cellsformat: 'yyyy-MM-dd', width: '9%' },
                { text: 'Provinsi', datafield: 'nama_propinsi', columntype: 'textbox', filtertype: 'list', width: '25%' },
                { text: 'Nama', datafield: 'penanggungjawab_nama', columntype: 'textbox', filtertype: 'textbox', width: '30%' },
                { text: 'NIP', datafield: 'penanggungjawab_nip', columntype: 'textbox', filtertype: 'textbox', width: '17%' }
            ]
		});
        
		$('#clearfilteringbutton').click(function () {
			$("#jqxgrid").jqxGrid('clearfilters');
		});
        
 		$('#refreshdatabutton').click(function () {
			$("#jqxgrid").jqxGrid('updatebounddata', 'cells');
		});

	   $('#btn_tambah').click(function(){
			$("#popup_content").html("<div style='text-align:center'><br><br><br><br><img src='<?php echo base_url();?>media/images/indicator.gif' alt='loading content.. '><br>loading</div>");
			var offset = $(this).offset();
			$("#popup").jqxWindow({
				theme: theme, resizable: false, position: { x: offset.left + 140, y: offset.top},
                width: 550,
                height: 405,
    			isModal: true, autoOpen: false, modalOpacity: 0.2
			});
            $("#popup").jqxWindow('open');
			$.get("<?php echo base_url();?>spkp_pjas_a015/add_form" , function(response) {
				$("#popup_content").html("<div>"+response+"</div>");
			});
		});

		$('#printbutton').click(function () {
  			$("#popup_content").html("<div style='text-align:center'><br><br><br><br><img src='<?php echo base_url();?>media/images/indicator.gif' alt='loading content.. '><br>loading</div>");

			//Collecting filter information into querystring
			var datastring="pagenum=0&pagesize=9999&groupscount=0&groupscount=10&recordstartindex=0&";
			var filter = $("#jqxgrid").jqxGrid('getfilterinformation');
			k=0;
			for (i = 0; i < filter.length; i++) {
				var filters = filter[i].filter.getfilters();
				var datafield = filter[i].filtercolumn;
				for (j = 0; j < filters.length; j++) {
					if(filters[j].type=='datefilter'){
						var now = new Date(filters[j].value);
						var thn = now.getFullYear();
						var bln = now.getMonth()+1;
						var tgl = now.getDate();
						var jam = now.getHours();
						var menit = now.getMinutes();
						var detik = now.getSeconds();

						if(bln<10) bln = "0"+bln;
						if(tgl<10) tgl = "0"+tgl;
						if(jam<10) jam = "0"+jam;
						if(menit<10) menit = "0"+menit;
						if(detik<10) detik = "0"+detik;
						var value = thn+"/"+bln+"/"+tgl+" "+jam+":"+menit+":"+detik;
					}else{
						var value = filters[j].value;
					}
					var condition = filters[j].condition;
					var operator = filters[j].operator;
					datastring +='&filtervalue'+k+'=' + value + '&filtercondition'+k+'=' + condition + '&filterdatafield'+k+'=' + datafield+ '&filteroperator'+k+'='+ operator;
					k++;
				}
				datastring +='&'+datafield+'operator=and';
			}
			//END - Collecting filter information into querystring

			//Creating html view
			$.ajax({ 
				type: "POST",
				data: datastring+'&filterscount='+k,
				url: "<?php echo base_url();?>spkp_pjas_a015/html_form",
				success: function(response){
					$("#popup_content").html(response);
				}
			 }); 		

			var offset = $(this).offset();
			$("#popup").jqxWindow({
			//	theme: theme, resizable: true, position: { x: offset.left + 250, y: offset.top},
                theme: theme, resizable: true, position: { x: 200, y: offset.top},
				width: 900,
				height: 500,
				isModal: true, autoOpen: false, cancelButton: $("#Cancel"), modalOpacity: 0.2
			});
			$("#popup").jqxWindow('open');
			//END - Creating html view
		});

		$('#excelbutton').click(function () {
			//Collecting filter information into querystring
			var datastring="pagenum=0&pagesize=9999&groupscount=0&groupscount=10&recordstartindex=0&";
			var filter = $("#jqxgrid").jqxGrid('getfilterinformation');
			k=0;
			for (i = 0; i < filter.length; i++) {
				var filters = filter[i].filter.getfilters();
				var datafield = filter[i].filtercolumn;
				for (j = 0; j < filters.length; j++) {
					if(filters[j].type=='datefilter'){
						var now = new Date(filters[j].value);
						var thn = now.getFullYear();
						var bln = now.getMonth()+1;
						var tgl = now.getDate();
						var jam = now.getHours();
						var menit = now.getMinutes();
						var detik = now.getSeconds();

						if(bln<10) bln = "0"+bln;
						if(tgl<10) tgl = "0"+tgl;
						if(jam<10) jam = "0"+jam;
						if(menit<10) menit = "0"+menit;
						if(detik<10) detik = "0"+detik;
						var value = thn+"/"+bln+"/"+tgl+" "+jam+":"+menit+":"+detik;
					}else{
						var value = filters[j].value;
					}
					var condition = filters[j].condition;
					var operator = filters[j].operator;
					datastring +='&filtervalue'+k+'=' + value + '&filtercondition'+k+'=' + condition + '&filterdatafield'+k+'=' + datafield+ '&filteroperator'+k+'='+ operator;
					k++;
				}
				datastring +='&'+datafield+'operator=and';
			}
			//END - Collecting filter information into querystring

			//Creating html view
			$.ajax({ 
				type: "POST",
				data: datastring+'&filterscount='+k,
				url: "<?php echo base_url();?>spkp_pjas_a015/excel_form",
				success: function(response){
					//Download excel file response
					window.open("<?php echo base_url();?>spkp_loader/"+response);
				}
			 }); 		
			//END - Creating excel file
		});
    });
    
    function edit_form(id){
		window.location.href = '<?php echo base_url(); ?>spkp_pjas_a015/edit/'+id;
	}

    function save_add_form_dialog(content){
		$.ajax({ 
			type: "POST",
			url: "<?php echo base_url()?>index.php/spkp_pjas_a015/doadd_form",
			data: content,
			success: function(response){
			     var data = response.split("_");
				 if(data[0]=="1"){
					 close_dialog();
					 /*
                     $('#clearfilteringbutton').click();
                     $('#refreshdatabutton').click();
					 $.notific8('Notification', {
					  life: 5000,
					  message: 'Save data succesfully.',
					  heading: 'Saving data',
					  theme: 'lime'
					}); */
                    
                    window.location.href = "<?php echo base_url(); ?>spkp_pjas_a015/edit/"+data[1];
				 }else{
					 $.notific8('Notification', {
					  life: 5000,
					  message: response,
					  heading: 'Saving data FAIL',
					  theme: 'red'
					});
				 }
			}
		 }); 		
	}
    
    function dodel_form(urut,id){
		if(confirm("Anda yakin akan menghapus data ("+urut+") ini?")){
			$.ajax({ 
				type: "POST",
				url: "<?php echo base_url()?>index.php/spkp_pjas_a015/dodel_form/"+id,
				success: function(response){
					 if(response=="1"){
						 $('#clearfilteringbutton').click();
                         $.notific8('Notification', {
						  life: 5000,
						  message: 'Delete data succesfully.',
						  heading: 'Delete data',
						  theme: 'lime'
						});
					 }else{
						 $.notific8('Notification', {
						  life: 5000,
						  message: response,
						  heading: 'Delete data FAIL',
						  theme: 'red'
						});
					 }
				}
			 }); 		
		}
	}

	function close_dialog(){
		$("#popup").jqxWindow("close");
	}
</script>
<div class="row-fluid">
   <div class="span12">
	   <h3 class="page-title">
		 {title}
	   </h3>
   </div>
</div>
<div id="popup" style="display:none"><div id="popup_title">Form A015</div><div id="popup_content">{popup}</div></div>
<div>
	<div style="width:100%;background-color:#DDDDDD;-moz-border-radius:5px;border-radius:5px;padding:2px;border:3px solid #ebebeb;">
        <?php if($add_permission){?><input style="padding: 5px;" value="Tambah" id="btn_tambah" type="button"/><?php } ?>
		<input style="padding: 5px;" value=" Clear Filter " id="clearfilteringbutton" type="button" />
		<input style="padding: 5px;" value=" Refresh Data " id="refreshdatabutton" type="button" />
		<input style="padding: 5px;display: none;" value=" Print " id="printbutton" type="button" />
		<input style="padding: 5px;display: none;" value=" Excel " id="excelbutton" type="button" />
        <div id="jqxgrid"></div>
	</div>
	<br>
	<br>
	<br>
</div>