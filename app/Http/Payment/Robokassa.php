<?php


namespace App\Http\Payment;


use App\Models\Test;
use Illuminate\Support\Facades\Log;

class Robokassa
{
    private string $merchant = 'personahuman';
    public string $password = 'lU3L6rhyEYUTuwnB345H';
    private int $invoice;
    private int $sum = 500;
    private string $description;
    private bool $mail = false;
    private ?string $email = null;

    /**
     * @return string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return bool
     */
    public function isMail(): bool
    {
        return $this->mail;
    }

    /**
     * @param bool $mail
     */
    public function setMail(bool $mail): void
    {
        $this->mail = $mail;
    }

    public function getHTMLButton(): string
    {
        $src = 'https://auth.robokassa.ru/Merchant/PaymentForm/FormSS.js?';
        $merchant = $this->getMerchant();
        $src .= 'MerchantLogin=' . $merchant;
        $src .= '&Shp_Mail=' . ($this->isMail() ? '1' : '0');
        $src .= '&Culture=ru';
        $src .= '&Encoding=utf-8';
        $src .= '&OutSum=' . $this->getSum();
        $src .= '&InvoiceID=' . $this->getInvoice();
        $src .= '&EMail=' . $this->getEmail();
        $src .= '&IsTest=0';
        $src .= '&Description=' . $this->getDescription();
        $src .= '&SignatureValue=' . md5(sprintf("%s:%s:%s:%s:Shp_Mail=%d",
                $merchant, $this->getSum(), $this->getInvoice(), $this->getPassword(), ($this->isMail() ? 1 : 0)));

        return "<script type='text/javascript' src='{$src}'></script>";
    }

    public function __construct(Test $test)
    {
        if(!isset($test->content)) return;
        $content = json_decode($test->content, true);
        if(!isset($content['robokassa'])) return;
        $robokassa = $content['robokassa'];

        $this->setMerchant($robokassa['merchant']);
        $this->setPassword($robokassa['password']);
        $this->setSum($robokassa['sum']);
    }

    public function getHTMLLink(): string
    {
        $src = 'https://auth.robokassa.ru/Merchant/Index.aspx?';
        $merchant = $this->getMerchant();
        $src .= 'MerchantLogin=' . $merchant;
        $src .= '&Shp_Mail=' . ($this->isMail() ? '1' : '0');
        $src .= '&Culture=ru';
        $src .= '&Encoding=utf-8';
        $src .= '&OutSum=' . $this->getSum();
        $src .= '&InvoiceID=' . $this->getInvoice();
        $src .= '&IsTest=0';
        $src .= '&EMail=' . $this->getEmail();
        $src .= '&Description=' . $this->getDescription();
        $src .= '&SignatureValue=' . md5(sprintf("%s:%s:%s:%s:Shp_Mail=%d",
                $merchant, $this->getSum(), $this->getInvoice(), $this->getPassword(), ($this->isMail() ? 1 : 0)));

        return "<a href=\"{$src}\">Оплатить через Робокассу</a>";
    }

    public function getFrameButton(): string
    {
        $src = "https://auth.robokassa.ru/Merchant/bundle/robokassa_iframe.js";
        $out = "<script type='text/javascript' src='{$src}'></script>";
        $merchant = $this->getMerchant();
        $onclick = sprintf(
            "Robokassa.StartPayment({\n" .
            "MerchantLogin: '%s',\n" .
            "Shp_Mail: '%d',\n" .
            "OutSum: '%d',\n" .
            "InvoiceID: '%d',\n" .
            "Email: '%s',\n" .
            "Description: '%s',\n" .
            "Culture: 'ru',\n" .
            "IsTest: '%d',\n" .
            "Encoding: 'utf-8',\n" .
            "SignatureValue: '%s'\n" .
            "})",
            $merchant, $this->isMail() ? 1 : 0, $this->getSum(), $this->getInvoice(), $this->getEmail(), $this->getDescription(), 0,
            md5(sprintf("%s:%s:%s:%s:Shp_Mail=%d",
                $merchant, $this->getSum(), $this->getInvoice(), $this->getPassword(), $this->isMail() ? 1 : 0))
        );
		if(session('branding')) {
			$buttonstyle = session('branding')['buttonstyle'];
			$class = "class='btn mt-2 mb-5' style='{$buttonstyle}'";
		} else {
			$class = "class='btn btn-primary mt-2 mb-5'";
		}

        return $out . "\n" .
            "<input type='submit'{$class} value='Оплатить через Робокассу' onclick=\"{$onclick}\">";
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return int
     */
    public function getInvoice(): int
    {
        return $this->invoice;
    }

    /**
     * @param int $invoice
     */
    public function setInvoice(int $invoice): void
    {
        $this->invoice = $invoice;
    }

    /**
     * @return int
     */
    public function getSum(): int
    {
        return $this->sum;
    }

    /**
     * @param int $sum
     */
    public function setSum(int $sum = 0): void
    {
        if($sum != 0) $this->sum = $sum;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = urlencode($description);
    }

    /**
     * @return string
     */
    public function getMerchant(): string
    {
        return $this->merchant;
    }

    /**
     * @param string $merchant
     */
    public function setMerchant(string $merchant): void
    {
        $this->merchant = $merchant;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }
}
