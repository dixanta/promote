<div region="center" border="false">
<div style="padding:20px">
<div id="search-panel" class="easyui-panel" data-options="title:'<?php  echo lang('guest_search')?>',collapsible:true,iconCls:'icon-search'" style="padding:5px">
<form action="" method="post" id="guest-search-form">
<table width="100%" border="1" cellspacing="1" cellpadding="1">
<tr><td><label><?php echo lang('first_name')?></label>:</td>
<td><input type="text" name="search[first_name]" id="search_first_name"  class="easyui-validatebox"/></td>
<td><label><?php echo lang('last_name')?></label>:</td>
<td><input type="text" name="search[last_name]" id="search_last_name"  class="easyui-validatebox"/></td>
</tr>
<tr>
<td><label><?php echo lang('email')?></label>:</td>
<td><input type="text" name="search[email]" id="search_email"  class="easyui-validatebox"/></td>
<td><label><?php echo lang('contact_no')?></label>:</td>
<td><input type="text" name="search[contact_no]" id="search_contact_no"  class="easyui-validatebox"/></td>
</tr>
<tr>
<td><label><?php echo lang('promoter_id')?></label>:</td>
<td><input type="text" name="search[promoter_id]" id="search_promoter_id"  class="easyui-numberbox"/></td>
</tr>
  <tr>
    <td colspan="4">
    <a href="javascript:void(0)" class="easyui-linkbutton" id="search" data-options="iconCls:'icon-search'"><?php  echo lang('search')?></a>  
    <a href="javascript:void(0)" class="easyui-linkbutton" id="clear" data-options="iconCls:'icon-clear'"><?php  echo lang('clear')?></a>
    </td>
    </tr>
</table>

</form>
</div>
<br/>
<table id="guest-table" data-options="pagination:true,title:'<?php  echo lang('guest')?>',pagesize:'20', rownumbers:true,toolbar:'#toolbar',collapsible:true,fitColumns:true">
    <thead>
    <th data-options="field:'checkbox',checkbox:true"></th>
    <th data-options="field:'guest_id',sortable:true" width="30"><?php echo lang('guest_id')?></th>
<th data-options="field:'first_name',sortable:true" width="50"><?php echo lang('first_name')?></th>
<th data-options="field:'last_name',sortable:true" width="50"><?php echo lang('last_name')?></th>
<th data-options="field:'email',sortable:true" width="50"><?php echo lang('email')?></th>
<th data-options="field:'contact_no',sortable:true" width="50"><?php echo lang('contact_no')?></th>
<th data-options="field:'promoter_id',sortable:true" width="50"><?php echo lang('promoter_id')?></th>

    <th field="action" width="100" formatter="getActions"><?php  echo lang('action')?></th>
    </thead>
</table>

<div id="toolbar" style="padding:5px;height:auto">
    <p>
    <a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="false" onclick="create()" title="<?php  echo lang('create_guest')?>"><?php  echo lang('create')?></a>
    <a href="#" class="easyui-linkbutton" iconCls="icon-cancel" plain="false" onclick="removeSelected()"  title="<?php  echo lang('delete_guest')?>"><?php  echo lang('remove_selected')?></a>
    </p>

</div> 

<!--for create and edit guest form-->
<div id="dlg" class="easyui-dialog" style="width:600px;height:auto;padding:10px 20px"
        data-options="closed:true,collapsible:true,buttons:'#dlg-buttons',modal:true">
    <form id="form-guest" method="post" >
    <table>
		<tr>
		              <td width="34%" ><label><?php echo lang('first_name')?>:</label></td>
					  <td width="66%"><input name="first_name" id="first_name" class="easyui-validatebox" required="true"></td>
		       </tr><tr>
		              <td width="34%" ><label><?php echo lang('last_name')?>:</label></td>
					  <td width="66%"><input name="last_name" id="last_name" class="easyui-validatebox" required="true"></td>
		       </tr><tr>
		              <td width="34%" ><label><?php echo lang('email')?>:</label></td>
					  <td width="66%"><input name="email" id="email" class="easyui-validatebox" required="true"></td>
		       </tr><tr>
		              <td width="34%" ><label><?php echo lang('contact_no')?>:</label></td>
					  <td width="66%"><input name="contact_no" id="contact_no" class="easyui-validatebox" required="true"></td>
		       </tr><tr>
		              <td width="34%" ><label><?php echo lang('promoter_id')?>:</label></td>
					  <td width="66%"><input name="promoter_id" id="promoter_id" class="easyui-numberbox" required="true"></td>
		       </tr><input type="hidden" name="guest_id" id="guest_id"/>
    </table>
    </form>
	<div id="dlg-buttons">
		<a href="#" class="easyui-linkbutton" iconCls="icon-ok" onClick="save()"><?php  echo  lang('general_save')?></a>
		<a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onClick="javascript:$('#dlg').window('close')"><?php  echo  lang('general_cancel')?></a>
	</div>    
