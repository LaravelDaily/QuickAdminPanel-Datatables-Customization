<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Resources\Admin\ProjectResource;
use App\Project;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProjectsApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('project_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ProjectResource(Project::all());
    }

    public function store(StoreProjectRequest $request)
    {
        $project = Project::create($request->all());

        if ($request->input('logo', false)) {
            $project->addMedia(storage_path('tmp/uploads/' . $request->input('logo')))->toMediaCollection('logo');
        }

        if ($request->input('photos', false)) {
            $project->addMedia(storage_path('tmp/uploads/' . $request->input('photos')))->toMediaCollection('photos');
        }

        return (new ProjectResource($project))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Project $project)
    {
        abort_if(Gate::denies('project_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ProjectResource($project);
    }

    public function update(UpdateProjectRequest $request, Project $project)
    {
        $project->update($request->all());

        if ($request->input('logo', false)) {
            if (!$project->logo || $request->input('logo') !== $project->logo->file_name) {
                $project->addMedia(storage_path('tmp/uploads/' . $request->input('logo')))->toMediaCollection('logo');
            }
        } elseif ($project->logo) {
            $project->logo->delete();
        }

        if ($request->input('photos', false)) {
            if (!$project->photos || $request->input('photos') !== $project->photos->file_name) {
                $project->addMedia(storage_path('tmp/uploads/' . $request->input('photos')))->toMediaCollection('photos');
            }
        } elseif ($project->photos) {
            $project->photos->delete();
        }

        return (new ProjectResource($project))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Project $project)
    {
        abort_if(Gate::denies('project_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $project->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
