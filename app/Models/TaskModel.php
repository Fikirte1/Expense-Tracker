<?php 

namespace App\Models;

use CodeIgniter\Model;

class TaskModel extends Model
{
    // Define the table this Model is linked to
    protected $table        = 'tasks'; 
    
    // Define the primary key column
    protected $primaryKey = 'id';

    // Specify the data type to return
    protected $returnType = 'array';
    
    // FIX 1 & 2: Added 'created_at' and 'updated_at' to $allowedFields,
    // as they are managed by timestamps.
    protected $allowedFields = ['title', 'is_completed', 'created_at', 'updated_at']; 

    // Use timestamps for automatic management
    protected $useTimestamps = true; 
    
    // The field to be managed automatically upon creation
    protected $createdField  = 'created_at';
    
    // FIX 3: Set the updatedField name to 'updated_at', NOT null.
    protected $updatedField  = 'updated_at'; 
}