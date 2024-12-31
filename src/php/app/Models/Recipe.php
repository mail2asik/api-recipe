<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Recipe extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uid',
        'user_id',
        'category',
        'title',
        'slug',
        'image_uid',
        'ingredients',
        'short_desc',
        'long_desc',
        'status'
    ];

    protected $hidden = [
        'id',
        'user_id'
    ];

    protected $appends = [
        'image_url'
    ];

    /**
     * Get image_url using S3
     *
     * @return array
     */
    public function getImageUrlAttribute()
    {
        $s3_dir = config('constants.s3_dir');
        $image_name = config('constants.s3_default_recipe_image_name');
        if (!empty($this->attributes['image_uid'])) {
            $image_name = $this->attributes['image_uid'] . '.jpg';
        }

        return [
            'original' => $this->getRawS3UrlByPath($s3_dir['original'] . '/' . $image_name),
            'thumb'    => $this->getRawS3UrlByPath($s3_dir['thumb'] . '/' . $image_name)
        ];
    }

    /*
     * Get raw S3 url by path
     *
     * @return string
     */
    protected function getRawS3UrlByPath($path)
    {
        return 'https://s3-' . config('filesystems.disks.s3.region') . '.amazonaws.com/' . config('filesystems.disks.s3.bucket') . '/' . $path;
    }
}
