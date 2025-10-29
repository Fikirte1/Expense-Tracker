<?php
// app/Controllers/Profile.php
namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\Shield\Models\UserModel;

class Profile extends Controller
{
    public function index()
    {
        // Check if user is logged in
        if (!auth()->loggedIn()) {
            return redirect()->to('/login')->with('error', 'Please login to view your profile.');
        }

        $user = auth()->user();
        
        $data = [
            'title' => 'My Profile',
            'user' => $user
        ];

        return view('profile/index', $data);
    }

    public function update()
    {
        if (!auth()->loggedIn()) {
            return redirect()->to('/login');
        }

        $user = auth()->user();
        $userModel = new UserModel();

        // Get form data
        $username = $this->request->getPost('username');
        
        if ($username) {
            $user->username = $username;
            
            if ($userModel->save($user)) {
                return redirect()->to('/profile')->with('success', 'Profile updated successfully!');
            } else {
                return redirect()->to('/profile')->with('error', 'Failed to update profile.');
            }
        }

        return redirect()->to('/profile')->with('error', 'No data provided.');
    }
}