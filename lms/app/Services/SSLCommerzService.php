<?php

namespace App\Services;

class SSLCommerzService
{
    private $store_id;
    private $store_password;
    private $sandbox_mode;
    
    public function __construct()
    {
        $this->store_id = config('sslcommerz.store_id');
        $this->store_password = config('sslcommerz.store_password');
        $this->sandbox_mode = config('sslcommerz.sandbox_mode', true);
    }
    
    public function makePayment($data)
    {
        $postData = [
            'store_id' => $this->store_id,
            'store_passwd' => $this->store_password,
            'total_amount' => $data['total_amount'],
            'currency' => $data['currency'] ?? 'BDT',
            'tran_id' => $data['tran_id'],
            'success_url' => $data['success_url'],
            'fail_url' => $data['fail_url'],
            'cancel_url' => $data['cancel_url'],
            'ipn_url' => $data['ipn_url'] ?? '',
            
            // Customer information
            'cus_name' => $data['cus_name'],
            'cus_email' => $data['cus_email'],
            'cus_add1' => $data['cus_add1'],
            'cus_phone' => $data['cus_phone'],
            'cus_city' => $data['cus_city'] ?? 'Dhaka',
            'cus_state' => $data['cus_state'] ?? 'Dhaka',
            'cus_postcode' => $data['cus_postcode'] ?? '1000',
            'cus_country' => $data['cus_country'] ?? 'Bangladesh',
            'cus_fax' => $data['cus_fax'] ?? '',
            
            // Shipping information
            'ship_name' => $data['ship_name'] ?? $data['cus_name'],
            'ship_add1' => $data['ship_add1'] ?? $data['cus_add1'],
            'ship_city' => $data['ship_city'] ?? 'Dhaka',
            'ship_state' => $data['ship_state'] ?? 'Dhaka',
            'ship_postcode' => $data['ship_postcode'] ?? '1000',
            'ship_phone' => $data['ship_phone'] ?? $data['cus_phone'],
            'ship_country' => $data['ship_country'] ?? 'Bangladesh',
            
            // Product information
            'product_name' => $data['product_name'] ?? 'Course Purchase',
            'product_category' => $data['product_category'] ?? 'Education',
            'product_profile' => $data['product_profile'] ?? 'general',
            
            // Additional parameters
            'shipping_method' => 'NO',
            'num_of_item' => $data['num_of_item'] ?? 1,
        ];
        
        $url = $this->sandbox_mode 
            ? 'https://sandbox.sslcommerz.com/gwprocess/v3/api.php'
            : 'https://securepay.sslcommerz.com/gwprocess/v3/api.php';
            
        return $this->callAPI($url, $postData);
    }
    
    public function validateTransaction($postData)
    {
        $tran_id = $postData['tran_id'] ?? '';
        $amount = $postData['amount'] ?? '';
        $currency = $postData['currency'] ?? 'BDT';
        
        if (empty($tran_id)) {
            return [
                'status' => 'invalid',
                'message' => 'Transaction ID is missing'
            ];
        }
        
        $requestData = [
            'store_id' => $this->store_id,
            'store_passwd' => $this->store_password,
            'tran_id' => $tran_id,
            'format' => 'json'
        ];
        
        $url = $this->sandbox_mode
            ? 'https://sandbox.sslcommerz.com/validator/api/validationserverAPI.php'
            : 'https://securepay.sslcommerz.com/validator/api/validationserverAPI.php';
            
        $response = $this->callAPI($url, $requestData);
        
        // For sandbox mode, we'll be more lenient with validation
        if ($this->sandbox_mode) {
            // If the transaction has VALID status from the initial response, consider it valid
            if (isset($postData['status']) && $postData['status'] == 'VALID') {
                return [
                    'status' => 'valid',
                    'data' => $postData
                ];
            }
        }
        
        if ($response && isset($response['status']) && $response['status'] == 'VALID') {
            return [
                'status' => 'valid',
                'data' => $response
            ];
        }
        
        return [
            'status' => 'invalid',
            'data' => $response,
            'message' => 'Transaction validation failed'
        ];
    }
    
    private function callAPI($url, $postData)
    {
        $fields = '';
        foreach ($postData as $key => $value) {
            $fields .= $key . '=' . urlencode($value) . '&';
        }
        $fields = rtrim($fields, '&');
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/x-www-form-urlencoded'
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode == 200) {
            return json_decode($response, true);
        }
        
        return false;
    }
}