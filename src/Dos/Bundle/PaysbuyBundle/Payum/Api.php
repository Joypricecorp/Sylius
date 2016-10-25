<?php

namespace Dos\Bundle\PaysbuyBundle\Payum;

use Http\Message\MessageFactory;
use Payum\Core\Exception\Http\HttpException;
use Payum\Core\HttpClientInterface;
use Payum\Core\Reply\HttpPostRedirect;
use Payum\Core\Request\GetHttpRequest;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Api
{
    /*private static $ex_code = [
        '00' => 'Success',
        '02' => 'Processing',
        '90' => 'Required Fail',
        '91' => 'Incorrect Secure Code',
        '92' => 'Not found PSB ID or Email.',
        '93' => 'Incorrect PSB ID or Email.',
        '99' => 'Transaction Failed',
    ];*/

    private static $currencies = [
        'THB' => 'TH',
        'AUD' => 'AU',
        'GBP' => 'GB',
        'EUR' => 'FR',
        'HKD' => 'HK',
        'JPY' => 'JP',
        'NZD' => 'NZ',
        'SGD' => 'SG',
        'CHF' => 'CH',
        'USD' => 'US',
    ];

    /**
     * @var array
     */
    private $options = [];

    /**
     * @var HttpClientInterface
     */
    protected $client;

    /**
     * @var MessageFactory
     */
    protected $messageFactory;

    /**
     * @param array $options
     * @param HttpClientInterface $client
     * @param MessageFactory $messageFactory
     *
     * @throws \Payum\Core\Exception\InvalidArgumentException if an option is invalid
     */
    public function __construct(array $options, HttpClientInterface $client, MessageFactory $messageFactory)
    {
        $this->client = $client;
        $this->messageFactory = $messageFactory;

        $this->resolveOptions([
            'sandbox' => $options['sandbox'],
            'psbID' => $options['psbID'],
            'username' => $options['username'],
            'secureCode' => $options['secureCode'],
        ]);
    }

    private function resolveOptions(array $options)
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);

        $this->options = $resolver->resolve(array_replace_recursive($this->options, $options));
    }

    private function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'sandbox' => true,

            // paysbuy options
            'psbID' => '8051084553',
            'username' => 'plusmain_merchant@paysbuy.com',
            'secureCode' => 'f8a64c0f7141d10687b62d4ecb52721d',
            'inv' => null,
            'itm' => null,
            'amt' => null,
            'paypal_amt' => 1,
            'curr_type' => 'TH',
            'com' => '',
            'method' => 1,
            'language' => 'T',
            'resp_front_url' => null,
            'resp_back_url' => null,
            'opt_fix_redirect' => 0,
            'opt_fix_method' => 0,
            'opt_name' => '',
            'opt_email' => null,
            'opt_mobile' => '',
            'opt_address' => '',
            'opt_detail' => '',
            'opt_param' => [
                'cash_exp' => 24,
                'device_display' => 'm',
            ],
        ]);

        $resolver->setRequired([
            'psbID',
            'username',
            'secureCode',
            'inv',
            'itm',
            'amt',
            'curr_type',
            'method',
            'language',
            'resp_front_url',
            'resp_back_url',
        ]);
    }

    private function buildParams(array $options)
    {
        // remove none paysbuy params
        foreach ([
            'sandbox',
         ] as $key) {
            unset($options[$key]);
        }

        // convert currency
        $options['curr_type'] = self::$currencies[$options['curr_type']];

        if ($this->options['sandbox']) {
            $options['curr_type'] = 'TH';
            $options['amt'] = 1;
        }

        return $options;
    }

    protected function doRequest(array $fields, $method = 'POST')
    {
        $headers = [
            'Content-Type' => 'application/x-www-form-urlencoded',
        ];

        $request = $this->messageFactory->createRequest(
            $method,
            $this->getApiEndpoint(),
            $headers,
            http_build_query($fields)
        );

        $response = $this->client->send($request);

        if (false === ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300)) {
            throw HttpException::factory($request, $response);
        }

        $xml = simplexml_load_string($response->getBody());
        $json = json_encode($xml);
        $array = json_decode($json, true);

        if ('00' !== substr($array[0], 0, 2)) {
            throw HttpException::factory($request, $response);
        }

        return substr($array[0], 2);
    }

    /**
     * @param GetHttpRequest $httpRequest
     * @param $model
     */
    public function parseFeedbackResponse(GetHttpRequest $httpRequest, $model)
    {
        // get back from paysbuy
        $request = $httpRequest->request;

        $feedback = [];

        // default feedback
        $feedback['code'] = null;

        if (array_intersect(['result', 'apCode'], array_keys($request))) {
            $feedback['code'] = substr($request['result'], 0, 2);
            $feedback['message'] = substr($request['result'], 2);

            foreach($request as $key => $value) {
                $feedback[$key] = $value;
            }
        }
        //$feedback['code'] = '99';
        $model['feedback'] = $feedback;
    }

    public function authorize(array $options)
    {
        $this->resolveOptions($options);

        $params = $this->buildParams($this->options);

        return $this->doRequest($params);
    }

    public function purchase($token)
    {
        throw new HttpPostRedirect(sprintf($this->getPurchaseApiEndpoint(), $token));
    }

    public function isProcessing($code)
    {
        return '02' === $code;
    }

    public function isSuccess($code)
    {
        return '00' === $code;
    }

    public function isFailed($code)
    {
        return $code && !in_array($code, ['00', '02']);
    }

    /**
     * @return string
     */
    protected function getApiEndpoint()
    {
        return $this->options['sandbox']
            ? 'https://demo.paysbuy.com/api_paynow/api_paynow.asmx/api_paynow_authentication_new'
            : 'https://www.paysbuy.com/api_paynow/api_paynow.asmx/api_paynow_authentication_new'
        ;
    }

    /**
     * @return string
     */
    protected function getPurchaseApiEndpoint()
    {
        return $this->options['sandbox']
            ? 'https://demo.paysbuy.com/paynow.aspx?refid=%s'
            : 'https://www.paysbuy.com/paynow.aspx?refid=%s'
        ;
    }
}
