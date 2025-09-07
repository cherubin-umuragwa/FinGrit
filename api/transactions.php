<?php
header('Content-Type: application/json');
require_once '../php/middleware.php';
require_once '../php/transactions.php';

Middleware::authRequired();
$user = Middleware::getCurrentUser();
$transaction = new Transaction();
$response = ['success' => false, 'message' => ''];

try {
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            if (isset($_GET['id'])) {
                $transactionData = $transaction->getById($_GET['id'], $user['id']);
                if ($transactionData) {
                    $response['success'] = true;
                    $response['data'] = $transactionData;
                } else {
                    throw new Exception('Transaction not found');
                }
            } else {
                $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : null;
                $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : null;
                
                $transactions = $transaction->getAll($user['id'], $limit, $offset);
                $summary = $transaction->getSummary($user['id']);
                $byCategory = $transaction->getByCategory($user['id']);
                
                $response['success'] = true;
                $response['data'] = [
                    'transactions' => $transactions,
                    'summary' => $summary,
                    'byCategory' => $byCategory
                ];
            }
            break;
            
        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (isset($data['id'])) {
                // Update transaction
                $transaction->update(
                    $data['id'],
                    $user['id'],
                    $data['type'],
                    $data['category'],
                    $data['amount'],
                    $data['date'],
                    $data['notes'] ?? ''
                );
                $response['message'] = 'Transaction updated successfully';
            } else {
                // Create transaction
                $transaction->create(
                    $user['id'],
                    $data['type'],
                    $data['category'],
                    $data['amount'],
                    $data['date'],
                    $data['notes'] ?? ''
                );
                $response['message'] = 'Transaction created successfully';
            }
            $response['success'] = true;
            break;
            
        case 'DELETE':
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (isset($data['id'])) {
                $transaction->delete($data['id'], $user['id']);
                $response['success'] = true;
                $response['message'] = 'Transaction deleted successfully';
            } else {
                throw new Exception('Transaction ID is required');
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