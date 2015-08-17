<?php
class Helper_AdminSirclo
{
    static function orderStatusToShipmentStatus($status, $lang = "en")
    {
        $_label_not_yet_shipped = $lang == "id" ? "Belum Dikirim" : "Not Yet Shipped";
        $_label_shipped = $lang == "id" ? "Telah Dikirim" : "Shipped";

        $shipments = array(
            'Pending' => array(
                'text' => $_label_not_yet_shipped,
                'class' => 'order-status-error',
            ),
            'Verifying Payment' => array(
                'text' => $_label_not_yet_shipped,
                'class' => 'order-status-error',
            ),
            'Processing' => array(
                'text' => $_label_not_yet_shipped,
                'class' => 'order-status-error',
            ),
            'Shipped' => array(
                'text' => $_label_shipped,
                'class' => 'order-status-success',
            ),
            'Cancelled' => array(
                'text' => 'N/A',
                'class' => 'order-status-none',
            ),
        );

        if (isset($shipments[$status])) {
            return $shipments[$status];
        }

        return array(
            'text' => 'N/A',
            'class' => 'order-status-none',
        );
    }
    
    static function orderStatusToPaymentStatus($status, $lang = "en")
    {
        $_label_not_yet_paid = $lang == "id" ? "Belum Dibayar" : "Not Yet Paid";
        $_label_verifying = $lang == "id" ? "Sedang Diverifikasi" : "Verifiying";
        $_label_paid = $lang == "id" ? "Telah Dibayar" : "Paid";

        $payments = array(
            'Pending' => array(
                'text' => $_label_not_yet_paid,
                'class' => 'order-status-error',
            ),
            'Verifying Payment' => array(
                'text' => $_label_verifying,
                'class' => 'order-status-warning',
            ),
            'Processing' => array(
                'text' => $_label_paid,
                'class' => 'order-status-success',
            ),
            'Shipped' => array(
                'text' => $_label_paid,
                'class' => 'order-status-success',
            ),
            'Cancelled' => array(
                'text' => 'N/A',
                'class' => 'order-status-none',
            ),
        );

        if (isset($payments[$status])) {
            return $payments[$status];
        }

        return array(
            'text' => 'N/A',
            'class' => 'order-status-none',
        );
    }
}
