<?

namespace app\components;

use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\ItemList;
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

    public function checkOut($params)
    {
        $payer = new Payer();
        $payer->setPaymentMethod($params['method']);
        $orderList = [];

        $itemList = new ItemList();
        $itemList->setItems($orderList);

        $amount = new Amount();
        $amount->setCurrency($params['order']['currency'])
            ->setTotal($params['order']['total']);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($itemList)
            ->setDescription($params['order']['description'])
            ->setCustom($params['productId'])
            ->setInvoiceNumber(uniqid());

        $redirectUrl = Url::to([$this->redirectUrl], true);
        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl("$redirectUrl?success=true")
            ->setCancelUrl("$redirectUrl?success=false");

        $payment = new Payment();
        $payment->setIntent($params['intent'])
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
            $details = new Details();
            $amount = new Amount();

            $paymentId = Yii::$app->request->get()['paymentId'];
            $payment = Payment::get($paymentId, $this->apiContext);

            $execution->setPayerId(Yii::$app->request->get()['PayerID']);

            $amount->setDetails($details);

            $transaction->setAmount($amount);
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
            $paymentToJson = Json::decode(\Yii::$app->response->data);

            $payment = new \app\models\Payment();
            $payment->payment_user_id = Yii::$app->user->id;
            $payment->payment_plan_id = $paymentToJson['transactions']['0']['custom'];
            $payment->payment_created_at = date('Y-m-d H:i:s');
            $payment->save();
        }

        return null;
    }
}