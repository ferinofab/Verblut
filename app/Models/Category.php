<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['external_code', 'name', 'slug'];

    // Связь с детьми
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    // Связь с родителем
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // Получить всех потомков (рекурсивно через коллекцию)
    public function descendants()
    {
        $descendants = collect();

        foreach($this->children as $child) {
            $descendants->push($child);
            $descendants = $descendants->merge($child->descendants());
        }

        return $descendants;
    }

    // Получить дерево
    public static function getTree()
    {
        $categories = static::all();
        $grouped = $categories->groupBy('parent_id');

        return static::buildTree($grouped);
    }

    private static function buildTree($grouped, $parentId = null)
    {
        $tree = [];

        if(isset($grouped[$parentId])) {
            foreach($grouped[$parentId] as $category) {
                $category->children = static::buildTree($grouped, $category->id);
                $tree[] = $category;
            }
        }

        return $tree;
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }


}
