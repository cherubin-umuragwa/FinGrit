<?php
header('Content-Type: application/json');
require_once '../php/middleware.php';
require_once '../php/goals.php';

Middleware::authRequired();
$user = Middleware::getCurrentUser();
$goal = new Goal();
$response = ['success' => false, 'message' => ''];

try {
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            if (isset($_GET['id'])) {
                $goalData = $goal->getById($_GET['id'], $user['id']);
                if ($goalData) {
                    $response['success'] = true;
                    $response['data'] = $goalData;
                } else {
                    throw new Exception('Goal not found');
                }
            } else {
                $goals = $goal->getAll($user['id']);
                $response['success'] = true;
                $response['data'] = $goals;
            }
            break;
            
        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (isset($data['id'])) {
                // Update goal
                $goal->update(
                    $data['id'],
                    $user['id'],
                    $data['name'],
                    $data['target_amount'],
                    $data['deadline'] ?? null,
                    $data['current_savings'] ?? 0
                );
                $response['message'] = 'Goal updated successfully';
            } else {
                // Create goal
                $goal->create(
                    $user['id'],
                    $data['name'],
                    $data['target_amount'],
                    $data['deadline'] ?? null,
                    $data['current_savings'] ?? 0
                );
                $response['message'] = 'Goal created successfully';
            }
            $response['success'] = true;
            break;
            
        case 'PATCH':
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (isset($data['id']) && isset($data['amount'])) {
                $goal->addToSavings($data['id'], $user['id'], $data['amount']);
                $response['success'] = true;
                $response['message'] = 'Savings updated successfully';
            } else {
                throw new Exception('Goal ID and amount are required');
            }
            break;
            
        case 'DELETE':
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (isset($data['id'])) {
                $goal->delete($data['id'], $user['id']);
                $response['success'] = true;
                $response['message'] = 'Goal deleted successfully';
            } else {
                throw new Exception('Goal ID is required');
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