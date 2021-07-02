<?php

    class AddSubTest extends \PHPUnit\Framework\TestCase{

        private $op;

        public function setUp():void{
            $this->op = new Controller();

        }
        
        public function testAddSubNullClientID(){
            exec('php updateToken.php');
            $clientid = $this->op->getClient(); // Get Client ID
            $list = $this->op->getList($clientid); // Get List ID
            $data =[];
            $data = 'test';
            $this->expectException(InvalidArgumentException::class);
            $this->op->addSub(null,$list,$data);
        }


        public function testAddSubNullListID(){
            exec('php updateToken.php');
            $clientid = $this->op->getClient(); // Get Client ID
            $data =[];
            $data = 'test';
            $this->expectException(InvalidArgumentException::class);
            $this->op->addSub($clientid, null , $data);
        }

        public function testAddSubNotNullData(){
            exec('php updateToken.php');
            $clientid = $this->op->getClient(); // Get Client ID
            $list = $this->op->getList($clientid); // Get List ID
            $this->expectException(InvalidArgumentException::class);
            $this->op->addSub($list,$clientid, null);
        }
    }

?>