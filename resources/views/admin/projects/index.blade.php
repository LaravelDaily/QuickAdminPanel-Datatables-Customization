@extends('layouts.admin')
@section('content')
<div class="content">
    @can('project_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route("admin.projects.create") }}">
                    {{ trans('global.add') }} {{ trans('cruds.project.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="row">
        <div class="col-lg-12">

            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('cruds.project.title_singular') }} {{ trans('global.list') }}
                </div>
                <div class="panel-body">

                    <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Project">
                        <thead>
                            <tr>
                                <th width="10">

                                </th>
                                <th>
                                    {{ trans('cruds.project.fields.id') }}
                                </th>
                                <th>
                                    {{ trans('cruds.project.fields.name') }}
                                </th>
                                <th>
                                    {{ trans('cruds.project.fields.project_type') }}
                                </th>
                                <th>
                                    {{ trans('cruds.project.fields.logo') }}
                                </th>
                                <th>
                                    {{ trans('cruds.project.fields.deadline_date') }}
                                </th>
                                <th>
                                    {{ trans('cruds.project.fields.description') }}
                                </th>
                                <th>
                                    {{ trans('cruds.project.fields.photos') }}
                                </th>
                                <th>
                                    &nbsp;
                                </th>
                            </tr>
                        </thead>
                    </table>


                </div>
            </div>

        </div>
    </div>
</div>
@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('project_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.projects.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).data(), function (entry) {
          return entry.id
      });

      if (ids.length === 0) {
        alert('{{ trans('global.datatables.zero_selected') }}')

        return
      }

      if (confirm('{{ trans('global.areYouSure') }}')) {
        $.ajax({
          headers: {'x-csrf-token': _token},
          method: 'POST',
          url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  dtButtons.push(deleteButton)
@endcan

  let dtOverrideGlobals = {
    buttons: dtButtons,
    processing: true,
    serverSide: true,
    retrieve: true,
    aaSorting: [],
    ajax: "{{ route('admin.projects.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'id', name: 'id' },
{ data: 'name', name: 'name' },
{ data: 'project_type', name: 'project_type' },
{ data: 'logo', name: 'logo', sortable: false, searchable: false },
{ data: 'deadline_date', name: 'deadline_date' },
{ data: 'description', name: 'description' },
{ data: 'photos', name: 'photos', sortable: false, searchable: false },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  };
  $('.datatable-Project').DataTable(dtOverrideGlobals);
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust();
    });
});

</script>
@endsection