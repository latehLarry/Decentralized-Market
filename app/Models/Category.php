<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use App\Traits\UUIDs;

class Category extends Model
{
    use HasFactory, UUIDs;

    /**
     * Returns all subcategories 
     * 
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subcategories()
    {
        return $this->hasMany(Category::class, 'parent_category', 'id');
    }

    /**
     * Get all root categories
     * 
     * @return App\Models\Category
     */
    public static function roots()
    {
        return self::whereNull('parent_category')->orderBy('name')->get();
    }

    /**
     * Returns all products belonging to the category, including products from subcategories
     * 
     * @return Illuminate\Collections\Collection
     */
    public function products()
    {
        #Get the products from the category
        $products = $this->hasMany(Product::class, 'category_id', 'id')->where('deleted', false)->get();

        #Create a collection and include products from the category
        $productCollection = new Collection($products);

        #Takes the products from the subcategories and includes them together with the products from that category
        if ($this->isParent()) {
            foreach ($this->subcategories as $subcategory) {
                $productCollection = $productCollection->merge($subcategory->products());
            }
        }

        return $productCollection;
    }

    /**
     * Count the amount of products that the category has
     * 
     * @return int
     */
    public function totalProducts()
    {
        return $this->products()->where('deleted', false)->count();
    }

    /**
     * Check if the category is the parent of another
     * 
     * @return boolean
     */
    public function isParent() : bool
    {
        if (count($this->subcategories) > 0) {
            return true;
        }
        
        return false;
    }


    /**
     * Get parent
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_category', 'id');
    }


    /**
     * Parents
     *
     * @return Illuminate\Collections\Collection
     */
    public function parents()
    {
        $ancestorsCollection = collect();
        $currentParent = $this->parent;

        while ($currentParent != null){
            $ancestorsCollection->push($currentParent);
            $currentParent = $currentParent->parent;
        }

        return $ancestorsCollection->reverse();
    }


    public function allSubcategories()
    {
        $subcategories = $this->subcategories;

        $allSubcategories = new Collection($subcategories);

        foreach ($subcategories as $subcategory) {
            $allSubcategories = $allSubcategories->merge($subcategory->allSubcategories());
        }
        
        return $allSubcategories;
    }
}
