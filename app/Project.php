<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class Project extends Model implements HasMedia
{
    use SoftDeletes, HasMediaTrait;

    public $table = 'projects';

    protected $appends = [
        'logo',
        'photos',
    ];

    protected $dates = [
        'updated_at',
        'created_at',
        'deleted_at',
        'deadline_date',
    ];

    const PROJECT_TYPE_RADIO = [
        'fixed'  => 'fixed',
        'hourly' => 'hourly',
        'mixed'  => 'mixed',
    ];

    protected $fillable = [
        'name',
        'created_at',
        'updated_at',
        'deleted_at',
        'description',
        'project_type',
        'deadline_date',
    ];

    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')->width(50)->height(50);
    }

    public function getLogoAttribute()
    {
        $file = $this->getMedia('logo')->last();

        if ($file) {
            $file->url       = $file->getUrl();
            $file->thumbnail = $file->getUrl('thumb');
        }

        return $file;
    }

    public function getDeadlineDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setDeadlineDateAttribute($value)
    {
        $this->attributes['deadline_date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function getPhotosAttribute()
    {
        $files = $this->getMedia('photos');
        $files->each(function ($item) {
            $item->url       = $item->getUrl();
            $item->thumbnail = $item->getUrl('thumb');
        });

        return $files;
    }
}
