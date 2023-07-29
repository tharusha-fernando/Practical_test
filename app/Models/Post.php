<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;


class Post extends Model
{
    use HasFactory;
    use Sluggable;

    protected $fillable=['title','data','slug','user_id','status','image'];



    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }





    public function User(){
        return $this->belongsTo(User::class);
    }
    
    public function Tag(){
        return $this->belongsToMany(Tag::class,'post_tags','post__id','tag_id');
    }
}
