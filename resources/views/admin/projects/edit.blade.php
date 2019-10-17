@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">

            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.edit') }} {{ trans('cruds.project.title_singular') }}
                </div>
                <div class="panel-body">

                    <form action="{{ route("admin.projects.update", [$project->id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                            <label for="name">{{ trans('cruds.project.fields.name') }}</label>
                            <input type="text" id="name" name="name" class="form-control" value="{{ old('name', isset($project) ? $project->name : '') }}">
                            @if($errors->has('name'))
                                <p class="help-block">
                                    {{ $errors->first('name') }}
                                </p>
                            @endif
                            <p class="helper-block">
                                {{ trans('cruds.project.fields.name_helper') }}
                            </p>
                        </div>
                        <div class="form-group {{ $errors->has('project_type') ? 'has-error' : '' }}">
                            <label>{{ trans('cruds.project.fields.project_type') }}</label>
                            @foreach(App\Project::PROJECT_TYPE_RADIO as $key => $label)
                                <div>
                                    <input id="project_type_{{ $key }}" name="project_type" type="radio" value="{{ $key }}" {{ old('project_type', $project->project_type) === (string)$key ? 'checked' : '' }}>
                                    <label for="project_type_{{ $key }}">{{ $label }}</label>
                                </div>
                            @endforeach
                            @if($errors->has('project_type'))
                                <p class="help-block">
                                    {{ $errors->first('project_type') }}
                                </p>
                            @endif
                        </div>
                        <div class="form-group {{ $errors->has('logo') ? 'has-error' : '' }}">
                            <label for="logo">{{ trans('cruds.project.fields.logo') }}</label>
                            <div class="needsclick dropzone" id="logo-dropzone">

                            </div>
                            @if($errors->has('logo'))
                                <p class="help-block">
                                    {{ $errors->first('logo') }}
                                </p>
                            @endif
                            <p class="helper-block">
                                {{ trans('cruds.project.fields.logo_helper') }}
                            </p>
                        </div>
                        <div class="form-group {{ $errors->has('deadline_date') ? 'has-error' : '' }}">
                            <label for="deadline_date">{{ trans('cruds.project.fields.deadline_date') }}</label>
                            <input type="text" id="deadline_date" name="deadline_date" class="form-control date" value="{{ old('deadline_date', isset($project) ? $project->deadline_date : '') }}">
                            @if($errors->has('deadline_date'))
                                <p class="help-block">
                                    {{ $errors->first('deadline_date') }}
                                </p>
                            @endif
                            <p class="helper-block">
                                {{ trans('cruds.project.fields.deadline_date_helper') }}
                            </p>
                        </div>
                        <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
                            <label for="description">{{ trans('cruds.project.fields.description') }}</label>
                            <textarea id="description" name="description" class="form-control ">{{ old('description', isset($project) ? $project->description : '') }}</textarea>
                            @if($errors->has('description'))
                                <p class="help-block">
                                    {{ $errors->first('description') }}
                                </p>
                            @endif
                            <p class="helper-block">
                                {{ trans('cruds.project.fields.description_helper') }}
                            </p>
                        </div>
                        <div class="form-group {{ $errors->has('photos') ? 'has-error' : '' }}">
                            <label for="photos">{{ trans('cruds.project.fields.photos') }}</label>
                            <div class="needsclick dropzone" id="photos-dropzone">

                            </div>
                            @if($errors->has('photos'))
                                <p class="help-block">
                                    {{ $errors->first('photos') }}
                                </p>
                            @endif
                            <p class="helper-block">
                                {{ trans('cruds.project.fields.photos_helper') }}
                            </p>
                        </div>
                        <div>
                            <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
                        </div>
                    </form>


                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    Dropzone.options.logoDropzone = {
    url: '{{ route('admin.projects.storeMedia') }}',
    maxFilesize: 2, // MB
    acceptedFiles: '.jpeg,.jpg,.png,.gif',
    maxFiles: 1,
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 2,
      width: 4096,
      height: 4096
    },
    success: function (file, response) {
      $('form').find('input[name="logo"]').remove()
      $('form').append('<input type="hidden" name="logo" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="logo"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($project) && $project->logo)
      var file = {!! json_encode($project->logo) !!}
          this.options.addedfile.call(this, file)
      this.options.thumbnail.call(this, file, file.url)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="logo" value="' + file.file_name + '">')
      this.options.maxFiles = this.options.maxFiles - 1
@endif
    },
    error: function (file, response) {
        if ($.type(response) === 'string') {
            var message = response //dropzone sends it's own error messages in string
        } else {
            var message = response.errors.file
        }
        file.previewElement.classList.add('dz-error')
        _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
        _results = []
        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
            node = _ref[_i]
            _results.push(node.textContent = message)
        }

        return _results
    }
}
</script>
<script>
    var uploadedPhotosMap = {}
Dropzone.options.photosDropzone = {
    url: '{{ route('admin.projects.storeMedia') }}',
    maxFilesize: 2, // MB
    acceptedFiles: '.jpeg,.jpg,.png,.gif',
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 2,
      width: 4096,
      height: 4096
    },
    success: function (file, response) {
      $('form').append('<input type="hidden" name="photos[]" value="' + response.name + '">')
      uploadedPhotosMap[file.name] = response.name
    },
    removedfile: function (file) {
      console.log(file)
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedPhotosMap[file.name]
      }
      $('form').find('input[name="photos[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($project) && $project->photos)
      var files =
        {!! json_encode($project->photos) !!}
          for (var i in files) {
          var file = files[i]
          this.options.addedfile.call(this, file)
          this.options.thumbnail.call(this, file, file.url)
          file.previewElement.classList.add('dz-complete')
          $('form').append('<input type="hidden" name="photos[]" value="' + file.file_name + '">')
        }
@endif
    },
     error: function (file, response) {
         if ($.type(response) === 'string') {
             var message = response //dropzone sends it's own error messages in string
         } else {
             var message = response.errors.file
         }
         file.previewElement.classList.add('dz-error')
         _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
         _results = []
         for (_i = 0, _len = _ref.length; _i < _len; _i++) {
             node = _ref[_i]
             _results.push(node.textContent = message)
         }

         return _results
     }
}
</script>
@stop