<?php
class Helper_Subscription
{
    static function updateSubscriptionOfOrder($controller, $order)
    {
        $sub = $controller->getSingleObject('subscriptions', 'getSubscriptionById', array($order->getSubscriptionId()));
        self::updateSubscription($controller, $sub);
    }

    static function updateSubscription($controller, $sub)
    {
        if ($sub) {
            $callback = $controller->getObjKrcoConfig('callback_activate_subscription', 'subscribe');
            if (is_callable($callback)) {
                $callback($controller, $sub);
            }
            $oldNoOfPeriodPaid = $sub->getNoOfPeriodPaid();
            $sub->setNoOfPeriodPaid(1 + $oldNoOfPeriodPaid);
            $sub->setIsExpiringNotifiable(TRUE);
            $sub->setExpiringNotifLevel(0);
            $controller->subscriptions->updateSubscription($sub);
        }
    }

    static function getExpiryTimeOfSubscription($obj, $addPeriod=0)
    {
        $date = $obj->getStartDate();
        for ($i = 0; $i < $obj->getNoOfPeriodPaid() + $addPeriod; $i++) {
            if ($obj->getPeriod()) {
                $newTime = strtotime($date . ' + ' . $obj->getPeriod());
                if ($newTime) {
                    $date = Helper_Date::formatSqlDatetime($newTime);
                }
            }
        }
        $expiryTime = strtotime($date);
        return $expiryTime;
    }
}
