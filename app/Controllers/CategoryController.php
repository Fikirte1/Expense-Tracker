<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CategoryModel;

class CategoryController extends BaseController
{
    protected $categoryModel;

    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
        helper(['form', 'url']);
    }

   public function index()
{
    if (!auth()->loggedIn()) {
        return redirect()->to('/login')->with('error', 'Please login to access categories page.');
    }
    
    $userId = auth()->id();
    
    $categories = $this->categoryModel
        ->where('user_id', $userId)
        ->orderBy('name', 'ASC')
        ->findAll();

    // Page actions HTML for navbar
    $pageActions = '
        <div class="navbar-nav ms-auto">
            <a href="' . site_url('expenses') . '" class="btn btn-outline-secondary me-2">
                <i class="fas fa-arrow-left me-2"></i>Back to Expenses
            </a>
            <a href="' . site_url('categories/create') . '" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>New Category
            </a>
        </div>
    ';

    return view('categories/index', [
        'categories' => $categories,
        'title' => 'Category Management',
        'page_icon' => '<i class="fas fa-tags me-2 text-info"></i>',
        'breadcrumbs' => [
            ['url' => base_url(), 'text' => 'Dashboard'],
            ['text' => 'Categories']
        ],
        'page_actions' => $pageActions,
        'show_quick_stats' => false
    ]);
}

    public function create()
    {
        return view('categories/create');
    }

    public function store()
    {
        $userId = auth()->id();

        // Validate input
        $validation = \Config\Services::validation();
        $validation->setRules([
            'name' => 'required|max_length[100]'
        ], [
            'name' => [
                'required' => 'Category name is required.',
                'max_length' => 'Category name cannot exceed 100 characters.'
            ]
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->with('errors', $validation->getErrors())->withInput();
        }

        $data = [
            'user_id' => $userId,
            'name' => $this->request->getPost('name')
        ];

        if ($this->categoryModel->save($data)) {
            return redirect()->to('/categories')->with('success', 'Category added successfully!');
        } else {
            return redirect()->back()->with('errors', $this->categoryModel->errors())->withInput();
        }
    }

    public function edit($id)
    {
        $userId = auth()->id();
        $category = $this->categoryModel->where('user_id', $userId)->find($id);

        if (!$category) {
            return redirect()->to('/categories')->with('error', 'Category not found.');
        }

        return view('categories/edit', ['category' => $category]);
    }

    public function update($id)
    {
        $userId = auth()->id();
        $category = $this->categoryModel->where('user_id', $userId)->find($id);

        if (!$category) {
            return redirect()->to('/categories')->with('error', 'Category not found.');
        }

        // Validate input
        $validation = \Config\Services::validation();
        $validation->setRules([
            'name' => 'required|max_length[100]'
        ], [
            'name' => [
                'required' => 'Category name is required.',
                'max_length' => 'Category name cannot exceed 100 characters.'
            ]
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->with('errors', $validation->getErrors())->withInput();
        }

        $data = [
            'name' => $this->request->getPost('name')
        ];

        if ($this->categoryModel->update($id, $data)) {
            return redirect()->to('/categories')->with('success', 'Category updated successfully!');
        } else {
            return redirect()->back()->with('errors', $this->categoryModel->errors())->withInput();
        }
    }

    public function delete($id)
    {
        $userId = auth()->id();
        $category = $this->categoryModel->where('user_id', $userId)->find($id);
        
        if ($category && $this->categoryModel->delete($id)) {
            return redirect()->to('/categories')->with('success', 'Category deleted successfully!');
        } else {
            return redirect()->to('/categories')->with('error', 'Category not found or you do not have permission to delete it.');
        }
    }
}