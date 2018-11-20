<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 28/06/18
 * Time: 15:29
 */

namespace EuroMillions\shared\components;


use Aws\Sqs\SqsClient;
use EuroMillions\shared\interfaces\IQueue;
use Phalcon\Config;

class AwsQueueImpl implements IQueue
{

    protected $sqs;

    protected $config;

    protected $awsConfig;

    protected $queueUrl;


    public function __construct($queue)
    {
        $this->initialize();
        $this->sqs = new SqsClient($this->awsConfig);
        $this->queueUrl = $queue;
    }

    protected function initialize()
    {
        $this->awsConfig = [
            'region' => 'eu-west-1',
            'version' => 'latest',
        ];
    }

    public function messageProducer($message)
    {
        try {
            $this->sqs->sendMessage([
                'QueueUrl' => $this->queueUrl,
                'MessageBody' => json_encode(
                    $message
                )
            ]);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function receiveMessage()
    {
        try {
            return $this->sqs->receiveMessage([
                'QueueUrl' => $this->queueUrl
            ]);

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function deleteMessage($message)
    {
        try {
            $this->sqs->deleteMessage(array(
                'QueueUrl' => $this->queueUrl,
                'ReceiptHandle' => $message
            ));
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}