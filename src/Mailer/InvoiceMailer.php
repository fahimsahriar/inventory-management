<?php
// src/Mailer/InvoiceMailer.php
namespace App\Mailer;

use Cake\Mailer\Mailer;

class InvoiceMailer extends Mailer
{
    public function invoice($invoice, $products)
    {
        $this->setTransport('smtp') // or another mailer transport config
            ->setEmailFormat('html')
            ->setTo($invoice->email)
            ->setFrom(['fahim.sahriar@sjinnovation.com' => 'Your Website'])
            ->setSubject("Invoice #{$invoice->id}")
            ->setViewVars([
                'invoice' => $invoice,
                'products' => $products
            ])
            ->viewBuilder()
                ->setLayout('default')
                ->setTemplate('invoice_email'); // using the template src/Template/Email/html/invoice_email.php
    }
}

?>