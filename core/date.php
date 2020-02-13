<?php

namespace Common;

class Date
{
    public $begin_date;
    public $end_date;

    public function __construct()
    {
        self::setDate();
    }

    /**
     * 日付を設定する
     *
     * self::begin_date, self::end_dateにそれぞれ日付データを設定する
     */
    private function setDate()
    {
        // date_picker.phpから日付データがPOSTされたら、それをbegin_dateに設定する
        // それ以外の場合は当日の日付をbegin_dateに設定する
        if (isset($_POST['date'])) {
            $this->begin_date = filter_input(INPUT_POST, 'date', FILTER_SANITIZE_STRING);
        } else {
            $this->begin_date = date("Y-m-d");
        }

        $this->end_date = date("Y-m-d", strtotime("$this->begin_date +1 day", time()));
    }
}
