<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\OrderModel;
use App\Models\PaymentModel;
use CodeIgniter\HTTP\ResponseInterface;

class OrderController extends BaseController
{
    protected $orderModel;
    protected $paymentModel;

    public function __construct()
    {
        $this->orderModel = new OrderModel();
        $this->paymentModel = new PaymentModel();
    }

    public function placeOrder()
    {
        $json = $this->request->getJSON();

        if (!isset($json->user_id)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'user_id is required'
            ])->setStatusCode(400);
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $orderId = $this->orderModel->insert([
                'user_id' => $json->user_id,
                'status'  => 'PENDING'
            ]);

            $this->paymentModel->insert([
                'order_id' => $orderId,
                'status'   => 'PENDING'
            ]);

            $db->transComplete();

            if ($db->transStatus() === false) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to place order'
                ])->setStatusCode(500);
            }

            $order = $this->orderModel->find($orderId);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Order placed successfully',
                'data'    => $order
            ])->setStatusCode(201);
        } catch (\Exception $e) {
            $db->transRollback();
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to place order'
            ])->setStatusCode(500);
        }
    }

    public function summary()
    {
        $data = [
            'total_orders'     => $this->orderModel->countAll(),
            'completed_orders' => $this->orderModel->getCompletedCount(),
            'failed_orders'    => $this->orderModel->getFailedCount(),
            'pending_orders'   => $this->orderModel->getPendingCount(),
        ];

        return $this->response->setJSON($data);
    }
}
