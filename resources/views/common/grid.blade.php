<script type="text/javascript">
    $('#doc-datagrid-table').datagrid({
        showCheckboxcol: true,
        showEditbtnscol: true,
        filterAll: true,
//        loadType: 'GET',
//        showToolbar: true,
//        editMode: 'dialog',
//        toolbarItem: ['all', 'add', 'edit', 'cancel', 'save', 'del', 'import', 'export', '|'],
        sortAll: true,
        linenumberAll: true,
        height: "100%",
        columns: <?php echo $columns; ?>,
        dataUrl: "{{ route('dashboard.test') }}"
    })
</script>
<table id="doc-datagrid-table" style="width:100%"></table>