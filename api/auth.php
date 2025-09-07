<?php
header('Content-Type: application/json');
require_once '../php/auth.php';
require_once '../php/helpers.php';

$auth = new Auth();
$response = ['success' => false, 'message' => ''];

try {
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (isset($data['action'])) {
                switch ($data['action']) {
                    case 'register':
                        if (empty($data['name'])) throw new Exception('Name is required');
                        if (empty($data['email'])) throw new Exception('Email is required');
                        if (empty($data['password'])) throw new Exception('Password is required');
                        if (strlen($data['password']) < 8) throw new Exception('Password must be at least 8 characters');
                        
                        $auth->register($data['name'], $data['email'], $data['password']);
                        $response['success'] = true;
                        $response['message'] = 'Registration successful';
                        break;
                        
                    case 'login':
                        if (empty($data['email'])) throw new Exception('Email is required');
                        if (empty($data['password'])) throw new Exception('Password is required');
                        
                        $user = $auth->login($data['email'], $data['password']);
                        $response['success'] = true;
                        $response['message'] = 'Login successful';
                        $response['user'] = [
                            'id' => $user['id'],
                            'name' => $user['name'],
                            'email' => $user['email']
                        ];
                        break;
                        
                    default:
                        throw new Exception('Invalid action');
                }
            } else {
                throw new Exception('Action is required');
            }
            break;
            
        default:
            throw new Exception('Method not allowed');
    }
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
?>