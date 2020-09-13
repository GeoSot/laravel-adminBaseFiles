<?php


namespace GeoSot\BaseAdmin\App\Traits\Eloquent\Media;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\morphTo;
use Illuminate\Support\Facades\Storage;

trait ManagesFiles
{
    public static function bootManagesFiles()
    {
        static::deleting(function ($model) {
            if (in_array($model['disk'], ['youTube', 'vimeo', 'uri'])) {
                return;
            }
            $exists = Storage::disk($model->disk)->exists($model->file);
            if ($exists) {
                $getUsagesCountOfThisFile = count(get_class($model)::where('file', $model->file)->get());
                if ($getUsagesCountOfThisFile > 1) {
                    //Probably is in use By another
                    return;
                }
                Storage::disk($model->disk)->delete($model->file); //Delete The FILE from Disk
                Storage::disk($model->disk)->delete($model->thumb); //Delete The FILE from Disk
                $filesInDirectory = Storage::disk($model->disk)->allFiles($model->collection_name); //Check if directory has files
                if (count($filesInDirectory) === 0) {
                    Storage::disk($model->disk)->deleteDirectory($model->collection_name);
                }
            }
        });
    }

    /**
     * Get all of the owning commentable models.
     *
     * @param  array  $idsToBeDeleted
     */
    public static function deleteIds(array $idsToBeDeleted)
    {
        static::whereIn('id', $idsToBeDeleted)->each(function (Model $model) {
            $model->delete();
        });
    }

    /**
     * TODO review this relationship
     *
     * @return morphTo
     */
    public function ownerModel()
    {
        //morphedByMany
        return $this->morphTo('model');
    }

    public function isEnabled()
    {
        return (bool)$this->enabled and $this->the_file_exists;
    }

    public function scopeEnabled(Builder $builder)
    {
        return $builder->where('enabled', true)->where('the_file_exists', true);
    }

    public function scopeDisabled(Builder $builder)
    {
        return $builder->where('enabled', false)->where('the_file_exists', false);
    }

}
