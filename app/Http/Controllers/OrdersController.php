<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AuthController as AuthController;
use App\Http\Repository\ResponseRepository;

class OrdersController
{
    private ResponseRepository $_request;
    public string $getRequestType = 'GET';
    private \App\Http\Controllers\AuthController $_authInformation;

    public function __construct(ResponseRepository $request, AuthController $authInformation)
    {
        $this->_request = $request;
        $this->_authInformation = $authInformation;
    }

    public function retrieveData($requestType, $authInformation, $resourceType)
    {
        return $this->_request->getShopifyResponse($requestType, $authInformation,
            $resourceType);
    }

    public function normalizeShopEvents($shopEvents): array
    {
        foreach ($shopEvents as $key => $shopEvent) {
            $shopEvents[$key]['created_at'] = date("m/d/Y h:i:s",strtotime($shopEvent['created_at']));
        }

        return $shopEvents;
    }

    public function grabOrders(): array
    {
        $resourceType = 'orders.json';
        $shopEvents = $this->retrieveData($this->getRequestType,
            $this->_authInformation->authorizedUser(), $resourceType);

        return $this->normalizeShopEvents($shopEvents['body']['container']['orders']);
    }

    public function createOrderCSV()
    {
        $pathOrders = dirname(__DIR__, 3) . '/public/orders';
        if (!file_exists($pathOrders)) {
            mkdir('orders', 0777, true);
        }
        $fileName = 'orders-' . date("d-m-Y") . '.csv';
        $headerCSV = "Order number,Customer first name,Customer last name,Customer email,Time of purchase,Total price,Currency,Total discounts,Total tax,Financial status, ,Customer name for delivery,Customer address for delivery,Customer phone for delivery,Customer city for delivery,Customer zip for delivery,Customer country code for delivery\n";
        $fp = fopen(dirname(__DIR__, 3) . '/public/orders' . "/$fileName","wb");
        fwrite($fp,$headerCSV);
        $orderList = $this->grabOrders();
        foreach ($orderList as $order) {
            if(str_contains($order['shipping_address']['address1'], ',')) {
                $order['shipping_address']['address1'] = str_replace(',', '', $order['shipping_address']['address1']);
            }
            $dataCSV = "{$order['order_number']},{$order['customer']['first_name']},{$order['customer']['last_name']},{$order['email']},{$order['created_at']},{$order['total_price']},{$order['currency']},{$order['current_total_discounts']},{$order['current_total_tax']},{$order['financial_status']}, ,{$order['shipping_address']['name']},{$order['shipping_address']['address1']},{$order['shipping_address']['phone']},{$order['shipping_address']['city']},{$order['shipping_address']['zip']},{$order['shipping_address']['country_code']}\n";
            $fp = fopen(dirname(__DIR__, 3) . '/public/orders' . "/$fileName","a");
            fwrite($fp,$dataCSV);
        }
        fclose($fp);
        header('Content-Type: text');
        header("Content-Disposition: attachment; filename=$fileName");
        readfile($pathOrders . '/' . $fileName);

        return $this->showCompletePage();
    }

    public function showCompletePage(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('complete');
    }

    public function showDownloadPage(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('download');
    }
}