</div>
<!--div ends-->
   
</div>
</div>
<script language="javascript" type="text/javascript">
	$(function(){
		$('#clear').click(function(){
			$('#guest-search-form').form('clear');
			$('#guest-table').datagrid({
				queryParams:null
				});

		});

		$('#search').click(function(){
			$('#guest-table').datagrid({
				queryParams:{data:$('#guest-search-form').serialize()}
				});
		});		
		$('#guest-table').datagrid({
			url:'<?php  echo site_url('guest/admin/guest/json')?>',
			height:'auto',
			width:'auto',
			onDblClickRow:function(index,row)
			{
				edit(index);
			}
		});
	});
	
	function getActions(value,row,index)
	{
		var e = '<a href="#" onclick="edit('+index+')" class="easyui-linkbutton l-btn" iconcls="icon-edit"  title="<?php  echo lang('edit_guest')?>"><span class="l-btn-left"><span style="padding-left: 20px;" class="l-btn-text icon-edit"></span></span></a>';
		var d = '<a href="#" onclick="removeguest('+index+')" class="easyui-linkbutton l-btn" iconcls="icon-remove"  title="<?php  echo lang('delete_guest')?>"><span class="l-btn-left"><span style="padding-left: 20px;" class="l-btn-text icon-cancel"></span></span></a>';
		return e+d;		
	}
	
	function formatStatus(value)
	{
		if(value==1)
		{
			return 'Yes';
		}
		return 'No';
	}

	function create(){
		//Create code here
		$('#form-guest').form('clear');
		$('#dlg').window('open').window('setTitle','<?php  echo lang('create_guest')?>');
		//uploadReady(); //Uncomment This function if ajax uploading
	}	

	function edit(index)
	{
		var row = $('#guest-table').datagrid('getRows')[index];
		if (row){
			$('#form-guest').form('load',row);
			//uploadReady(); //Uncomment This function if ajax uploading
			$('#dlg').window('open').window('setTitle','<?php  echo lang('edit_guest')?>');
		}
		else
		{
			$.messager.alert('Error','<?php  echo lang('edit_selection_error')?>');				
		}		
	}
	
		
	function removeguest(index)
	{
		$.messager.confirm('Confirm','<?php  echo lang('delete_confirm')?>',function(r){
			if (r){
				var row = $('#guest-table').datagrid('getRows')[index];
				$.post('<?php  echo site_url('guest/admin/guest/delete_json')?>', {id:[row.guest_id]}, function(){
					$('#guest-table').datagrid('deleteRow', index);
					$('#guest-table').datagrid('reload');
				});

			}
		});
	}
	
	function removeSelected()
	{
		var rows=$('#guest-table').datagrid('getSelections');
		if(rows.length>0)
		{
			selected=[];
			for(i=0;i<rows.length;i++)
			{
				selected.push(rows[i].guest_id);
			}
			
			$.messager.confirm('Confirm','<?php  echo lang('delete_confirm')?>',function(r){
				if(r){				
					$.post('<?php  echo site_url('guest/admin/guest/delete_json')?>',{id:selected},function(data){
						$('#guest-table').datagrid('reload');
					});
				}
				
			});
			
		}
		else
		{
			$.messager.alert('Error','<?php  echo lang('edit_selection_error')?>');	
		}
		
	}
	
	function save()
	{
		$('#form-guest').form('submit',{
			url: '<?php  echo site_url('guest/admin/guest/save')?>',
			onSubmit: function(){
				return $(this).form('validate');
			},
			success: function(result){
				var result = eval('('+result+')');
				if (result.success)
				{
					$('#form-guest').form('clear');
					$('#dlg').window('close');		// close the dialog
					$.messager.show({title: '<?php  echo lang('success')?>',msg: result.msg});
					$('#guest-table').datagrid('reload');	// reload the user data
				} 
				else 
				{
					$.messager.show({title: '<?php  echo lang('error')?>',msg: result.msg});
				} //if close
			}//success close
		
		});		
		
	}
	
	
</script>