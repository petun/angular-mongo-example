<?php

namespace Petun\Reminders;

use MongoDB\BSON\ObjectID;
use MongoDB\Client;
use Petun\Reminders\Exceptions\MethodNowFoundException;

/**
 * Class Handler
 *
 * @author Petr Marochkin <petun911@gmail.com>
 */
class Handler
{
    const DB = 'reminders';

    /**
     * @var \MongoDB\Client
     */
    private $_client;

    /**
     * @var
     */
    private $_collection;

    public function handleRequest() {
        $this->_init();
        $method = isset($_GET['method']) ? 'action'. ucfirst($_GET['method']) : null;

        if (method_exists($this, $method)) {
            $this->{$method}();
        } else {
            throw new MethodNowFoundException('Method not found  - ' . $method);
        }
    }

    private function _init()
    {
        $this->_client = new Client();
        $this->_client->selectDatabase(self::DB);
        $this->_collection = $this->_client->{self::DB}->reminders;
    }

    public function actionIndex() {
        $cursor = $this->_collection->find([]);

        $result = [];
        foreach ($cursor as $document) {
            $r = (array)$document;
            $r['_id'] = (string)$document->_id;
            $result[] = $r;
        }

        echo json_encode($result);
    }

    public function actionUpdate() {
        $json = file_get_contents('php://input');
        $object = json_decode($json, true);

        $id = $object['_id'];
        unset($object['_id']);

        $updateResult = $this->_collection->updateOne(
            ['_id' => new ObjectID($id) ],
            ['$set' => $object]
        );

        printf("Matched %d document(s)\n", $updateResult->getMatchedCount());
        printf("Modified %d document(s)\n", $updateResult->getModifiedCount());
    }


    public function actionAdd() {
        $json = file_get_contents('php://input');
        $object = json_decode($json);

        $insertOneResult = $this->_collection->insertOne($object);


        if ($insertOneResult->getInsertedCount()) {
            echo json_encode(true);
        }
        //printf("Inserted %d document(s)\n", $insertOneResult->getInsertedCount());
        //var_dump($insertOneResult->getInsertedId());
    }


}