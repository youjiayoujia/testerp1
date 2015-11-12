<script type="text/javascript">
    $('#doc-datagrid-table').datagrid({
        height: "100%",
        columns: [
            {name: 'size', width: '150', label: '型号'},
            {name: 'color', width: '150', label: '颜色'},
            {name: 'created_at', width: '150', label: '创建时间', type: 'date'}
        ],
        dataUrl: "{{ route('dashboard.test') }}"
    })
</script>
<table id="doc-datagrid-table"></table>