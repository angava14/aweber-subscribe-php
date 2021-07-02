<?php

    class FindSubInListTest extends \PHPUnit\Framework\TestCase{

        private $op;

        public function setUp():void{
            $this->op = new Controller();

        }
        public function testFindInListNullClientID(){
            exec('php updateToken.php');
            $clientid = $this->op->getClient(); // Get Client ID
            $list = $this->op->getList($clientid); // Get List ID
            $email = 'test@test.com';
            $this->expectException(InvalidArgumentException::class);
            $this->op->findSubInList(null,$list,$email);
        }


        public function testFindInListNullListID(){
            exec('php updateToken.php');
            $clientid = $this->op->getClient(); // Get Client ID
            $email = 'test@test.com';
            $this->expectException(InvalidArgumentException::class);
            $this->op->findSubInList($clientid, null , $email);
        }

        public function testFindInListNotNullEmail(){
            exec('php updateToken.php');
            $clientid = $this->op->getClient(); // Get Client ID
            $list = $this->op->getList($clientid); // Get List ID
            $this->expectException(InvalidArgumentException::class);
            $this->op->findSubInList($list,$clientid, null);
        }
    }

?>