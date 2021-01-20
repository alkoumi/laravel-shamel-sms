<?php

namespace Alkoumi\LaravelShamelSms;

use Illuminate\Support\Facades\Mail;

class ShamelSMS
{

    public function __construct()
    {
        $this->fetchConfig();
        $this->currentBalance = $this->getBalance();
        $this->balanceStatus();
    }

    /**
     * @param string $message The Message you want to send
     * @param array $to Array of Numbers to recive your message
     * @return string
     * @throws \Exception
     */
    public function sendSMS(string $message, array $to): string
    {
        $this->validateParams($message, $to);
        return $this->hit($this->getSendUrl($message, $to));
    }

    /**
     * @return int the balance number still
     */
    public function getBalance(): int
    {
        return $this->hit($this->getBalanceUrl());
    }

    /**
     * @return string Balance Status
     */
    public function balanceStatus(): string
    {
        if ($this->currentBalance <= $this->notify_under) {
            $this->sendSMS(' إنتبه 😱 إلى الرصيد في حساب شامل للرسائل ' . $this->currentBalance, [$this->admin_mobile]);
            return 'Your Balance is ' . $this->currentBalance . ' Less Than ' . $this->notify_under;
        }
        return 'Your Balance is : ' . $this->currentBalance . ', 🤩 More than ' . $this->notify_under;
    }

    /**
     * @param array $numbers
     * @return bool
     */
    protected function enoughBalanceToSend(array $numbers): bool
    {
        if ($this->currentBalance < count($numbers)) {
            abort(403, $this->currentBalance . '!إنتبه بارك الله فيك ، لايوجد لديك رصيد كافي رصيدكم : ');
        }
        return true;
    }

    /**
     * @param string $url
     * @return string content respode
     */
    protected function hit(string $url): string
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        $content = curl_exec($ch);
        return $this->getSendResults($content);
    }

    /**
     * @param string $content
     * @return string
     */
    protected function getSendResults(string $content): string
    {
        //dump($content);
        //dd(is_numeric($content));
        // Return The Content id it's A Numeric
        if (is_numeric($content)) return $content;

        //Explode the text content to Result Array
        $results = explode(',', $content);

        //Notify Admin in his Mail About the Sending status
        Mail::send([], [], function ($message) use ($results) {
            $message->to($this->admin_email)
                ->subject('حالة إرسال رسالة من حساب شامل الخاص بك - ' . config('app.name'))
                ->setBody('<h1>' . $results['0'] . ' كود رقم ' . '<br>' . $results['1'] . '</h1>', 'text/html');
        });

        // Returning The content or Abort with message
        if ($results['0'] == 4 || $results['0'] == 3101) {
            return $content;
        } else {
            abort(403, $results['1']);
        }
    }

    /**
     * @param string $message
     * @param array $numbers
     * @return void
     * @throws \Exception
     */
    protected function validateParams(string $message, array $numbers): void
    {
        if (!$message || empty($message)) abort(403, 'لم تقم بكتابة رسالة لإرسالها !إنتبه بارك الله فيك');
        if (!$numbers || empty($numbers)) abort(403, 'لم تقم بكتابة الأرقام !إنتبه بارك الله فيك');
        if (!is_array($numbers)) abort(403, 'الأرقام لابد أن تكون على هيئة مصفوفة [] !إنتبه بارك الله فيك');
    }

    /**
     * @param array $numbers
     * @param string $message
     * @return string
     */
    protected function getSendUrl(string $message, array $numbers): string
    {
        //$url = "http://www.shamelsms.net/api/httpSms.aspx?username=$username&password=$password&mobile=$mobile&message=$message&sender=$senderName&unicodetype=U";
        return $url = $this->base_uri . $this->send_uri . http_build_query
            ([
                'username' => $this->username,
                'password' => $this->password,
                'sender' => $this->formal_sender,
                'unicodetype' => $this->unicodetype,
                'mobile' => $this->getNumbers($numbers),
                'message' => $message,
            ]);
    }

    /**
     * @return string
     */
    protected function getBalanceUrl(): string
    {
        return $url = $this->base_uri . $this->main_uri . http_build_query
            ([
                'code' => $this->balance_code,
                'username' => $this->username,
                'password' => $this->password,
            ]);
    }

    /**
     * @param array $numbers
     * @return string
     * @throws \Exception
     */
    protected function getNumbers(array $numbers): string
    {
        $numbers = $this->removeDuplication($numbers);
        $this->enoughBalanceToSend($numbers);
        return $this->parseNumbers($numbers);
    }

    /**
     * @param array $numbers
     * @return string
     * @throws \Exception
     */
    protected function parseNumbers(array $numbers): string
    {
        $parsedNumbers = '';

        for ($i = 0; $i < count($numbers); $i++) {
            if ((!is_numeric($numbers[$i])) || (strlen($numbers[$i]) != 10) || (substr($numbers[$i], 0, 2) != '05')) {
                abort(403, 'لابد أن تكون صيغة الأرقام صحيحة مثلا 0500175200 !إنتبه بارك الله فيك');
            }
            $parsedNumbers .= '966' . substr($numbers[$i], 1, strlen($numbers[$i]) - 1) . ',';
        }
        $parsedNumbers = substr($parsedNumbers, 0, strlen($parsedNumbers) - 1);

        return $parsedNumbers;
    }

    /**
     * @param array $numbers
     * @return array
     */
    protected function removeDuplication(array $numbers): array
    {
        return $numbers = array_values(array_unique($numbers));
    }

    /**
     * Fetching Configs from Config File to Construcor
     */
    protected function fetchConfig(): void
    {
        $config = config('shamelsms');
        foreach ($config as $key => $value) {
            $this->{$key} = $value;
        }
    }
}
