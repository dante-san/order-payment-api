<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\OrderModel;
use App\Models\PaymentModel;
use CodeIgniter\HTTP\ResponseInterface;

class PaymentController extends BaseController
{
    protected $orderModel;
    protected $paymentModel;

    public function __construct()
    {
        $this->orderModel = new OrderModel();
        $this->paymentModel = new PaymentModel();
    }

    public function updateStatus($paymentId)
    {
        $json = $this->request->getJSON();

        if (!isset($json->status)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'status is required'
            ])->setStatusCode(400);
        }

        if (!in_array($json->status, ['SUCCESS', 'FAILED'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'status must be SUCCESS or FAILED'
            ])->setStatusCode(400);
        }

        $payment = $this->paymentModel->find($paymentId);

        if (!$payment) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Payment not found'
            ])->setStatusCode(404);
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $this->paymentModel->update($paymentId, [
                'status' => $json->status
            ]);

            $orderStatus = ($json->status === 'SUCCESS') ? 'COMPLETED' : 'FAILED';

            $this->orderModel->update($payment['order_id'], [
                'status' => $orderStatus
            ]);

            $db->transComplete();

            if ($db->transStatus() === false) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to update payment status'
                ])->setStatusCode(500);
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Payment status updated successfully',
                'data'    => $this->paymentModel->find($paymentId)
            ]);
        } catch (\Exception $e) {
            $db->transRollback();
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to update payment status'
            ])->setStatusCode(500);
        }
    }
}
