<?php

namespace App;

use Carbon\CarbonImmutable;
use DateTimeInterface;
use RuntimeException;

class Helpers
{
    /**
     * @return string 時刻から取引IDを自動生成する関数
     */
    public static function generateOrderId(): string
    {
        return "dummy".time().substr(explode(".", (microtime(true).""))[1], 0, 3);
    }

    /**
     * @param  string | null  $jpo1  10,21,61,80
     * @param  string | null  $jpo2  分割回数
     * @return string | null DTOにセットするjpo(支払種別)を作成する関数
     */
    public static function generateJpo(?string $jpo1, ?string $jpo2): ?string
    {
        if (empty($jpo1)) {
            return null;
        }

        if (in_array($jpo1, array("10", "21", "80"))) {
            return $jpo1;
        }

        if ($jpo1 == "61" && !empty($jpo2)) {
            return "61C".$jpo2;
        }

        return null;
    }

    /**
     * @param  DateTimeInterface  $createdAt
     * @param  int  $paymentPlusDay
     * @param  string  $finalDeadline
     * @return DateTimeInterface
     */
    public static function calcPaymentDeadline(DateTimeInterface $createdAt, int $paymentPlusDay, string $finalDeadline): DateTimeInterface
    {
        if (strlen($finalDeadline) < 10) {
            throw new RuntimeException();
        }
        $deadline = CarbonImmutable::create(substr($finalDeadline, 0, 4), substr($finalDeadline, 5, 2), substr($finalDeadline, 8, 2));
        $limit = CarbonImmutable::instance($createdAt)->addDays($paymentPlusDay);
        if ($limit < $deadline) {
            $deadline = $limit;
        }
        return $deadline->setTime(23, 59);
    }

    /**
     * @param  array  $numberOfReservations
     * @param  int  $typeId
     * @param  string  $s
     * @return bool
     */
    public static function isInNumberOfReservations(array $numberOfReservations, int $typeId, string $s): bool
    {
        foreach (range(1, 3) as $i) {
            if (array_key_exists(sprintf('type%d_%s_%d_number', $typeId, $s, $i), $numberOfReservations)) {
                return true;
            }
        }
        return false;
    }
}
