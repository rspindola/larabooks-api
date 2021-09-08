<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Book extends Model
{
    use HasSlug, HasFactory;

    protected $fillable = [
        'company_id', 'category_id', 'title', 'cover', 'description', 'about', 'chapters',
        'gender','pages','price','status','slug', 'published_at'
    ];

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    /**
     * Category hasMany Recipe
     */
    public function category()
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Category hasMany Recipe
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

}
