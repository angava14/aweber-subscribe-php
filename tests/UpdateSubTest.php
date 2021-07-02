<?php

    class UpdateSubTest extends \PHPUnit\Framework\TestCase{

        private $op;

        public function setUp():void{
            $this->op = new Controller();

        }
        public function testUpdateSubNullClientID(){
            exec('php updateToken.php');
            $clientid = $this->op->getClient(); // Get Client ID
            $list = $this->op->getList($clientid); // Get List ID
            $data =[];
            $data = 'test';
            $this->expectException(InvalidArgumentException::class);
            $this->op->updateSub(null,$list,$data);
        }


        public function testUpdateSubNullListID(){
            exec('php updateToken.php');
            $clientid = $this->op->getClient(); // Get Client ID
            $data =[];
            $data = 'test';
            $this->expectException(InvalidArgumentException::class);
            $this->op->updateSub($clientid, null , $data);
        }

        public function testUpdateSubNotNullData(){
            exec('php updateToken.php');
            $clientid = $this->op->getClient(); // Get Client ID
            $list = $this->op->getList($clientid); // Get List ID
            $this->expectException(InvalidArgumentException::class);
            $this->op->updateSub($list,$clientid, null);
        }
    }

?>