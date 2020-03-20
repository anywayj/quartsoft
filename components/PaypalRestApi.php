<?

namespace app\components;

use PayPal\Api\Amount;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Api\PaymentExecution;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use yii\helpers\Url;
use yii\helpers\Json;
use Yii;

class PayPalRestApi
{
    public $apiContext;
    public $redirectUrl;

    public function __construct()
    {
        $apiContext = new ApiContext(
            new OAuthTokenCredential(
                Yii::$app->params['payPalClientId'],
                Yii::$app->params['payPalClientSecret']
            )
        );

        $this->apiContext = $apiContext;
    }

    public function checkOut($planId, $price)
    {
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $amount = new Amount();
        $amount->setCurrency('USD')
            ->setTotal($price);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setCustom($planId)
            ->setInvoiceNumber(uniqid());

        $redirectUrl = Url::to([$this->redirectUrl], true);
        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl("$redirectUrl?success=true")
            ->setCancelUrl("$redirectUrl?success=false");

        $payment = new Payment();
        $payment->setIntent('sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirectUrls)
            ->setTransactions([$transaction]);
        $payment->create($this->apiContext);

        return \Yii::$app->controller->redirect($payment->getApprovalLink());
    }

    public function processPayment()
    {
        if (isset(Yii::$app->request->get()['success']) && Yii::$app->request->get()['success'] == 'true') {
            $execution = new PaymentExecution();
            $transaction = new Transaction();

            $paymentId = Yii::$app->request->get()['paymentId'];
            $payment = Payment::get($paymentId, $this->apiContext);

            $execution->setPayerId(Yii::$app->request->get()['PayerID']);
            $execution->addTransaction($transaction);

            try {
                $payment->execute($execution, $this->apiContext);
                try {
                    $payment = Payment::get($paymentId, $this->apiContext);
                } catch (\Exception $ex) {
                    \Yii::$app->response->format = \yii\web\Response::FORMAT_HTML;
                    \Yii::$app->response->data = $ex->getData();
                }
            } catch (\Exception $ex) {
                \Yii::$app->response->format = \yii\web\Response::FORMAT_HTML;
                \Yii::$app->response->data = $ex->getData();
            }
            \Yii::$app->response->data = $payment;
            return Json::decode(\Yii::$app->response->data);
        }

        return null;
    }
}