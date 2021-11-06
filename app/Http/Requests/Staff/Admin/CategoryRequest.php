<?php

namespace App\Http\Requests\Staff\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use App\Models\Category;

class CategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:50',
            'parent_category' => 'nullable|exists:categories,id'
        ];
    }

    /**
     * Database persist
     * 
     * @return Illuminate\Routing\Redirector
     */
    public function add()
    {
        #Check if exists category name
        $otherCategory = Category::where('name', $this->name)->first();

        if (!is_null($otherCategory)) {
            throw new \Exception('There is already a category with that name!');
        }

        $category = new Category();
        $category->name = $this->name;
        $category->slug = Str::slug(strtolower($this->name));
        $category->parent_category = $this->parent_category;
        $category->save();

        session()->flash('success', 'Category created successfully!');
        return redirect()->route('admin.categories');
    }

    /**
     * Database persist
     * @param Category $category
     * 
     * @return Illuminate\Routing\Redirector
     */
    public function edit(Category $category)
    {
        #Check if exists category name
        $otherCategory = Category::where('name', $this->name)->first();

        if (!is_null($otherCategory) && $category->name != $this->name) {
            throw new \Exception('There is already a category with that name!');
        }

        if ($category->id == $this->parent_category) {
            throw new \Exception('You cannot place this category as a parent!');
        }

        $category->name = $this->name;
        $category->slug = Str::slug($this->name);
        $category->parent_category = $this->parent_category;
        $category->save();

        session()->flash('success', 'Category successfully edited!');
        return redirect()->route('admin.category', ['category' => $category->id]);
    }
}
