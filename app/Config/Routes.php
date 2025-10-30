<?php 

use CodeIgniter\Router\RouteCollection;
use App\Controllers\TaskController;
use App\controllers\ExpenseController;
use App\Controllers\CategoryController;
use App\Controllers\IncomeController;
use App\Controllers\ReportController;
use App\Controllers\BudgetController;
use App\Controllers\Settings;
use App\Controllers\Profile;

/**
 * @var RouteCollection $routes
 */
// $routes->get('/', function() {
//     return redirect()->to('/login');
// });


// Load Homepage (GET is correct)
// $routes->get('/', [TaskController::class, 'index']);

// Create New Task (POST is correct)
$routes->post('create', [TaskController::class, 'create']);

// Delete Task (CHANGE TO POST)
$routes->post('delete/(:num)', [TaskController::class, 'delete'], ['as' => 'TaskController::delete']);

// Toggle Task Status (CHANGE TO POST)
$routes->post('toggle/(:num)', [TaskController::class, 'toggle'], ['as' => 'TaskController::toggle']);

service('auth')->routes($routes);

// $routes->group('', ['filter' => 'auth'], function($routes){
//     $routes->get('expenses', 'ExpenseController::index');
//     $routes->get('expenses/create', 'ExpenseController::create');
//     $routes->post('expenses/store', 'ExpenseController::store');
//     $routes->get('expenses/delete/(:num)', 'ExpenseController::delete/$1');
// });

$routes->group('', ['filter' => 'auth'], function($routes){
    // Existing expense routes
    $routes->get('/', [ExpenseController::class, 'index']);
    $routes->get('expenses/create', [ExpenseController::class, 'create']);
    $routes->post('expenses/store', [ExpenseController::class, 'store']);
    $routes->get('expenses/view/(:num)', [ExpenseController::class, 'view']);
    $routes->get('expenses/edit/(:num)', [ExpenseController::class, 'edit']);
    $routes->post('expenses/update/(:num)', [ExpenseController::class, 'update']);
    $routes->get('expenses/delete/(:num)', [ExpenseController::class, 'delete']);
    $routes->get('expenses/download-receipt/(:num)', [ExpenseController::class, 'downloadReceipt']);
    $routes->get('expenses/view-receipt/(:num)', [ExpenseController::class, 'viewReceipt']);

    // Income routes - FIXED (added import above)
    $routes->get('income', [IncomeController::class, 'index']);
    $routes->get('income/create', [IncomeController::class, 'create']);
    $routes->post('income/store', [IncomeController::class, 'store']);

    $routes->get('income/edit/(:num)', [IncomeController::class, 'edit/$1']);
    $routes->post('income/update/(:num)', [IncomeController::class, 'update/$1']);
    $routes->get('income/delete/(:num)', [IncomeController::class, 'delete/$1']);



    // Category routes
    $routes->get('categories', [CategoryController::class, 'index']);
    $routes->get('categories/create', [CategoryController::class, 'create']);
    $routes->post('categories/store', [CategoryController::class, 'store']);
    $routes->get('categories/edit/(:num)', [CategoryController::class, 'edit']);
    $routes->post('categories/update/(:num)', [CategoryController::class, 'update']);
    $routes->get('categories/delete/(:num)', [CategoryController::class, 'delete']);


    $routes->get('reports', [ReportController::class, 'index']);
    $routes->get('reports/export', [ReportController::class, 'export']);


    // Budget routes
    // $routes->get('budget', [BudgetController::class, 'index']);
    // $routes->post('budget/store', [BudgetController::class, 'store']);
    // $routes->post('budget/update/(:num)', [BudgetController::class, 'update/$1']);
    // $routes->get('budget/delete/(:num)', [BudgetController::class, 'delete/$1']);

    //   $routes->post('budget/quick-add', [BudgetController::class, 'quickAdd']);
    // $routes->post('budget/duplicate', [BudgetController::class, 'duplicateBudget']);
    // $routes->get('budget/stats', [BudgetController::class, 'getBudgetStats']);

    $routes->get('budget', [BudgetController::class, 'index']);
$routes->post('budget/store', [BudgetController::class, 'store']);
$routes->post('budget/update/(:num)', [BudgetController::class, 'update']);
$routes->post('budget/delete/(:num)', [BudgetController::class, 'delete']);
$routes->post('budget/quick-add', [BudgetController::class, 'quickAdd']);
$routes->post('budget/duplicate', [BudgetController::class, 'duplicateBudget']);
$routes->get('budget/stats', [BudgetController::class, 'getBudgetStats']);

// $routes->get('profile', 'Profile::index');
// $routes->post('profile/update', 'Profile::update');

// Settings routes
// $routes->get('settings', 'Settings::index');
$routes->post('settings/update', 'Settings::update');

$routes->post('settings/security', 'Settings::security');
$routes->post('settings/notifications', 'Settings::notifications');
$routes->get('settings', 'Settings::index');
$routes->get('profile', [Profile::class, 'index']);
$routes->post('profile/update', 'Profile::update');

});

