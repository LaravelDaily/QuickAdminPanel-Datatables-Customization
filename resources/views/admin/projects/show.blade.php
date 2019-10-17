@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">

            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.show') }} {{ trans('cruds.project.title') }}
                </div>
                <div class="panel-body">

                    <div class="form-group">
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.project.fields.id') }}
                                    </th>
                                    <td>
                                        {{ $project->id }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.project.fields.name') }}
                                    </th>
                                    <td>
                                        {{ $project->name }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.project.fields.project_type') }}
                                    </th>
                                    <td>
                                        {{ App\Project::PROJECT_TYPE_RADIO[$project->project_type] }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.project.fields.logo') }}
                                    </th>
                                    <td>
                                        @if($project->logo)
                                            <a href="{{ $project->logo->getUrl() }}" target="_blank">
                                                <img src="{{ $project->logo->getUrl('thumb') }}" width="50px" height="50px">
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.project.fields.deadline_date') }}
                                    </th>
                                    <td>
                                        {{ $project->deadline_date }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.project.fields.description') }}
                                    </th>
                                    <td>
                                        {!! $project->description !!}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.project.fields.photos') }}
                                    </th>
                                    <td>
                                        @foreach($project->photos as $key => $media)
                                            <a href="{{ $media->getUrl() }}" target="_blank">
                                                <img src="{{ $media->getUrl('thumb') }}" width="50px" height="50px">
                                            </a>
                                        @endforeach
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <a style="margin-top:20px;" class="btn btn-default" href="{{ url()->previous() }}">
                            {{ trans('global.back_to_list') }}
                        </a>
                    </div>


                </div>
            </div>

        </div>
    </div>
</div>
@endsection