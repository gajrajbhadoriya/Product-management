<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;


class CategoriesController extends Controller
{
    public function categoriesListing(Request $request)
    {
        if ($request->ajax()) {
            $where_str = "1 = ?";
            $where_params = [1];

            // Search functionality
            if ($request->has('sSearch') && !empty($request->get('sSearch'))) {
                $search = $request->get('sSearch');
                $where_str .= " AND (title LIKE ?)";
                $where_params[] = "%{$search}%";
            }

            // Define the columns to select
            $columns = ['id', 'title', 'description', 'image', 'status'];

            // Build the query
            $category = Category::select($columns)
                ->whereRaw($where_str, $where_params)
                ->orderBy('created_at', 'desc');

            // Get total record count
            $total_count = Category::whereRaw($where_str, $where_params)->count();

            // Handle pagination
            if ($request->has('iDisplayStart') && $request->get('iDisplayLength') != '') {
                $category->take($request->get('iDisplayLength'))
                    ->skip($request->get('iDisplayStart'));
            }

            // Handle sorting
            if ($request->has('iSortCol_0')) {
                for ($i = 0; $i < intval($request->get('iSortingCols')); $i++) {
                    $column = $columns[intval($request->get('iSortCol_' . $i))];
                    $category->orderBy($column, $request->get('sSortDir_' . $i));
                }
            }

            // Execute the query and get the results
            $categoryData = $category->get()->toArray();

            // Prepare the response
            $response = [
                'iTotalDisplayRecords' => $total_count,
                'iTotalRecords' => $total_count,
                'sEcho' => intval($request->get('sEcho')),
                'aaData' => $categoryData
            ];

            return response()->json($response, 200);
        }

        return view('categories.categories');
    }


    // {
    //     // dd('testing');
    //     $categories = Category::all();
    //     return view('categories.categories', compact('categories'));
    // }

    public function create()
    {
        // dd('testing');
        return view('categories.categories-create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|boolean',
        ]);

        $category = new Category($request->all());

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images', 'public');
            $category->image = $path;
        }

        $category->save();
        return redirect()->route('categoriesListing')->with('success', 'Category created successfully.');
    }

    public function edit($id)
    {
        $category = Category::find($id);
        // dd($category);
        if (isset($category)) {
            return view('categories.categories-edit', compact('category'));
        } else {
            return redirect()->route('categoriesListing')->with('error', 'Category not found.');
        }
    }

    public function update(Request $request, Category $category)
    {
        $editCategoryData = $request->all();

        $editId = $editCategoryData['id'];

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|boolean',
        ]);

        $category = Category::find($editId);

        if ($category) {

            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('images', 'public');
                $category->image = $path;
            }

            $category->title = $editCategoryData['title'];
            $category->description = $editCategoryData['description'];
            $category->status = $editCategoryData['status'];

            $category->save();

            return redirect()->route('categoriesListing')->with('success', 'Category updated successfully.');
        }

        return redirect()->route('categoriesListing')->with('error', 'Category not found');
    }


    public function destroy(Category $category, $id)
    {
        // dd($id);
        $category->where('id', $id)->delete();
        return redirect()->route('categoriesListing')->with('success', 'Category deleted successfully.');
    }
}
